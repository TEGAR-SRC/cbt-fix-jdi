<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProctoringNetworkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'proctoring_session_id',
        'connection_status',
        'network_type',
        'status_changed_at',
        'duration_seconds',
        'additional_info',
        'exam_paused',
    ];

    protected $casts = [
        'status_changed_at' => 'datetime',
        'exam_paused' => 'boolean',
    ];

    /**
     * Get the proctoring session that owns this log
     */
    public function proctoringSession(): BelongsTo
    {
        return $this->belongsTo(ProctoringSession::class);
    }

    /**
     * Scope for disconnected status
     */
    public function scopeDisconnected($query)
    {
        return $query->where('connection_status', 'disconnected');
    }

    /**
     * Scope for connected status
     */
    public function scopeConnected($query)
    {
        return $query->where('connection_status', 'connected');
    }

    /**
     * Scope for exam paused
     */
    public function scopeExamPaused($query)
    {
        return $query->where('exam_paused', true);
    }

    /**
     * Scope by network type
     */
    public function scopeByNetworkType($query, $type)
    {
        return $query->where('network_type', $type);
    }

    /**
     * Get human readable duration
     */
    public function getDurationHumanAttribute(): string
    {
        if (!$this->duration_seconds) {
            return 'Unknown';
        }

        $seconds = $this->duration_seconds;
        
        if ($seconds < 60) {
            return $seconds . ' detik';
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            return $minutes . ' menit ' . $remainingSeconds . ' detik';
        } else {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            return $hours . ' jam ' . $minutes . ' menit';
        }
    }

    /**
     * Connection status constants
     */
    const STATUS_CONNECTED = 'connected';
    const STATUS_DISCONNECTED = 'disconnected';
    const STATUS_POOR = 'poor';
    const STATUS_EXCELLENT = 'excellent';

    /**
     * Network type constants
     */
    const TYPE_WIFI = 'WiFi';
    const TYPE_MOBILE = 'Mobile';
    const TYPE_ETHERNET = 'Ethernet';
    const TYPE_UNKNOWN = 'Unknown';
}