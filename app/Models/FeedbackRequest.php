<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackRequest extends Model
{
    public const TYPES = [
        'bug' => 'Bug report',
        'improvement' => 'Improvement',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'open' => 'Open',
        'in_progress' => 'In progress',
        'done' => 'Done',
        'rejected' => 'Rejected',
    ];

    public const UNRESOLVED_STATUSES = ['open', 'in_progress'];

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'status',
        'resolution_note',
        'resolved_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function isResolved(): bool
    {
        return in_array($this->status, ['done', 'rejected'], true);
    }
}
