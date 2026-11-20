<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'avatar',
        'is_active',
        'last_login_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function assignedLeads(): HasMany
    {
        return $this->hasMany(CustomerLead::class, 'assigned_to');
    }

    public function assignedDealerApplications(): HasMany
    {
        return $this->hasMany(DealerApplication::class, 'assigned_to');
    }

    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function hasPermission(string $permissionSlug): bool
    {
        if (! $this->is_active || ! $this->role) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->role->permissions()->where('slug', $permissionSlug)->exists();
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if (! $this->is_active || ! $this->role) {
            return false;
        }

        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->role->permissions()->whereIn('slug', $permissions)->exists();
    }
}
