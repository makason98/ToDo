<x-admin-layout>
    <x-slot name="title">Admin Dashboard</x-slot>

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
            @include('admin.partials.sidebar', ['currentPage' => 'dashboard'])
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
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Admin Dashboard</h1>
            </div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Platform overview</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Users, tasks, and activity across the platform.</p>

                <!-- User stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalUsers }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total users</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $verifiedUsers }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Verified</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $unverifiedUsers }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Unverified</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalAdmins }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Admins</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalTasks }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total tasks</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-50 dark:bg-green-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $completedTasks }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Completed</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $activeTasks }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Active</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg {{ $overdueTasks > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-gray-50 dark:bg-gray-700' }} flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $overdueTasks > 0 ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold {{ $overdueTasks > 0 ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">{{ $overdueTasks }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Overdue</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart + completion rate -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">New signups — last 7 days</h2>
                        <div class="flex items-end justify-between gap-2 h-40">
                            @foreach($signupsByDay as $day)
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <div class="w-full flex-1 flex items-end">
                                        <div class="w-full bg-red-500 hover:bg-red-600 transition-colors rounded-t"
                                             style="height: {{ ($day['count'] / $maxSignups) * 100 }}%; min-height: {{ $day['count'] > 0 ? '4px' : '0' }};"
                                             title="{{ $day['count'] }} signups">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $day['count'] }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $day['label'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Completion rate</h2>
                        <div class="flex items-center justify-center py-4">
                            <div class="relative w-32 h-32">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-200 dark:text-gray-700" stroke="currentColor" stroke-width="3" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="text-green-500" stroke="currentColor" stroke-width="3" fill="none"
                                          stroke-dasharray="{{ $completionRate }}, 100"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $completionRate }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-center text-xs mt-2">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">Today</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $completedToday }} done</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400">New</p>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $tasksCreatedToday }} today</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User growth summary + top users -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">User growth</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">New today</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $newUsersToday }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">New this week</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $newUsersThisWeek }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">New this month</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $newUsersThisMonth }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Top users by tasks</h2>
                        @forelse($topUsers as $user)
                            <div class="flex items-center gap-3 py-2 border-b border-gray-50 dark:border-gray-700 last:border-0">
                                <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center text-sm font-semibold shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 shrink-0">{{ $user->tasks_count }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">No users yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent signups -->
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Recent signups</h2>
                    @forelse($recentUsers as $user)
                        <div class="flex items-center gap-3 py-2 border-b border-gray-50 dark:border-gray-700 last:border-0">
                            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 flex items-center justify-center text-sm font-semibold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                    {{ $user->name }}
                                    @if($user->is_admin)
                                        <span class="ml-1 text-xs px-1.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded">Admin</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                @if($user->email_verified_at)
                                    <span class="inline-block w-2 h-2 rounded-full bg-green-500" title="Verified"></span>
                                @else
                                    <span class="inline-block w-2 h-2 rounded-full bg-yellow-500" title="Unverified"></span>
                                @endif
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">No users yet.</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</x-admin-layout>
