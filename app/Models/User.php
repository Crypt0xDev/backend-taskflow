<?php

namespace App\Models;

use App\Modules\Access\Role\Role;
use App\Modules\Task\Task;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['user_name', 'email', 'password', 'birth_date', 'avatar', 'must_change_password', 'role_id'];
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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function hasPermission(string $module, string $action): bool
    {
        if (! $this->relationLoaded('role') || ! $this->role?->relationLoaded('permissions')) {
            $this->loadMissing('role.permissions');
        }

        return $this->role?->permissions->contains(
            fn ($permission) => $permission->module === $module && $permission->name === $action
        ) ?? false;
    }

    public static function isLastAdmin(User $model): bool
    {
        return $model->isAdmin()
            && static::whereHas('role', fn ($query) => $query->where('name', 'admin'))->count() <= 1;
    }
}
