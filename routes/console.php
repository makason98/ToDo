<?php

use Illuminate\Support\Facades\Schedule;

// Send task reminders every day at 8:00 AM
Schedule::command('tasks:send-reminders')->dailyAt('08:00');
