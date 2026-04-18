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
     <?php $__env->slot('title', null, []); ?> Users <?php $__env->endSlot(); ?>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            <?php echo $__env->make('admin.partials.sidebar', ['currentPage' => 'users'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </aside>

        <!-- Main content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Mobile header -->
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Users</h1>
            </div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Users</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?php echo e($users->total()); ?> total</p>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="<?php echo e(route('admin.users.index')); ?>"
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <!-- Search -->
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search by name or email..."
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400" />
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="all" <?php echo e($status === 'all' ? 'selected' : ''); ?>>All users</option>
                                <option value="verified" <?php echo e($status === 'verified' ? 'selected' : ''); ?>>Verified</option>
                                <option value="unverified" <?php echo e($status === 'unverified' ? 'selected' : ''); ?>>Unverified</option>
                                <option value="admin" <?php echo e($status === 'admin' ? 'selected' : ''); ?>>Admins</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Registered</label>
                            <select name="sort"
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="newest" <?php echo e($sort === 'newest' ? 'selected' : ''); ?>>Newest first</option>
                                <option value="oldest" <?php echo e($sort === 'oldest' ? 'selected' : ''); ?>>Oldest first</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                            Apply
                        </button>
                        <?php if($search !== '' || $status !== 'all' || $sort !== 'newest'): ?>
                            <a href="<?php echo e(route('admin.users.index')); ?>"
                                class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">
                                Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

                <!-- Users list -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="text-center px-4 py-3 font-semibold">User</th>
                                    <th class="text-center px-4 py-3 font-semibold">Email</th>
                                    <th class="text-center px-4 py-3 font-semibold">Status</th>
                                    <th class="text-center px-4 py-3 font-semibold">Tasks</th>
                                    <th class="text-center px-4 py-3 font-semibold">Registered</th>
                                    <th class="text-center px-4 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center text-sm font-semibold shrink-0">
                                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                                </div>
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($user->name); ?></span>
                                                    <?php if($user->isAdmin()): ?>
                                                        <span class="text-xs px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded"><?php echo e($user->adminRoleLabel()); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-400"><?php echo e($user->email); ?></td>
                                        <td class="px-4 py-3 text-center">
                                            <?php if($user->email_verified_at): ?>
                                                <span class="inline-flex items-center gap-2 text-xs font-medium px-4 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full">
                                                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Verified
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-2 text-xs font-medium px-4 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full">
                                                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Unverified
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300 font-medium"><?php echo e($user->tasks_count); ?></td>
                                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap" title="<?php echo e($user->created_at); ?>">
                                            <?php echo e($user->created_at->format('M d, Y')); ?>

                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="<?php echo e(route('admin.users.show', $user)); ?>"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg shadow-sm hover:shadow transition-all"
                                                    title="View user">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View
                                                </a>
                                                <?php if(auth()->user()->canEditUser($user)): ?>
                                                    <a href="<?php echo e(route('admin.users.edit', $user)); ?>"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm hover:shadow transition-all"
                                                        title="Edit user">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">
                                            No users match your filters.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- Pagination -->
                <?php if($users->hasPages()): ?>
                    <div class="mt-4">
                        <?php echo e($users->links()); ?>

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
<?php /**PATH /home/tehnic/laravel/todo/resources/views/admin/users/index.blade.php ENDPATH**/ ?>