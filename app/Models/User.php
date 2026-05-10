<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'farm_id', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isFarmer()
    {
        return $this->role === 'farmer';
    }

    public function isViewer()
    {
        return $this->role === 'viewer';
    }
}
