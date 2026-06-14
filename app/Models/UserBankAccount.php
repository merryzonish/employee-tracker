<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_holder_name',
        'bank_name',
        'branch_name',
        'account_number',
        'iban',
        'routing_number',
        'sort_code',
        'transit_number',
        'swift_code',
        'bic_code',
        'bank_address',
        'bank_city',
        'bank_state',
        'bank_country',
        'bank_postal_code',
        'currency',
        'is_primary',
        'is_active',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'is_primary'  => 'boolean',
            'is_active'   => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns this bank account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}