<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProctoringPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'proctoring_session_id',
        'photo_filename',
        'photo_path',
        'photo_url',
        'file_size',
        'mime_type',
        'captured_at',
        'camera_metadata',
        'notes',
        'is_flagged',
        'flag_reason',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
        'camera_metadata' => 'json',
        'is_flagged' => 'boolean',
    ];

    /**
     * Get the proctoring session that owns this photo
     */
    public function proctoringSession(): BelongsTo
    {
        return $this->belongsTo(ProctoringSession::class);
    }

    /**
     * Get the full URL of the photo
     */
    public function getFullUrlAttribute(): string
    {
        if ($this->photo_url) {
            return $this->photo_url;
        }

        return Storage::url($this->photo_path);
    }

    /**
     * Get human readable file size
     */
    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Scope for flagged photos
     */
    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    /**
     * Scope by session
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('proctoring_session_id', $sessionId);
    }

    /**
     * Flag this photo with a reason
     */
    public function flagPhoto(string $reason): void
    {
        $this->update([
            'is_flagged' => true,
            'flag_reason' => $reason,
        ]);
    }

    /**
     * Unflag this photo
     */
    public function unflagPhoto(): void
    {
        $this->update([
            'is_flagged' => false,
            'flag_reason' => null,
        ]);
    }

    /**
     * Delete photo file from storage
     */
    public function deletePhotoFile(): bool
    {
        if (Storage::exists($this->photo_path)) {
            return Storage::delete($this->photo_path);
        }
        
        return true;
    }
}