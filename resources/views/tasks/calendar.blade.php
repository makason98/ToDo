<x-app-layout>
    <x-slot name="title">Calendar</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('tasks.partials.sidebar', ['currentPage' => 'calendar'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <!-- Mobile header -->
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Calendar</h1>
            </div>

            <div class="p-4 sm:p-6">
                <!-- Month navigation -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $date->format('F Y') }}</h1>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('tasks.calendar', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}"
                           class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <a href="{{ route('tasks.calendar') }}"
                           class="px-3 py-1 text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                            Today
                        </a>
                        <a href="{{ route('tasks.calendar', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}"
                           class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Calendar grid -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <!-- Day headers -->
                    <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                            <div class="px-2 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 text-center">{{ $day }}</div>
                        @endforeach
                    </div>

                    <!-- Calendar days -->
                    @php
                        $startOfMonth = $date->copy()->startOfMonth();
                        $endOfMonth = $date->copy()->endOfMonth();
                        $startDay = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                        $endDay = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);
                        $today = now()->toDateString();
                    @endphp

                    <div class="grid grid-cols-7">
                        @for($day = $startDay->copy(); $day->lte($endDay); $day->addDay())
                            @php
                                $dateKey = $day->format('Y-m-d');
                                $isCurrentMonth = $day->month === $date->month;
                                $isToday = $dateKey === $today;
                                $dayTasks = $tasks[$dateKey] ?? collect();
                            @endphp
                            <div class="min-h-20 sm:min-h-28 border-b border-r border-gray-100 dark:border-gray-700 p-1 {{ !$isCurrentMonth ? 'bg-gray-50/50 dark:bg-gray-800/50' : 'bg-white dark:bg-gray-900' }}">
                                <!-- Day number -->
                                <div class="flex items-center justify-center sm:justify-start sm:px-1">
                                    <span class="text-xs sm:text-sm font-medium w-6 h-6 flex items-center justify-center rounded-full
                                        {{ $isToday ? 'bg-red-500 text-white' : ($isCurrentMonth ? 'text-gray-700 dark:text-gray-300' : 'text-gray-300 dark:text-gray-600') }}">
                                        {{ $day->day }}
                                    </span>
                                </div>

                                <!-- Tasks for this day -->
                                <div class="mt-0.5 space-y-0.5">
                                    @foreach($dayTasks->take(3) as $task)
                                        <a href="{{ route('tasks.show', $task) }}"
                                           class="block px-1 py-0.5 rounded text-xs truncate transition-colors
                                               {{ $task->completed
                                                   ? 'line-through text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800'
                                                   : ($task->priority === 'high'
                                                       ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30'
                                                       : ($task->priority === 'low'
                                                           ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30'
                                                           : 'bg-orange-50 dark:bg-orange-900/20 text-orange-700 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-900/30'))
                                               }}">
                                            {{ Str::limit($task->title, 20) }}
                                        </a>
                                    @endforeach
                                    @if($dayTasks->count() > 3)
                                        <span class="block px-1 text-xs text-gray-400 dark:text-gray-500">+{{ $dayTasks->count() - 3 }} more</span>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
