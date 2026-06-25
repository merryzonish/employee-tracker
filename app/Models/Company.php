<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'legal_name',
        'registration_no',
        'tax_no',
        'country_id',
        'timezone',
        'currency',
        'language',
        'email',
        'phone',
        'website',
        'address',
        'postal_code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the country that owns this company.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get all branches belonging to this company.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get all departments belonging to this company.
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get all designations belonging to this company.
     */
    public function designations(): HasMany
    {
        return $this->hasMany(Designation::class);
    }

    /**
     * Get all users belonging to this company.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}