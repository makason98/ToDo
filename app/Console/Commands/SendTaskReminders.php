<?php

namespace App\Console\Commands;

use App\Mail\TaskReminderMail;
use App\Models\InAppNotification;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';
    protected $description = 'Send email reminders for tasks due today (alert) and tomorrow (reminder)';

    public function handle(): int
    {
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        // Tasks due TODAY → urgent alert
        $todayTasks = Task::with('user')
            ->where('completed', false)
            ->whereDate('due_date', $today)
            ->get();

        foreach ($todayTasks as $task) {
            Mail::to($task->user->email)->send(new TaskReminderMail($task, 'alert'));
            InAppNotification::send($task->user_id, 'overdue', "Task due today: {$task->title}", "Don't forget to complete this task!", route('tasks.show', $task));
            $this->info("Alert sent to {$task->user->email} for task: {$task->title} (due today)");
        }

        // Tasks due TOMORROW → friendly reminder
        $tomorrowTasks = Task::with('user')
            ->where('completed', false)
            ->whereDate('due_date', $tomorrow)
            ->get();

        foreach ($tomorrowTasks as $task) {
            Mail::to($task->user->email)->send(new TaskReminderMail($task, 'reminder'));
            InAppNotification::send($task->user_id, 'reminder', "Task due tomorrow: {$task->title}", "Remember to plan for this task.", route('tasks.show', $task));
            $this->info("Reminder sent to {$task->user->email} for task: {$task->title} (due tomorrow)");
        }

        $total = $todayTasks->count() + $tomorrowTasks->count();

        if ($total === 0) {
            $this->info('No reminders to send.');
        } else {
            $this->info("Done! Sent {$todayTasks->count()} alert(s) and {$tomorrowTasks->count()} reminder(s).");
        }

        return Command::SUCCESS;
    }
}
