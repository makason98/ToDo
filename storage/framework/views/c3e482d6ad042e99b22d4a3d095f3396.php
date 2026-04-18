<?php
    $activeBadge = $activeBadge ?? auth()->user()->tasks()->where('completed', false)->count();
    $completedBadge = $completedBadge ?? auth()->user()->tasks()->where('completed', true)->count();
    $labels = $labels ?? auth()->user()->labels()->orderBy('name')->get();
    $currentPage = $currentPage ?? '';
?>

<div class="p-4">
    <!-- Add task button -->
    <button @click="if(typeof showAddTask !== 'undefined') { showAddTask = true; sidebarOpen = false; } else { window.location = '<?php echo e(route('tasks.index', ['add' => 1])); ?>'; }"
        class="flex items-center gap-2 w-full text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg px-3 py-2 text-sm font-medium transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add task
    </button>

    <!-- Search -->
    <form action="<?php echo e(route('tasks.index')); ?>" method="GET" class="mt-3">
        <div class="relative">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search..."
                class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400" />
        </div>
    </form>

    <!-- Navigation -->
    <nav class="mt-6 space-y-1">
        <a href="<?php echo e(route('dashboard')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'dashboard' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                </svg>
                Dashboard
            </span>
        </a>
        <a href="<?php echo e(route('tasks.index', ['filter' => 'active'])); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'tasks' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Tasks
            </span>
            <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full px-2 py-0.5"><?php echo e($activeBadge); ?></span>
        </a>
        <a href="<?php echo e(route('tasks.index', ['filter' => 'completed'])); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'completed' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Completed
            </span>
            <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full px-2 py-0.5"><?php echo e($completedBadge); ?></span>
        </a>
        <a href="<?php echo e(route('tasks.kanban')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'kanban' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                </svg>
                Board
            </span>
        </a>
        <a href="<?php echo e(route('tasks.calendar')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'calendar' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Calendar
            </span>
        </a>
        <a href="<?php echo e(route('activity.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'activity' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Activity Log
            </span>
        </a>
        <a href="<?php echo e(route('feedback.index')); ?>"
           class="flex items-center justify-between px-3 py-2 text-sm rounded-lg <?php echo e($currentPage === 'feedback' ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Feedback
            </span>
        </a>
    </nav>

    <!-- Labels -->
    <?php if($labels->count() > 0): ?>
        <div class="mt-6">
            <div class="flex items-center justify-between px-3 mb-2">
                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Labels</span>
            </div>
            <div class="space-y-1">
                <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('tasks.index', ['filter' => request('filter', 'active'), 'label' => $label->id])); ?>"
                       class="flex items-center gap-2 px-3 py-1.5 text-sm rounded-lg <?php echo e(request('label') == $label->id ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
                        <span class="w-3 h-3 rounded-full shrink-0" style="background-color: <?php echo e($label->color); ?>"></span>
                        <?php echo e($label->name); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(request('label')): ?>
                    <a href="<?php echo e(route('tasks.index', ['filter' => request('filter', 'active')])); ?>"
                       class="flex items-center gap-2 px-3 py-1.5 text-xs text-gray-400 dark:text-gray-500 hover:text-red-500">
                        Clear filter
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Manage Labels -->
    <div class="mt-4" x-data="{ showLabelForm: false }">
        <button @click="showLabelForm = !showLabelForm"
            class="flex items-center gap-2 px-3 py-1.5 text-xs text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Manage labels
        </button>
        <div x-show="showLabelForm" x-transition class="mt-2 px-3">
            <form method="POST" action="<?php echo e(route('labels.store')); ?>" class="flex items-center gap-2">
                <?php echo csrf_field(); ?>
                <input type="color" name="color" value="#ef4444" class="w-7 h-7 rounded cursor-pointer border-0 p-0">
                <input type="text" name="name" placeholder="Label name" required
                    class="flex-1 min-w-0 text-xs border border-gray-200 dark:border-gray-600 rounded-md px-2 py-1 bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-red-500 focus:border-red-500" />
                <button type="submit" class="text-xs bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 shrink-0">Add</button>
            </form>
            <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-3 h-3 rounded-full shrink-0" style="background-color: <?php echo e($label->color); ?>"></span>
                    <span class="flex-1 text-xs text-gray-700 dark:text-gray-300"><?php echo e($label->name); ?></span>
                    <form method="POST" action="<?php echo e(route('labels.destroy', $label)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-gray-300 dark:text-gray-600 hover:text-red-500 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </form>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH /home/tehnic/laravel/todo/resources/views/tasks/partials/sidebar.blade.php ENDPATH**/ ?>