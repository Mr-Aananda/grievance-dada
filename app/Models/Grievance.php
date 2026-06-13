<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Grievance extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'ticket_number',
        'category_id',
        'department_id',
        'employee_id',
        'description',
        'status',
        'admin_remarks',
        'resolved_at',
        'user_id',
        'updated_id',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_id');
    }

    // ── Media Collections (D Drive via grievance_media disk) ───
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('grievance_images')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'image/webp',
                'image/bmp',
            ])
            ->useDisk('grievance_media');

        $this->addMediaCollection('grievance_documents')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain',
                'text/csv',
            ])
            ->useDisk('grievance_media');

        $this->addMediaCollection('grievance_videos')
            ->acceptsMimeTypes([
                'video/mp4',
                'video/avi',
                'video/mov',
                'video/wmv',
                'video/mkv',
                'video/webm',
            ])
            ->useDisk('grievance_media');
    }

    // ── Accessors ──────────────────────────────────────────────
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'in_resolution' => 'In Resolution',
            'resolved' => 'Resolved',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'primary',
            'under_review' => 'warning',
            'in_resolution' => 'danger',
            'resolved' => 'success',
            default => 'secondary',
        };
    }

    /**
     * KEY FIX: Use route-based URLs instead of getUrl().
     *
     * getUrl() returns a path based on the disk's `url` config,
     * which doesn't exist for a local D-drive disk — so the browser
     * gets a broken or missing URL.
     *
     * Instead we route every media file through GrievanceMediaController
     * which reads the file from disk and streams it properly.
     */
    public function getAllMediaDataAttribute(): array
    {
        $files = [];

        foreach ($this->getMedia('grievance_images') as $media) {
            $files[] = [
                'id' => $media->id,
                'type' => 'image',
                'file_name' => $media->file_name,
                'original_name' => $media->name,
                'size' => $this->formatBytes($media->size),
                'mime_type' => $media->mime_type,
                // Route through our streaming controller
                'url' => route('grievance.media.stream', $media->id),
            ];
        }

        foreach ($this->getMedia('grievance_documents') as $media) {
            $files[] = [
                'id' => $media->id,
                'type' => 'document',
                'file_name' => $media->file_name,
                'original_name' => $media->name,
                'size' => $this->formatBytes($media->size),
                'mime_type' => $media->mime_type,
                // Documents → controller sends Content-Disposition: attachment
                'url' => route('grievance.media.stream', $media->id),
            ];
        }

        foreach ($this->getMedia('grievance_videos') as $media) {
            $files[] = [
                'id' => $media->id,
                'type' => 'video',
                'file_name' => $media->file_name,
                'original_name' => $media->name,
                'size' => $this->formatBytes($media->size),
                'mime_type' => $media->mime_type,
                // Route through our streaming controller
                'url' => route('grievance.media.stream', $media->id),
            ];
        }

        return $files;
    }

    // ── Ticket Generator ───────────────────────────────────────
    public static function generateTicketNumber(): string
    {
        $year = now()->format('Y');
        $count = self::withTrashed()->whereYear('created_at', $year)->count() + 1;
        return 'GRV-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    // ── Model Events ───────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function ($grievance) {
            if (auth()->check()) {
                $grievance->user_id = auth()->id();
            }
        });

        static::updating(function ($grievance) {
            if (auth()->check()) {
                $grievance->updated_id = auth()->id();
            }
        });
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes === 0)
            return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = min(floor(log($bytes, 1024)), count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}
