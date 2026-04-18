<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public const ADMIN_ROLES = [
        'root' => 'Root',
        'super_admin' => 'Super admin',
        'admin' => 'Admin',
        'moderator' => 'Moderator',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'admin_role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
        ];
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class);
    }

    public function isAdmin(): bool
    {
        return ! is_null($this->admin_role);
    }

    public function isRoot(): bool
    {
        return $this->admin_role === 'root';
    }

    public function adminRoleLabel(): ?string
    {
        return self::ADMIN_ROLES[$this->admin_role] ?? null;
    }

    public static function rankOf(?string $role): int
    {
        return match ($role) {
            'root' => 4,
            'super_admin' => 3,
            'admin' => 2,
            'moderator' => 1,
            default => 0,
        };
    }

    public function adminRoleRank(): int
    {
        return self::rankOf($this->admin_role);
    }

    public function canEditUser(User $target): bool
    {
        if (! $this->isAdmin()) {
            return false;
        }
        if ($target->id === $this->id) {
            return false;
        }
        return $target->adminRoleRank() < $this->adminRoleRank();
    }

    /**
     * Roles this user is allowed to assign to others (strictly lower than their own).
     *
     * @return array<string, string>
     */
    public function assignableRoles(): array
    {
        return array_filter(
            self::ADMIN_ROLES,
            fn ($key) => self::rankOf($key) < $this->adminRoleRank(),
            ARRAY_FILTER_USE_KEY
        );
    }
}
