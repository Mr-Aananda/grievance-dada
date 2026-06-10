<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Complain extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'type',
        'complain_type_id',
        'category_id',
        'subject',
        'buyer_id',
        'manual_category',
        'date',
        'name',
        'ps',
        'po',
        'cap',
        'style_order',
        'quantity',
        'amount',
        'line_floor',
        'complain',
        'note',
        'status',
        'status_note',
        'user_id',
        'updated_id',
        'video_count',
        'has_videos'
    ];

    protected $appends = [
        'complain_type_name',
        'category_name',
        'status_name',
        'files_data',
        'videos_data',
        'videos_count',
        'total_amount',
        'formatted_date',
        'short_complain',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
        'amount' => 'decimal:2',
        'has_videos' => 'boolean',
    ];

    // Relationships
    public function complainType(): BelongsTo
    {
        return $this->belongsTo(ComplainType::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->date ? \Carbon\Carbon::parse($this->date)->format('d M, Y') : '--';
    }

    public function getShortComplainAttribute()
    {
        return Str::limit(strip_tags($this->complain ?? ''), 50);
    }

    public function getComplainTypeNameAttribute(): string
    {
        if ($this->relationLoaded('complainType') && $this->complainType) {
            return $this->complainType->name;
        }

        return $this->complain_type_id ? "Type #{$this->complain_type_id}" : 'N/A';
    }

    public function getCategoryNameAttribute(): string
    {
        if ($this->relationLoaded('category') && $this->category) {
            return $this->category->name;
        }

        return $this->category_id ? "Category #{$this->category_id}" : 'N/A';
    }

    public function getStatusNameAttribute(): string
    {
        $statuses = config('complains.statuses', []);
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getFilesDataAttribute(): array
    {
        $files = [];

        // Get images
        foreach ($this->getMedia('images') as $media) {
            $files[] = [
                'id' => $media->id,
                'url' => route('complain.image.stream', ['complain' => $this->id, 'image' => $media->id]),
                'download_url' => route('complain.file.download', ['complain' => $this->id, 'file' => $media->id]),
                'file_name' => $media->file_name,
                'original_name' => $media->name,
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'is_image' => true,
                'formatted_size' => $this->formatBytes($media->size),
                'created_at' => $media->created_at->format('Y-m-d H:i:s'),
            ];
        }

        // Get documents
        foreach ($this->getMedia('documents') as $media) {
            $files[] = [
                'id' => $media->id,
                'url' => null,
                'download_url' => route('complain.file.download', ['complain' => $this->id, 'file' => $media->id]),
                'file_name' => $media->file_name,
                'original_name' => $media->name,
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'is_image' => false,
                'formatted_size' => $this->formatBytes($media->size),
                'created_at' => $media->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $files;
    }

    public function getVideosDataAttribute(): array
    {
        $videos = [];

        foreach ($this->getMedia('videos') as $media) {
            $videos[] = [
                'id' => $media->id,
                'url' => route('complain.videos.stream', ['complain' => $this->id, 'video' => $media->id]),
                'download_url' => route('complain.file.download', ['complain' => $this->id, 'file' => $media->id]),
                'file_name' => $media->file_name,
                'original_name' => $media->name,
                'size' => $media->size,
                'mime_type' => $media->mime_type,
                'formatted_size' => $this->formatBytes($media->size),
                'created_at' => $media->created_at->format('Y-m-d H:i:s'),
            ];
        }

        return $videos;
    }


    public function getVideosCountAttribute(): int
    {
        return $this->getMedia('videos')->count();
    }

    public function getTotalAmountAttribute(): float
    {
        $qty = (float) ($this->quantity ?? 0);
        $amt = (float) ($this->amount ?? 0);
        return round($qty * $amt, 2);
    }

    // Register media collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'image/webp',
                'image/bmp'
            ])
            ->useDisk('qms_media');

        $this->addMediaCollection('documents')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain',
                'text/csv',
                'application/json',
                'application/zip',
                'application/x-rar-compressed',
                'application/x-7z-compressed',
                'application/vnd.ms-outlook',
                'application/octet-stream',
            ])
            ->useDisk('qms_media');

        $this->addMediaCollection('videos')
            ->acceptsMimeTypes([
                'video/mp4',
                'video/avi',
                'video/mov',
                'video/wmv',
                'video/mkv',
                'video/webm',
                'video/flv',
                'video/3gp',
                'video/mpeg'
            ])
            ->useDisk('qms_media');
    }


    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeComplains($query)
    {
        return $query->where('type', 'complain');
    }

    public function scopeManuals($query)
    {
        return $query->where('type', 'manual');
    }

    public function isComplain()
    {
        return $this->type === 'complain';
    }

    public function isManual()
    {
        return $this->type === 'manual';
    }

    // Helper method for formatting bytes
    private function formatBytes($bytes, $precision = 2): string
    {
        if ($bytes == 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    // Model events
    protected static function booted()
    {
        static::creating(function ($complain) {
            if (auth()->check()) {
                $complain->user_id = auth()->id();
            }
        });

        static::updating(function ($complain) {
            if (auth()->check()) {
                $complain->updated_id = auth()->id();
            }
        });
    }
}
