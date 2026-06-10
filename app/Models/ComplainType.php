<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplainType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function complains()
    {
        return $this->hasMany(Complain::class);
    }
}
