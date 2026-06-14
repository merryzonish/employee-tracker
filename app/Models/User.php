<?php

namespace App\Models;

use App\Models\UserActivity;
use App\Models\UserBankAccount;
use App\Models\UserScreenshot;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserContact;
use App\Models\Media;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'can_user_logout'   => 'boolean',
            'allowed_ips'       => 'array',
        ];
    }

    /**
     * Get all screenshots belonging to this user.
     */
    public function screenshots(): HasMany
    {
        return $this->hasMany(UserScreenshot::class);
    }

    /**
     * Get all activities belonging to this user.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(UserActivity::class);
    }

    /**
     * Get all bank accounts belonging to this user.
     */
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(UserBankAccount::class);
    }

    /**
 * Get all contacts belonging to this user.
 */
public function contacts(): HasMany
{
    return $this->hasMany(UserContact::class);
}
/**
 * Get all media belonging to this user.
 */
public function media(): HasMany
{
    return $this->hasMany(Media::class);
}



}