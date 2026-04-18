<?php if (isset($component)) { $__componentOriginal91fdd17964e43374ae18c674f95cdaa3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91fdd17964e43374ae18c674f95cdaa3 = $attributes; } ?>
<?php $component = App\View\Components\AdminLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AdminLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> Trash <?php $__env->endSlot(); ?>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            <?php echo $__env->make('admin.partials.sidebar', ['currentPage' => 'trash'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Trash</h1>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Trash</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Soft-deleted content from across the platform. Restore to bring back, or permanently delete.</p>
                </div>

                <?php if(session('status')): ?>
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg"><?php echo e(session('status')); ?></div>
                <?php endif; ?>

                <!-- Tabs -->
                <div class="flex items-center gap-1 mb-4 border-b border-gray-200 dark:border-gray-700">
                    <a href="<?php echo e(route('admin.trash.index', ['type' => 'tasks'])); ?>"
                        class="px-4 py-2 text-sm font-medium border-b-2 -mb-px <?php echo e($type === 'tasks' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'); ?>">
                        Tasks <span class="ml-1 text-xs px-1.5 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full"><?php echo e($counts['tasks']); ?></span>
                    </a>
                    <a href="<?php echo e(route('admin.trash.index', ['type' => 'comments'])); ?>"
                        class="px-4 py-2 text-sm font-medium border-b-2 -mb-px <?php echo e($type === 'comments' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'); ?>">
                        Comments <span class="ml-1 text-xs px-1.5 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full"><?php echo e($counts['comments']); ?></span>
                    </a>
                </div>

                <!-- Empty trash button -->
                <?php if(($type === 'tasks' && $counts['tasks'] > 0) || ($type === 'comments' && $counts['comments'] > 0)): ?>
                    <form method="POST" action="<?php echo e(route('admin.trash.empty')); ?>" class="mb-4"
                        onsubmit="return confirm('Permanently delete ALL trashed <?php echo e($type); ?>? This cannot be undone.')">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="type" value="<?php echo e($type); ?>">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a2 2 0 012-2h2a2 2 0 012 2v3"/></svg>
                            Empty <?php echo e($type); ?> trash
                        </button>
                    </form>
                <?php endif; ?>

                <?php if($type === 'tasks'): ?>
                    <!-- Tasks tab -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="text-left px-4 py-3 font-semibold">Title</th>
                                        <th class="text-center px-4 py-3 font-semibold">Owner</th>
                                        <th class="text-center px-4 py-3 font-semibold">Deleted</th>
                                        <th class="text-center px-4 py-3 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100"><?php echo e($task->title); ?></td>
                                            <td class="px-4 py-3 text-center">
                                                <?php if($task->user): ?>
                                                    <a href="<?php echo e(route('admin.users.show', $task->user)); ?>" class="text-red-500 hover:text-red-600"><?php echo e($task->user->name); ?></a>
                                                <?php else: ?>
                                                    <span class="text-gray-400">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap" title="<?php echo e($task->deleted_at); ?>"><?php echo e($task->deleted_at->diffForHumans()); ?></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-center gap-2">
                                                    <form method="POST" action="<?php echo e(route('admin.trash.tasks.restore', $task->id)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-sm transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                            Restore
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="<?php echo e(route('admin.trash.tasks.purge', $task->id)); ?>"
                                                        onsubmit="return confirm('Permanently delete this task? Cannot be undone.')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a2 2 0 012-2h2a2 2 0 012 2v3"/></svg>
                                                            Purge
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="4" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Trash is empty.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if($tasks->hasPages()): ?>
                        <div class="mt-4"><?php echo e($tasks->links()); ?></div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Comments tab -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="text-left px-4 py-3 font-semibold">Body</th>
                                        <th class="text-center px-4 py-3 font-semibold">Author</th>
                                        <th class="text-center px-4 py-3 font-semibold">Task</th>
                                        <th class="text-center px-4 py-3 font-semibold">Deleted</th>
                                        <th class="text-center px-4 py-3 font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    <?php $__empty_1 = true; $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                            <td class="px-4 py-3 max-w-md">
                                                <span class="text-gray-700 dark:text-gray-300 line-clamp-2"><?php echo e($comment->body); ?></span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <?php if($comment->user): ?>
                                                    <a href="<?php echo e(route('admin.users.show', $comment->user)); ?>" class="text-red-500 hover:text-red-600"><?php echo e($comment->user->name); ?></a>
                                                <?php else: ?>
                                                    <span class="text-gray-400">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <?php if($comment->task): ?>
                                                    <a href="<?php echo e(route('admin.tasks.show', $comment->task->id)); ?>" class="text-red-500 hover:text-red-600 truncate inline-block max-w-xs"><?php echo e($comment->task->title); ?></a>
                                                <?php else: ?>
                                                    <span class="text-gray-400">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap" title="<?php echo e($comment->deleted_at); ?>"><?php echo e($comment->deleted_at->diffForHumans()); ?></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-center gap-2">
                                                    <form method="POST" action="<?php echo e(route('admin.trash.comments.restore', $comment->id)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-sm transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                            Restore
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="<?php echo e(route('admin.trash.comments.purge', $comment->id)); ?>"
                                                        onsubmit="return confirm('Permanently delete this comment? Cannot be undone.')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a2 2 0 012-2h2a2 2 0 012 2v3"/></svg>
                                                            Purge
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">Trash is empty.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if($comments->hasPages()): ?>
                        <div class="mt-4"><?php echo e($comments->links()); ?></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91fdd17964e43374ae18c674f95cdaa3)): ?>
<?php $attributes = $__attributesOriginal91fdd17964e43374ae18c674f95cdaa3; ?>
<?php unset($__attributesOriginal91fdd17964e43374ae18c674f95cdaa3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91fdd17964e43374ae18c674f95cdaa3)): ?>
<?php $component = $__componentOriginal91fdd17964e43374ae18c674f95cdaa3; ?>
<?php unset($__componentOriginal91fdd17964e43374ae18c674f95cdaa3); ?>
<?php endif; ?>
<?php /**PATH /home/tehnic/laravel/todo/resources/views/admin/trash/index.blade.php ENDPATH**/ ?>