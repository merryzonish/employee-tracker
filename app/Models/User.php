<?php

namespace App\Models;

use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Media;
use App\Models\State;
use App\Models\UserActivity;
use App\Models\UserBankAccount;
use App\Models\UserContact;
use App\Models\UserScreenshot;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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

    /**
     * Get the country this user belongs to.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the state this user belongs to.
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the city this user belongs to.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the company this user belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}