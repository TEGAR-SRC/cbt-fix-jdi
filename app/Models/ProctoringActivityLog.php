<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProctoringActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'proctoring_session_id',
        'activity_type',
        'activity_subtype',
        'timestamp',
        'metadata',
        'description',
        'severity',
        'is_violation',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'metadata' => 'json',
        'is_violation' => 'boolean',
    ];

    /**
     * Get the proctoring session that owns this log
     */
    public function proctoringSession(): BelongsTo
    {
        return $this->belongsTo(ProctoringSession::class);
    }

    /**
     * Scope for violation logs
     */
    public function scopeViolations($query)
    {
        return $query->where('is_violation', true);
    }

    /**
     * Scope by severity
     */
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope by activity type
     */
    public function scopeByActivityType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Activity type constants
     */
    const ACTIVITY_APP_LAUNCH = 'app_launch';
    const ACTIVITY_APP_EXIT = 'app_exit';
    const ACTIVITY_APP_PAUSE = 'app_pause';
    const ACTIVITY_APP_RESUME = 'app_resume';
    const ACTIVITY_WEBVIEW_LOAD = 'webview_load';
    const ACTIVITY_WEBVIEW_ERROR = 'webview_error';
    const ACTIVITY_SECURITY_VIOLATION = 'security_violation';
    const ACTIVITY_NETWORK_CHANGE = 'network_change';
    const ACTIVITY_PHOTO_CAPTURE = 'photo_capture';
    const ACTIVITY_ADMIN_ACCESS = 'admin_access';

    /**
     * Severity constants
     */
    const SEVERITY_INFO = 'info';
    const SEVERITY_WARNING = 'warning';
    const SEVERITY_ERROR = 'error';
    const SEVERITY_CRITICAL = 'critical';
}