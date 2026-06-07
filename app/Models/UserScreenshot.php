<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserScreenshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'screenshot_time',
    ];

    /**
     * Get the user that owns this screenshot.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}