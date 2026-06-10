<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    /**
     * Supported configuration keys.
     */
    public const KEYS = [
        'tracker_screenshot_interval',
        'tracker_api_url',
        'tracker_admin_password',
        'tracker_allowed_ips',
        'tracker_logout_restriction',
    ];

    /**
     * Get a config value by key.
     */
    public static function getValue(string $key): mixed
    {
        $config = static::where('key', $key)->first();
        return $config?->value ?? null;
    }
}