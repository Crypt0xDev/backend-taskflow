<?php

namespace App\Models;

use App\Modules\Task\Task;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['user_name', 'email', 'password', 'birth_date', 'avatar', 'must_change_password'];
    protected $hidden = ['password'];
    protected $casts = [
        'password' => 'hashed',
        'birth_date' => 'date',
        'must_change_password' => 'boolean',
    ];

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? (int) $this->birth_date->age : null;
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
