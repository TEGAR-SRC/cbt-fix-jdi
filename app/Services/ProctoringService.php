<?php

namespace App\Services;

use App\Models\ProctoringSession;
use App\Models\ProctoringActivityLog;
use App\Models\ProctoringPhoto;
use App\Models\ProctoringViolation;
use App\Models\ProctoringNetworkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProctoringService
{
    /**
     * Start a new proctoring session
     */
    public function startSession(array $data): ProctoringSession
    {
        // Generate unique session ID
        $sessionId = 'session_' . Str::random(16) . '_' . time();
        
        $session = ProctoringSession::create([
            'session_id' => $sessionId,
            'participant_id' => $data['participant_id'],
            'device_id' => $data['device_id'] ?? null,
            'device_model' => $data['device_model'] ?? null,
            'android_version' => $data['android_version'] ?? null,
            'app_version' => $data['app_version'] ?? null,
            'started_at' => now(),
            'status' => 'active',
            'device_info' => $data['device_info'] ?? null,
        ]);
        
        // Log session start activity
        $this->logActivity($session, [
            'activity_type' => ProctoringActivityLog::ACTIVITY_APP_LAUNCH,
            'timestamp' => now(),
            'description' => 'Proctoring session started',
            'severity' => ProctoringActivityLog::SEVERITY_INFO,
            'metadata' => [
                'session_id' => $sessionId,
                'device_info' => $data['device_info'] ?? null,
            ]
        ]);
        
        return $session;
    }

    /**
     * End a proctoring session
     */
    public function endSession(ProctoringSession $session, string $status = 'completed', string $notes = null): ProctoringSession
    {
        $session->update([
            'ended_at' => now(),
            'status' => $status,
            'notes' => $notes,
        ]);
        
        // Log session end activity
        $this->logActivity($session, [
            'activity_type' => ProctoringActivityLog::ACTIVITY_APP_EXIT,
            'timestamp' => now(),
            'description' => "Proctoring session ended with status: {$status}",
            'severity' => $status === 'completed' ? ProctoringActivityLog::SEVERITY_INFO : ProctoringActivityLog::SEVERITY_WARNING,
            'metadata' => [
                'end_status' => $status,
                'notes' => $notes,
                'duration_minutes' => $session->started_at->diffInMinutes(now()),
            ]
        ]);
        
        return $session;
    }

    /**
     * Log an activity
     */
    public function logActivity(ProctoringSession $session, array $data): ProctoringActivityLog
    {
        $activityLog = ProctoringActivityLog::create([
            'proctoring_session_id' => $session->id,
            'activity_type' => $data['activity_type'],
            'activity_subtype' => $data['activity_subtype'] ?? null,
            'timestamp' => $data['timestamp'] ?? now(),
            'metadata' => $data['metadata'] ?? null,
            'description' => $data['description'] ?? null,
            'severity' => $data['severity'] ?? ProctoringActivityLog::SEVERITY_INFO,
            'is_violation' => $data['is_violation'] ?? false,
        ]);
        
        // If this is a violation, increment the session violation count
        if ($activityLog->is_violation) {
            $session->incrementViolationCount();
        }
        
        return $activityLog;
    }

    /**
     * Upload a proctoring photo
     */
    public function uploadPhoto(ProctoringSession $session, Request $request): ProctoringPhoto
    {
        $file = $request->file('photo');
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "proctoring_{$session->session_id}_{$timestamp}." . $file->getClientOriginalExtension();
        
        // Store photo in proctoring directory
        $path = $file->storeAs('proctoring/photos/' . $session->participant_id, $filename, 'public');
        
        $photo = ProctoringPhoto::create([
            'proctoring_session_id' => $session->id,
            'photo_filename' => $filename,
            'photo_path' => $path,
            'photo_url' => Storage::url($path),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'captured_at' => $request->captured_at ?? now(),
            'camera_metadata' => $request->camera_metadata ?? null,
            'notes' => $request->notes ?? null,
        ]);
        
        // Increment photo count
        $session->incrementPhotoCount();
        
        // Log photo capture activity
        $this->logActivity($session, [
            'activity_type' => ProctoringActivityLog::ACTIVITY_PHOTO_CAPTURE,
            'timestamp' => $photo->captured_at,
            'description' => 'Proctoring photo captured',
            'severity' => ProctoringActivityLog::SEVERITY_INFO,
            'metadata' => [
                'photo_id' => $photo->id,
                'filename' => $filename,
                'file_size' => $photo->file_size,
                'camera_metadata' => $request->camera_metadata ?? null,
            ]
        ]);
        
        return $photo;
    }

    /**
     * Report a violation
     */
    public function reportViolation(ProctoringSession $session, array $data): ProctoringViolation
    {
        $violation = ProctoringViolation::create([
            'proctoring_session_id' => $session->id,
            'violation_type' => $data['violation_type'],
            'violation_category' => $data['violation_category'] ?? $this->getCategoryForViolationType($data['violation_type']),
            'detected_at' => now(),
            'severity' => $data['severity'],
            'description' => $data['description'],
            'evidence' => $data['evidence'] ?? null,
            'action_taken' => $data['action_taken'] ?? null,
        ]);
        
        // Increment violation count
        $session->incrementViolationCount();
        
        // Log violation activity
        $this->logActivity($session, [
            'activity_type' => ProctoringActivityLog::ACTIVITY_SECURITY_VIOLATION,
            'timestamp' => $violation->detected_at,
            'description' => "Security violation detected: {$violation->violation_type}",
            'severity' => $this->mapViolationSeverityToLogSeverity($violation->severity),
            'is_violation' => true,
            'metadata' => [
                'violation_id' => $violation->id,
                'violation_type' => $violation->violation_type,
                'violation_category' => $violation->violation_category,
                'evidence' => $data['evidence'] ?? null,
            ]
        ]);
        
        // Check if violation threshold is exceeded
        $this->checkViolationThreshold($session);
        
        return $violation;
    }

    /**
     * Log network status
     */
    public function logNetworkStatus(ProctoringSession $session, array $data): ProctoringNetworkLog
    {
        $networkLog = ProctoringNetworkLog::create([
            'proctoring_session_id' => $session->id,
            'connection_status' => $data['connection_status'],
            'network_type' => $data['network_type'] ?? null,
            'status_changed_at' => now(),
            'duration_seconds' => $data['duration_seconds'] ?? null,
            'additional_info' => $data['additional_info'] ?? null,
            'exam_paused' => $data['exam_paused'] ?? false,
        ]);
        
        // Log network activity
        $this->logActivity($session, [
            'activity_type' => ProctoringActivityLog::ACTIVITY_NETWORK_CHANGE,
            'timestamp' => $networkLog->status_changed_at,
            'description' => "Network status changed to: {$networkLog->connection_status}",
            'severity' => $networkLog->connection_status === 'disconnected' ? ProctoringActivityLog::SEVERITY_WARNING : ProctoringActivityLog::SEVERITY_INFO,
            'metadata' => [
                'connection_status' => $networkLog->connection_status,
                'network_type' => $networkLog->network_type,
                'exam_paused' => $networkLog->exam_paused,
                'duration_seconds' => $networkLog->duration_seconds,
            ]
        ]);
        
        // If disconnected, create a violation
        if ($networkLog->connection_status === 'disconnected') {
            $this->reportViolation($session, [
                'violation_type' => ProctoringViolation::TYPE_NETWORK_DISCONNECTED,
                'violation_category' => ProctoringViolation::CATEGORY_NETWORK,
                'severity' => ProctoringViolation::SEVERITY_MEDIUM,
                'description' => 'Network connection lost during exam',
                'evidence' => [
                    'network_log_id' => $networkLog->id,
                    'disconnection_time' => $networkLog->status_changed_at,
                    'network_type' => $networkLog->network_type,
                ],
                'action_taken' => $networkLog->exam_paused ? 'Exam paused automatically' : 'No action taken',
            ]);
        }
        
        return $networkLog;
    }

    /**
     * Check if violation threshold is exceeded
     */
    private function checkViolationThreshold(ProctoringSession $session): void
    {
        $maxViolations = config('proctoring.max_violations', 3);
        
        if ($session->violation_count >= $maxViolations) {
            // Auto-submit or terminate session
            if (config('proctoring.auto_submit_enabled', true)) {
                $this->endSession($session, 'terminated', "Exam terminated due to excessive violations ({$session->violation_count} violations)");
                
                // Log auto-submission
                $this->logActivity($session, [
                    'activity_type' => 'auto_submit',
                    'timestamp' => now(),
                    'description' => "Exam auto-submitted due to {$session->violation_count} violations",
                    'severity' => ProctoringActivityLog::SEVERITY_CRITICAL,
                    'is_violation' => true,
                    'metadata' => [
                        'violation_count' => $session->violation_count,
                        'max_violations' => $maxViolations,
                        'auto_submit_reason' => 'violation_threshold_exceeded',
                    ]
                ]);
            }
        }
    }

    /**
     * Get category for violation type
     */
    private function getCategoryForViolationType(string $violationType): string
    {
        $typeToCategory = [
            ProctoringViolation::TYPE_APP_EXIT_ATTEMPT => ProctoringViolation::CATEGORY_BEHAVIOR,
            ProctoringViolation::TYPE_SCREENSHOT_ATTEMPT => ProctoringViolation::CATEGORY_SECURITY,
            ProctoringViolation::TYPE_RECORDING_ATTEMPT => ProctoringViolation::CATEGORY_SECURITY,
            ProctoringViolation::TYPE_MULTI_WINDOW => ProctoringViolation::CATEGORY_SECURITY,
            ProctoringViolation::TYPE_ROOT_DETECTED => ProctoringViolation::CATEGORY_DEVICE,
            ProctoringViolation::TYPE_EMULATOR_DETECTED => ProctoringViolation::CATEGORY_DEVICE,
            ProctoringViolation::TYPE_NETWORK_DISCONNECTED => ProctoringViolation::CATEGORY_NETWORK,
            ProctoringViolation::TYPE_CAMERA_BLOCKED => ProctoringViolation::CATEGORY_TECHNICAL,
            ProctoringViolation::TYPE_UNAUTHORIZED_ACCESS => ProctoringViolation::CATEGORY_SECURITY,
            ProctoringViolation::TYPE_SUSPICIOUS_ACTIVITY => ProctoringViolation::CATEGORY_BEHAVIOR,
        ];
        
        return $typeToCategory[$violationType] ?? ProctoringViolation::CATEGORY_BEHAVIOR;
    }

    /**
     * Map violation severity to log severity
     */
    private function mapViolationSeverityToLogSeverity(string $violationSeverity): string
    {
        $severityMap = [
            ProctoringViolation::SEVERITY_LOW => ProctoringActivityLog::SEVERITY_INFO,
            ProctoringViolation::SEVERITY_MEDIUM => ProctoringActivityLog::SEVERITY_WARNING,
            ProctoringViolation::SEVERITY_HIGH => ProctoringActivityLog::SEVERITY_ERROR,
            ProctoringViolation::SEVERITY_CRITICAL => ProctoringActivityLog::SEVERITY_CRITICAL,
        ];
        
        return $severityMap[$violationSeverity] ?? ProctoringActivityLog::SEVERITY_WARNING;
    }

    /**
     * Get session statistics
     */
    public function getSessionStatistics(ProctoringSession $session): array
    {
        return [
            'session_duration_minutes' => $session->ended_at ? $session->started_at->diffInMinutes($session->ended_at) : $session->started_at->diffInMinutes(now()),
            'total_activities' => $session->activityLogs()->count(),
            'total_violations' => $session->violations()->count(),
            'total_photos' => $session->photos()->count(),
            'network_disconnections' => $session->networkLogs()->disconnected()->count(),
            'critical_violations' => $session->violations()->bySeverity('critical')->count(),
            'violation_rate' => $session->violation_count > 0 ? round($session->violation_count / max($session->started_at->diffInMinutes(now()), 1), 2) : 0,
            'photo_rate' => $session->photo_count > 0 ? round($session->photo_count / max($session->started_at->diffInMinutes(now()), 1), 2) : 0,
        ];
    }

    /**
     * Clean up old session data
     */
    public function cleanupOldSessions(int $daysOld = 30): int
    {
        $cutoffDate = Carbon::now()->subDays($daysOld);
        
        $oldSessions = ProctoringSession::where('ended_at', '<', $cutoffDate)
            ->orWhere(function ($query) use ($cutoffDate) {
                $query->whereNull('ended_at')
                    ->where('started_at', '<', $cutoffDate);
            })
            ->get();
        
        $deletedCount = 0;
        
        foreach ($oldSessions as $session) {
            // Delete associated photos from storage
            foreach ($session->photos as $photo) {
                $photo->deletePhotoFile();
            }
            
            // Delete session and all related data (cascade)
            $session->delete();
            $deletedCount++;
        }
        
        return $deletedCount;
    }
}