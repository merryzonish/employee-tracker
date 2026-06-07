<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'app_name',
        'window_title',
        'clicks',
        'keystrokes',
        'is_idle',
        'tracked_at',
    ];

    protected function casts(): array
    {
        return [
            'is_idle'    => 'boolean',
            'tracked_at' => 'datetime',
            'clicks'     => 'integer',
            'keystrokes' => 'integer',
        ];
    }

    /**
     * Get the user that owns this activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}