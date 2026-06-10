<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'code',
        'email',
        'phone',
        'country',
        'address',
        'note',
        'status',
        'user_id',
        'updated_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Custom Accessor
    protected $appends = ['custom_name'];

    /**
     * Custom name for dropdown/select with professional format
     * Format: Company Name | Code | Country
     *
     * @return string
     */
    public function getCustomNameAttribute()
    {
        $parts = [];

        // Company name
        if (!empty(trim($this->company_name))) {
            $parts[] = trim($this->company_name);
        }

        // Code
        // if (!empty(trim($this->code))) {
        //     $parts[] = trim($this->code);
        // }

        // Country
        if (!empty(trim($this->country))) {
            $parts[] = trim($this->country);
        }

        // If all data exists
        if (count($parts) >= 3) {
            return $parts[0] . ' | ' . $parts[1] . ' | ' . $parts[2];
        }

        // If only company and contact
        if (count($parts) === 2) {
            return $parts[0] . ' | ' . $parts[1];
        }

        // If only one part exists
        if (count($parts) === 1) {
            return $parts[0];
        }

        // Fallback
        return 'Buyer #' . $this->id;
    }

    // Relationship with creator user
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with updater user
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_id');
    }

    // Scope for active buyers
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Scope for inactive buyers
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    public function complains()
    {
        return $this->hasMany(Complain::class);
    }
}
