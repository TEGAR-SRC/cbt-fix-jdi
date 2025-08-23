<?php

namespace App\Http\Controllers;

use App\Models\ProctoringSession;
use App\Models\ProctoringActivityLog;
use App\Models\ProctoringPhoto;
use App\Models\ProctoringViolation;
use App\Models\ProctoringNetworkLog;
use App\Services\ProctoringService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProctoringController extends Controller
{
    protected $proctoringService;

    public function __construct(ProctoringService $proctoringService)
    {
        $this->proctoringService = $proctoringService;
    }

    /**
     * Start a new proctoring session
     */
    public function startSession(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'participant_id' => 'required|string',
            'device_id' => 'nullable|string',
            'device_model' => 'nullable|string',
            'android_version' => 'nullable|string',
            'app_version' => 'nullable|string',
            'device_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $session = $this->proctoringService->startSession($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Proctoring session started successfully',
                'data' => [
                    'session_id' => $session->session_id,
                    'participant_id' => $session->participant_id,
                    'started_at' => $session->started_at,
                    'status' => $session->status,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start proctoring session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * End a proctoring session
     */
    public function endSession(Request $request, string $sessionId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|string|in:completed,terminated,error',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $session = ProctoringSession::where('session_id', $sessionId)->firstOrFail();
            
            $this->proctoringService->endSession($session, $request->input('status', 'completed'), $request->input('notes'));
            
            return response()->json([
                'success' => true,
                'message' => 'Proctoring session ended successfully',
                'data' => [
                    'session_id' => $session->session_id,
                    'ended_at' => $session->ended_at,
                    'status' => $session->status,
                    'total_violations' => $session->violation_count,
                    'total_photos' => $session->photo_count,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to end proctoring session',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log an activity
     */
    public function logActivity(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string',
            'activity_type' => 'required|string',
            'activity_subtype' => 'nullable|string',
            'timestamp' => 'required|date',
            'metadata' => 'nullable|array',
            'description' => 'nullable|string',
            'severity' => 'nullable|string|in:info,warning,error,critical',
            'is_violation' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $session = ProctoringSession::where('session_id', $request->session_id)->firstOrFail();
            
            $log = $this->proctoringService->logActivity($session, $request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Activity logged successfully',
                'data' => [
                    'log_id' => $log->id,
                    'activity_type' => $log->activity_type,
                    'timestamp' => $log->timestamp,
                    'is_violation' => $log->is_violation,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to log activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload a proctoring photo
     */
    public function uploadPhoto(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'captured_at' => 'required|date',
            'camera_metadata' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $session = ProctoringSession::where('session_id', $request->session_id)->firstOrFail();
            
            $photo = $this->proctoringService->uploadPhoto($session, $request);
            
            return response()->json([
                'success' => true,
                'message' => 'Photo uploaded successfully',
                'data' => [
                    'photo_id' => $photo->id,
                    'filename' => $photo->photo_filename,
                    'captured_at' => $photo->captured_at,
                    'file_size' => $photo->file_size,
                    'url' => $photo->full_url,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Report a violation
     */
    public function reportViolation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string',
            'violation_type' => 'required|string',
            'violation_category' => 'nullable|string',
            'severity' => 'required|string|in:low,medium,high,critical',
            'description' => 'required|string',
            'evidence' => 'nullable|array',
            'action_taken' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $session = ProctoringSession::where('session_id', $request->session_id)->firstOrFail();
            
            $violation = $this->proctoringService->reportViolation($session, $request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Violation reported successfully',
                'data' => [
                    'violation_id' => $violation->id,
                    'violation_type' => $violation->violation_type,
                    'severity' => $violation->severity,
                    'detected_at' => $violation->detected_at,
                    'total_violations' => $session->fresh()->violation_count,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to report violation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log network status
     */
    public function logNetworkStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string',
            'connection_status' => 'required|string|in:connected,disconnected,poor,excellent',
            'network_type' => 'nullable|string',
            'duration_seconds' => 'nullable|integer',
            'additional_info' => 'nullable|string',
            'exam_paused' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $session = ProctoringSession::where('session_id', $request->session_id)->firstOrFail();
            
            $networkLog = $this->proctoringService->logNetworkStatus($session, $request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Network status logged successfully',
                'data' => [
                    'log_id' => $networkLog->id,
                    'connection_status' => $networkLog->connection_status,
                    'network_type' => $networkLog->network_type,
                    'exam_paused' => $networkLog->exam_paused,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to log network status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get session summary
     */
    public function getSessionSummary(string $sessionId): JsonResponse
    {
        try {
            $session = ProctoringSession::with(['activityLogs', 'photos', 'violations', 'networkLogs'])
                ->where('session_id', $sessionId)
                ->firstOrFail();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'session' => [
                        'session_id' => $session->session_id,
                        'participant_id' => $session->participant_id,
                        'device_model' => $session->device_model,
                        'android_version' => $session->android_version,
                        'started_at' => $session->started_at,
                        'ended_at' => $session->ended_at,
                        'status' => $session->status,
                        'violation_count' => $session->violation_count,
                        'photo_count' => $session->photo_count,
                    ],
                    'statistics' => [
                        'total_activities' => $session->activityLogs->count(),
                        'total_violations' => $session->violations->count(),
                        'total_photos' => $session->photos->count(),
                        'network_disconnections' => $session->networkLogs->where('connection_status', 'disconnected')->count(),
                        'critical_violations' => $session->violations->where('severity', 'critical')->count(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}