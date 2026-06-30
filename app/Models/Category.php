<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function grievances()
    {
        return $this->hasMany(Grievance::class);
    }
}
