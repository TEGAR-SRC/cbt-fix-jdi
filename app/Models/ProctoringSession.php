<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProctoringSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'participant_id',
        'device_id',
        'device_model',
        'android_version',
        'app_version',
        'started_at',
        'ended_at',
        'status',
        'violation_count',
        'photo_count',
        'device_info',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'device_info' => 'json',
    ];

    /**
     * Get all activity logs for this session
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ProctoringActivityLog::class);
    }

    /**
     * Get all photos for this session
     */
    public function photos(): HasMany
    {
        return $this->hasMany(ProctoringPhoto::class);
    }

    /**
     * Get all violations for this session
     */
    public function violations(): HasMany
    {
        return $this->hasMany(ProctoringViolation::class);
    }

    /**
     * Get all network logs for this session
     */
    public function networkLogs(): HasMany
    {
        return $this->hasMany(ProctoringNetworkLog::class);
    }

    /**
     * Scope for active sessions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for sessions by participant
     */
    public function scopeByParticipant($query, $participantId)
    {
        return $query->where('participant_id', $participantId);
    }

    /**
     * Check if session has violations
     */
    public function hasViolations(): bool
    {
        return $this->violation_count > 0;
    }

    /**
     * Increment violation count
     */
    public function incrementViolationCount(): void
    {
        $this->increment('violation_count');
    }

    /**
     * Increment photo count
     */
    public function incrementPhotoCount(): void
    {
        $this->increment('photo_count');
    }

    /**
     * End the proctoring session
     */
    public function endSession(string $status = 'completed'): void
    {
        $this->update([
            'ended_at' => now(),
            'status' => $status,
        ]);
    }
}