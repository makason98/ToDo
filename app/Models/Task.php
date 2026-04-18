<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'position',
        'recurrence',
        'recurrence_interval',
        'completed',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('position');
    }

    public function isRecurring(): bool
    {
        return $this->recurrence !== 'none' && $this->recurrence !== null;
    }

    public function getNextDueDate(): ?\Carbon\Carbon
    {
        if (!$this->due_date || !$this->isRecurring()) {
            return null;
        }

        return match ($this->recurrence) {
            'daily' => $this->due_date->copy()->addDay(),
            'weekly' => $this->due_date->copy()->addWeek(),
            'monthly' => $this->due_date->copy()->addMonth(),
            'custom' => $this->due_date->copy()->addDays($this->recurrence_interval ?? 1),
            default => null,
        };
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }
}
