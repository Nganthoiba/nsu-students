<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Exception;

class User extends Authenticatable implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Notifiable, Authorizable, CanResetPassword, MustVerifyEmail, SoftDeletes;
    use HasFactory;

    protected $connection = 'mongodb'; // Specify MongoDB connection
    protected $collection = 'users'; // Optional, default is the plural form of the model name

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'email_verified_at',
        'institution_id',
        'role',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    /**
     * Check if the user is assigned all the specified roles.
     *
     * This method checks if the user has all the roles specified in the given array.
     * 
     * Usage:
     * ```php
     * $flag = $user->isRole(['admin', 'supervisor']);
     * ```
     * If the user has both 'admin' and 'supervisor' roles, the method will return `true`.
     * Otherwise, it will return `false`.
     *
     * @param array $roleNames An array of role names to check.
     * @return bool Returns `true` if the user has all the specified roles, otherwise `false`.
     * @throws InvalidArgumentException If the $roleNames array is empty.
     */
    public function isRole(array $roleNames): bool
    {
        // Ensure the user has a role assigned
        if (empty($this->role)) {
            return false;
        }

        // Validate that the roleNames array is not empty
        if (empty($roleNames)) {
            throw new \InvalidArgumentException("The roleNames array cannot be empty.");
        }

        // Check if all specified roles exist in the user's roles
        return empty(array_diff($roleNames, $this->role));
    } 

}