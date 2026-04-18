<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Task $task,
        public string $urgency = 'reminder' // 'reminder' = tomorrow, 'alert' = today
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->urgency === 'alert'
            ? '⚠️ Task due TODAY: ' . $this->task->title
            : '📋 Reminder: Task due tomorrow - ' . $this->task->title;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.task-reminder');
    }
}
