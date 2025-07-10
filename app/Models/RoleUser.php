<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Platform\Models\Role;
use Orchid\Platform\Models\User;

class RoleUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_users';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role_id',
    ];

    /**
     * Get the user that owns the role assignment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role that is assigned to the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Scope to get role assignments for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get role assignments for a specific role.
     */
    public function scopeForRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /**
     * Check if a user has a specific role.
     */
    public static function userHasRole($userId, $roleId): bool
    {
        return static::where('user_id', $userId)
            ->where('role_id', $roleId)
            ->exists();
    }

    /**
     * Get all users with a specific role.
     */
    public static function getUsersWithRole($roleId)
    {
        return static::where('role_id', $roleId)
            ->with('user')
            ->get()
            ->pluck('user');
    }

    /**
     * Get all roles for a specific user.
     */
    public static function getRolesForUser($userId)
    {
        return static::where('user_id', $userId)
            ->with('role')
            ->get()
            ->pluck('role');
    }
} 