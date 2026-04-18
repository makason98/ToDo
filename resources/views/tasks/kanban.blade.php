<x-app-layout>
    <x-slot name="title">Kanban Board</x-slot>

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
            @include('tasks.partials.sidebar', ['currentPage' => 'kanban'])
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
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Board</h1>
            </div>

            <div class="p-4 sm:p-6">
                <!-- View toggle -->
                <div class="hidden sm:flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Board</h1>
                    <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                        <a href="{{ route('tasks.index') }}" class="p-1.5 rounded text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" title="List view">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </a>
                        <a href="{{ route('tasks.kanban') }}" class="p-1.5 rounded bg-white dark:bg-gray-700 text-red-500 shadow-sm" title="Board view">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Kanban columns -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- To Do -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-3 px-1">
                            <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">To Do</h2>
                            <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full px-2 py-0.5">{{ $todoTasks->count() }}</span>
                        </div>
                        <div class="space-y-2 min-h-16" x-sortable-kanban data-status="todo">
                            @foreach($todoTasks as $task)
                                @include('tasks.partials.kanban-card', ['task' => $task])
                            @endforeach
                            @if($todoTasks->isEmpty())
                                <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4 pointer-events-none">No tasks</p>
                            @endif
                        </div>
                    </div>

                    <!-- In Progress -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-3 px-1">
                            <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">In Progress</h2>
                            <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full px-2 py-0.5">{{ $inProgressTasks->count() }}</span>
                        </div>
                        <div class="space-y-2 min-h-16" x-sortable-kanban data-status="in_progress">
                            @foreach($inProgressTasks as $task)
                                @include('tasks.partials.kanban-card', ['task' => $task])
                            @endforeach
                            @if($inProgressTasks->isEmpty())
                                <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4 pointer-events-none">No tasks</p>
                            @endif
                        </div>
                    </div>

                    <!-- Done -->
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div class="flex items-center gap-2 mb-3 px-1">
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Done</h2>
                            <span class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-full px-2 py-0.5">{{ $doneTasks->count() }}</span>
                        </div>
                        <div class="space-y-2 min-h-16" x-sortable-kanban data-status="done">
                            @foreach($doneTasks as $task)
                                @include('tasks.partials.kanban-card', ['task' => $task])
                            @endforeach
                            @if($doneTasks->isEmpty())
                                <p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4 pointer-events-none">No tasks</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
