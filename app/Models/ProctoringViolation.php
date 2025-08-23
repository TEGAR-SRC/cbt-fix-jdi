<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProctoringViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'proctoring_session_id',
        'violation_type',
        'violation_category',
        'detected_at',
        'severity',
        'description',
        'evidence',
        'action_taken',
        'auto_resolved',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
        'evidence' => 'json',
        'auto_resolved' => 'boolean',
    ];

    /**
     * Get the proctoring session that owns this violation
     */
    public function proctoringSession(): BelongsTo
    {
        return $this->belongsTo(ProctoringSession::class);
    }

    /**
     * Scope for unresolved violations
     */
    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }

    /**
     * Scope for resolved violations
     */
    public function scopeResolved($query)
    {
        return $query->whereNotNull('resolved_at');
    }

    /**
     * Scope by severity
     */
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope by violation type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('violation_type', $type);
    }

    /**
     * Check if violation is resolved
     */
    public function isResolved(): bool
    {
        return !is_null($this->resolved_at);
    }

    /**
     * Resolve this violation
     */
    public function resolve(string $notes = null, bool $autoResolved = false): void
    {
        $this->update([
            'resolved_at' => now(),
            'resolution_notes' => $notes,
            'auto_resolved' => $autoResolved,
        ]);
    }

    /**
     * Violation type constants
     */
    const TYPE_APP_EXIT_ATTEMPT = 'app_exit_attempt';
    const TYPE_SCREENSHOT_ATTEMPT = 'screenshot_attempt';
    const TYPE_RECORDING_ATTEMPT = 'recording_attempt';
    const TYPE_MULTI_WINDOW = 'multi_window_detected';
    const TYPE_ROOT_DETECTED = 'root_detected';
    const TYPE_EMULATOR_DETECTED = 'emulator_detected';
    const TYPE_NETWORK_DISCONNECTED = 'network_disconnected';
    const TYPE_CAMERA_BLOCKED = 'camera_blocked';
    const TYPE_UNAUTHORIZED_ACCESS = 'unauthorized_access';
    const TYPE_SUSPICIOUS_ACTIVITY = 'suspicious_activity';

    /**
     * Violation category constants
     */
    const CATEGORY_SECURITY = 'security';
    const CATEGORY_NETWORK = 'network';
    const CATEGORY_DEVICE = 'device';
    const CATEGORY_BEHAVIOR = 'behavior';
    const CATEGORY_TECHNICAL = 'technical';

    /**
     * Severity constants
     */
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';
}