<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'emp_id',
        'email',
        'phone',
        'designation',
        'department_id',
        'section_id',
        'user_id',
        'status',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    /**
     * Find user for authentication by email or emp_id.
     */
    public function findForPassport($username)
    {
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'emp_id';
        return $this->where($field, $username)->first();
    }

    /**
     * Relationship: Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship: Section
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Relationship: Creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope: Active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
