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
     <?php $__env->slot('title', null, []); ?> <?php echo e($task->title); ?> <?php $__env->endSlot(); ?>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            <?php echo $__env->make('admin.partials.sidebar', ['currentPage' => 'tasks'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate"><?php echo e($task->title); ?></h1>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <a href="<?php echo e(route('admin.tasks.index')); ?>" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to tasks
                </a>

                <?php if(session('status')): ?>
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 break-words"><?php echo e($task->title); ?></h2>
                                <?php if($task->trashed()): ?>
                                    <span class="text-xs px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">Trashed</span>
                                <?php endif; ?>
                                <?php if($task->completed): ?>
                                    <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full">Completed</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full">Active</span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Owned by <a href="<?php echo e(route('admin.users.show', $task->user)); ?>" class="text-red-500 hover:text-red-600"><?php echo e($task->user->name); ?></a>
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <?php if($task->trashed()): ?>
                                <form method="POST" action="<?php echo e(route('admin.tasks.restore', $task->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg shadow-sm">Restore</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('admin.tasks.destroy', $task->id)); ?>" onsubmit="return confirm('Soft-delete this task? It can be restored later.')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg shadow-sm">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if($task->description): ?>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-4 whitespace-pre-line"><?php echo e($task->description); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Details -->
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Details</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div><dt class="text-gray-500 dark:text-gray-400">Priority</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5"><?php echo e(ucfirst($task->priority)); ?></dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Status field</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5"><?php echo e($task->status ?? '—'); ?></dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Due date</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5"><?php echo e($task->due_date ? $task->due_date->format('M d, Y') : '—'); ?></dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Recurrence</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5"><?php echo e($task->recurrence ?? 'none'); ?></dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Created</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5"><?php echo e($task->created_at->format('M d, Y H:i')); ?></dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Updated</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5"><?php echo e($task->updated_at->format('M d, Y H:i')); ?></dd></div>
                        <?php if($task->trashed()): ?>
                            <div><dt class="text-gray-500 dark:text-gray-400">Deleted at</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5"><?php echo e($task->deleted_at->format('M d, Y H:i')); ?></dd></div>
                        <?php endif; ?>
                    </dl>

                    <?php if($task->labels->count() > 0): ?>
                        <div class="mt-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Labels</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <?php $__currentLoopData = $task->labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-flex items-center gap-1.5 text-xs px-2 py-1 rounded-full" style="background-color: <?php echo e($label->color); ?>20; color: <?php echo e($label->color); ?>">
                                        <span class="w-2 h-2 rounded-full" style="background-color: <?php echo e($label->color); ?>"></span>
                                        <?php echo e($label->name); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Subtasks -->
                <?php if($task->subtasks->count() > 0): ?>
                    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Subtasks (<?php echo e($task->subtasks->count()); ?>)</h3>
                        <ul class="space-y-2">
                            <?php $__currentLoopData = $task->subtasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="flex items-center gap-2 text-sm">
                                    <span class="w-4 h-4 rounded border border-gray-300 dark:border-gray-600 flex items-center justify-center <?php echo e($subtask->completed ? 'bg-green-500 border-green-500' : ''); ?>">
                                        <?php if($subtask->completed): ?>
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        <?php endif; ?>
                                    </span>
                                    <span class="text-gray-700 dark:text-gray-300 <?php echo e($subtask->completed ? 'line-through text-gray-400 dark:text-gray-500' : ''); ?>"><?php echo e($subtask->title); ?></span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Comments -->
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Comments (<?php echo e($task->comments->count()); ?>)</h3>
                    <?php $__empty_1 = true; $__currentLoopData = $task->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300"><?php echo e($comment->user->name ?? 'Unknown'); ?></span>
                                <span>·</span>
                                <span><?php echo e($comment->created_at->diffForHumans()); ?></span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line"><?php echo e($comment->body); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">No comments.</p>
                    <?php endif; ?>
                </div>

                <!-- Attachments -->
                <?php if($task->attachments->count() > 0): ?>
                    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Attachments (<?php echo e($task->attachments->count()); ?>)</h3>
                        <ul class="space-y-2 text-sm">
                            <?php $__currentLoopData = $task->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span><?php echo e($attachment->filename); ?></span>
                                    <span class="text-xs text-gray-400">(<?php echo e(number_format($attachment->size / 1024, 1)); ?> KB)</span>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
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
<?php /**PATH /home/tehnic/laravel/todo/resources/views/admin/tasks/show.blade.php ENDPATH**/ ?>