<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\TrashController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::middleware(['admin'])->group(function () {
        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');

        Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::post('tasks/{task}/restore', [TaskController::class, 'restore'])->name('tasks.restore');

        Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
        Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
        Route::post('comments/{comment}/restore', [CommentController::class, 'restore'])->name('comments.restore');

        Route::get('activity', [ActivityController::class, 'index'])->name('activity.index');

        Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('announcements', [AnnouncementController::class, 'store'])->name('announcements.store');

        Route::get('feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::get('feedback/{feedback}', [FeedbackController::class, 'show'])->name('feedback.show');
        Route::patch('feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('audit', [AuditController::class, 'index'])->name('audit.index');

        Route::get('trash', [TrashController::class, 'index'])->name('trash.index');
        Route::post('trash/empty', [TrashController::class, 'emptyTrash'])->name('trash.empty');
        Route::post('trash/tasks/{id}/restore', [TrashController::class, 'restoreTask'])->name('trash.tasks.restore');
        Route::delete('trash/tasks/{id}', [TrashController::class, 'purgeTask'])->name('trash.tasks.purge');
        Route::post('trash/comments/{id}/restore', [TrashController::class, 'restoreComment'])->name('trash.comments.restore');
        Route::delete('trash/comments/{id}', [TrashController::class, 'purgeComment'])->name('trash.comments.purge');

        Route::middleware('admin.root')->group(function () {
            Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
            Route::patch('settings', [SettingController::class, 'update'])->name('settings.update');
        });

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});
