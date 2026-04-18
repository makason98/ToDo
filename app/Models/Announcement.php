<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    public const AUDIENCES = [
        'all' => 'All users',
        'verified' => 'Verified users',
        'unverified' => 'Unverified users',
        'admins' => 'Admins (any role)',
        'regular' => 'Regular users (non-admins)',
    ];

    protected $fillable = [
        'sender_id',
        'title',
        'body',
        'audience',
        'recipients_count',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function audienceLabel(): string
    {
        return self::AUDIENCES[$this->audience] ?? $this->audience;
    }
}
