<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'label',
        'value',
        'is_primary',
        'is_verified',
        'verified_at',
        'notes',
    ];

    /**
     * Supported contact types.
     */
    public const TYPES = [
        // Communication
        'email',
        'personal_email',
        'work_email',
        'phone',
        'mobile',
        'whatsapp',
        'telegram',
        'skype',
        'linkedin',
        'website',

        // Address
        'permanent_address',
        'current_address',
        'billing_address',
        'mailing_address',
        'office_address',

        // Emergency
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_email',
        'emergency_contact_relationship',

        // Government
        'tax_address',
        'registered_address',
    ];

    protected function casts(): array
    {
        return [
            'is_primary'  => 'boolean',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns this contact.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}