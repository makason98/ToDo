<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

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
            @include('tasks.partials.sidebar', ['currentPage' => 'dashboard'])
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
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Dashboard</h1>
            </div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <!-- Greeting -->
                @php
                    $hour = now()->hour;
                    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                @endphp
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $greeting }}, {{ auth()->user()->name }}!</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Here's your productivity overview.</p>

                <!-- Stats cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $activeTasksCount }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Active</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg {{ $overdueCount > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-gray-50 dark:bg-gray-700' }} flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $overdueCount > 0 ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold {{ $overdueCount > 0 ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">{{ $overdueCount }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Overdue</p>
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
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $completedToday }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Done today</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center">
                                <span class="text-lg">🔥</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $streak }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Day streak</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two columns -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Completion Summary</h2>
                        @php $maxCompleted = max($completedToday, $completedThisWeek, $completedThisMonth, 1); @endphp
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    <span>Today</span><span>{{ $completedToday }} tasks</span>
                                </div>
                                <div class="w-full h-2.5 bg-gray-100 dark:bg-gray-700 rounded-full">
                                    <div class="h-2.5 bg-green-500 rounded-full" style="width: {{ ($completedToday / $maxCompleted) * 100 }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    <span>This week</span><span>{{ $completedThisWeek }} tasks</span>
                                </div>
                                <div class="w-full h-2.5 bg-gray-100 dark:bg-gray-700 rounded-full">
                                    <div class="h-2.5 bg-blue-500 rounded-full" style="width: {{ ($completedThisWeek / $maxCompleted) * 100 }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                    <span>This month</span><span>{{ $completedThisMonth }} tasks</span>
                                </div>
                                <div class="w-full h-2.5 bg-gray-100 dark:bg-gray-700 rounded-full">
                                    <div class="h-2.5 bg-purple-500 rounded-full" style="width: {{ ($completedThisMonth / $maxCompleted) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Upcoming</h2>
                        @forelse($upcomingTasks as $task)
                            <a href="{{ route('tasks.show', $task) }}" class="flex items-center gap-3 py-2.5 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 -mx-2 px-2 rounded transition-colors">
                                <span class="w-2 h-2 rounded-full shrink-0 {{ $task->priority === 'high' ? 'bg-red-400' : ($task->priority === 'low' ? 'bg-blue-400' : 'bg-orange-400') }}"></span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-gray-100 truncate">{{ $task->title }}</p>
                                </div>
                                <span class="text-xs shrink-0 {{ $task->due_date->isPast() ? 'text-red-500' : 'text-gray-400 dark:text-gray-500' }}">
                                    {{ $task->due_date->format('M d') }}
                                </span>
                            </a>
                        @empty
                            <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">No upcoming tasks with due dates.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Recent Activity</h2>
                    @forelse($recentActivity as $activity)
                        <div class="flex items-center gap-3 py-2 border-b border-gray-50 dark:border-gray-700 last:border-0">
                            @if($activity['type'] === 'completed')
                                <div class="w-6 h-6 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-700 dark:text-gray-300 truncate">
                                    <span class="text-gray-400 dark:text-gray-500">{{ $activity['type'] === 'completed' ? 'Completed' : 'Created' }}</span>
                                    {{ $activity['task']->title }}
                                </p>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-gray-500 shrink-0">{{ $activity['date']->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">No activity yet. Start creating tasks!</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
