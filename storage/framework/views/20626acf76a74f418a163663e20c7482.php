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
     <?php $__env->slot('title', null, []); ?> Reports <?php $__env->endSlot(); ?>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            <?php echo $__env->make('admin.partials.sidebar', ['currentPage' => 'reports'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Reports</h1>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Reports</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Date-ranged analytics across the platform.</p>
                </div>

                <!-- Date range -->
                <form method="GET" action="<?php echo e(route('admin.reports.index')); ?>" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">From</label>
                            <input type="date" name="from" value="<?php echo e($from->toDateString()); ?>"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">To</label>
                            <input type="date" name="to" value="<?php echo e($to->toDateString()); ?>"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-6 flex items-center gap-2">
                            <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">Apply</button>
                            <a href="<?php echo e(route('admin.reports.index')); ?>" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Last 30 days</a>
                            <a href="<?php echo e(route('admin.reports.index', ['from' => now()->subDays(6)->toDateString(), 'to' => now()->toDateString()])); ?>" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Last 7 days</a>
                            <a href="<?php echo e(route('admin.reports.index', ['from' => now()->startOfMonth()->toDateString(), 'to' => now()->toDateString()])); ?>" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">This month</a>
                        </div>
                    </div>
                </form>

                <!-- Totals -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo e($totalSignups); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">New signups</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-blue-500"><?php echo e($totalCreated); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tasks created</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-green-500"><?php echo e($totalCompleted); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tasks completed</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-purple-500"><?php echo e($completionRate); ?>%</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Completion rate</p>
                    </div>
                </div>

                <!-- Daily charts -->
                <?php
                    $renderBars = function ($values, $max, $color, $days) {
                        $count = count($values);
                        return compact('values', 'max', 'color', 'days', 'count');
                    };
                ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <?php $__currentLoopData = [
                        ['New signups', $signups, $maxSignups, 'bg-red-500'],
                        ['Tasks created', $tasksCreated, $maxCreated, 'bg-blue-500'],
                        ['Tasks completed', $tasksCompleted, $maxCompleted, 'bg-green-500'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$title, $values, $max, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4"><?php echo e($title); ?></h2>
                            <div class="flex items-end gap-1 h-40">
                                <?php $__currentLoopData = $values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex-1 flex items-end" title="<?php echo e($days[$i]); ?>: <?php echo e($v); ?>">
                                        <div class="w-full <?php echo e($color); ?> hover:opacity-80 transition-opacity rounded-t"
                                             style="height: <?php echo e(($v / $max) * 100); ?>%; min-height: <?php echo e($v > 0 ? '2px' : '0'); ?>;"></div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 mt-2">
                                <span><?php echo e(\Carbon\Carbon::parse($days[0])->format('M d')); ?></span>
                                <span><?php echo e(\Carbon\Carbon::parse(end($days))->format('M d')); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Hourly distribution -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Task creations by hour of day</h2>
                    <div class="flex items-end gap-1 h-32">
                        <?php $__currentLoopData = $hourly; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex-1 flex items-end" title="<?php echo e(sprintf('%02d', $h)); ?>:00 — <?php echo e($count); ?>">
                                <div class="w-full bg-orange-500 hover:opacity-80 transition-opacity rounded-t"
                                     style="height: <?php echo e(($count / $maxHourly) * 100); ?>%; min-height: <?php echo e($count > 0 ? '2px' : '0'); ?>;"></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 mt-2">
                        <span>00</span><span>06</span><span>12</span><span>18</span><span>23</span>
                    </div>
                </div>

                <!-- Top labels + top users -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Top 5 labels (in range)</h2>
                        <?php $__empty_1 = true; $__currentLoopData = $topLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center gap-3 py-2 border-b border-gray-50 dark:border-gray-700 last:border-0">
                                <span class="w-3 h-3 rounded-full shrink-0" style="background-color: <?php echo e($label->color); ?>"></span>
                                <span class="flex-1 text-sm text-gray-700 dark:text-gray-300 truncate"><?php echo e($label->name); ?></span>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300"><?php echo e($label->tasks_count); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">No label usage in range.</p>
                        <?php endif; ?>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Top 10 users by tasks created (in range)</h2>
                        <?php $__empty_1 = true; $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center gap-3 py-2 border-b border-gray-50 dark:border-gray-700 last:border-0">
                                <div class="w-7 h-7 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center text-xs font-semibold shrink-0"><?php echo e(strtoupper(substr($u->name, 0, 1))); ?></div>
                                <div class="flex-1 min-w-0">
                                    <a href="<?php echo e(route('admin.users.show', $u)); ?>" class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-red-500 truncate block"><?php echo e($u->name); ?></a>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300"><?php echo e($u->tasks_count); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">No activity in range.</p>
                        <?php endif; ?>
                    </div>
                </div>
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
<?php /**PATH /home/tehnic/laravel/todo/resources/views/admin/reports/index.blade.php ENDPATH**/ ?>