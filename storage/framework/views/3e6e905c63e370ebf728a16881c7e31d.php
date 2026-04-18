<?php
    $currentPage = $currentPage ?? '';
?>

<div class="p-4">
    <div class="px-3 mb-4">
        <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Admin panel</span>
    </div>

    <nav class="space-y-1">
        <a href="<?php echo e(route('admin.dashboard')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'dashboard' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                </svg>
                Dashboard
            </span>
        </a>
        <a href="<?php echo e(route('admin.users.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'users' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Users
            </span>
        </a>
        <a href="<?php echo e(route('admin.tasks.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'tasks' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Tasks
            </span>
        </a>
        <a href="<?php echo e(route('admin.comments.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'comments' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Comments
            </span>
        </a>
        <a href="<?php echo e(route('admin.activity.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'activity' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Activity Log
            </span>
        </a>
        <a href="<?php echo e(route('admin.announcements.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'announcements' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Announcements
            </span>
        </a>
        <?php
            $unresolvedFeedback = \App\Models\FeedbackRequest::whereIn('status', \App\Models\FeedbackRequest::UNRESOLVED_STATUSES)->count();
            $trashedTotal = \App\Models\Task::onlyTrashed()->count() + \App\Models\Comment::onlyTrashed()->count();
        ?>
        <a href="<?php echo e(route('admin.feedback.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'feedback' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                Feedback
            </span>
            <?php if($unresolvedFeedback > 0): ?>
                <span class="text-xs bg-red-500 text-white rounded-full px-2 py-0.5 font-medium"><?php echo e($unresolvedFeedback > 99 ? '99+' : $unresolvedFeedback); ?></span>
            <?php endif; ?>
        </a>
        <a href="<?php echo e(route('admin.reports.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'reports' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Reports
            </span>
        </a>
        <a href="<?php echo e(route('admin.audit.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'audit' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Audit
            </span>
        </a>
        <a href="<?php echo e(route('admin.trash.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'trash' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a2 2 0 012-2h2a2 2 0 012 2v3"/>
                </svg>
                Trash
            </span>
            <?php if($trashedTotal > 0): ?>
                <span class="text-xs bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-full px-2 py-0.5 font-medium"><?php echo e($trashedTotal > 99 ? '99+' : $trashedTotal); ?></span>
            <?php endif; ?>
        </a>
        <?php if(auth()->user()->isRoot()): ?>
            <a href="<?php echo e(route('admin.settings.edit')); ?>"
               class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'settings' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </span>
            </a>
        <?php endif; ?>
    </nav>
</div>
<?php /**PATH /home/tehnic/laravel/todo/resources/views/admin/partials/sidebar.blade.php ENDPATH**/ ?>