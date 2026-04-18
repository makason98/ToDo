<x-app-layout>
    <x-slot name="title">Tasks</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ showAddTask: {{ request('add') ? 'true' : 'false' }}, sidebarOpen: false, showFilters: {{ $hasFilters ? 'true' : 'false' }} }">
        <!-- Mobile sidebar overlay -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('tasks.partials.sidebar', ['currentPage' => $filter === 'completed' ? 'completed' : 'tasks'])
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
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex-1">
                    {{ request('search') ? 'Search: ' . request('search') : ($filter === 'completed' ? 'Completed' : 'Tasks') }}
                </h1>
                <button @click="showFilters = !showFilters"
                    class="p-2 rounded-lg {{ $hasFilters ? 'text-red-500' : 'text-gray-400 dark:text-gray-500' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </button>
            </div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="hidden sm:flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ request('search') ? 'Search: ' . request('search') : ($filter === 'completed' ? 'Completed' : 'Tasks') }}
                    </h1>
                    <div class="flex items-center gap-2">
                        <!-- Filter button -->
                        <button @click="showFilters = !showFilters"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-lg transition-colors {{ $hasFilters ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filter
                            @if($hasFilters)
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                            @endif
                        </button>

                        <!-- View toggle -->
                        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                            <a href="{{ route('tasks.index') }}" class="p-1.5 rounded bg-white dark:bg-gray-700 text-red-500 shadow-sm" title="List view">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </a>
                            <a href="{{ route('tasks.kanban') }}" class="p-1.5 rounded text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" title="Board view">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filter panel -->
                <div x-show="showFilters" x-transition class="mb-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <form action="{{ route('tasks.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
                        <input type="hidden" name="filter" value="{{ $filter }}">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <!-- Priority -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Priority</label>
                            <select name="priority"
                                class="text-xs border border-gray-200 dark:border-gray-600 rounded-md pl-2 pr-7 py-1.5 bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-red-500 focus:border-red-500">
                                <option value="">All</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>

                        <!-- Date from -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Due from</label>
                            <input type="text" name="date_from" x-datepicker value="{{ request('date_from') }}" placeholder="Start date"
                                class="text-xs border border-gray-200 dark:border-gray-600 rounded-md px-2 py-1.5 w-28 bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-red-500 focus:border-red-500" />
                        </div>

                        <!-- Date to -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Due to</label>
                            <input type="text" name="date_to" x-datepicker value="{{ request('date_to') }}" placeholder="End date"
                                class="text-xs border border-gray-200 dark:border-gray-600 rounded-md px-2 py-1.5 w-28 bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-red-500 focus:border-red-500" />
                        </div>

                        <!-- Label -->
                        @if($labels->count() > 0)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Label</label>
                                <select name="label"
                                    class="text-xs border border-gray-200 dark:border-gray-600 rounded-md pl-2 pr-7 py-1.5 bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-red-500 focus:border-red-500">
                                    <option value="">All</option>
                                    @foreach($labels as $label)
                                        <option value="{{ $label->id }}" {{ request('label') == $label->id ? 'selected' : '' }}>{{ $label->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="flex items-center gap-2">
                            <button type="submit"
                                class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md font-medium transition-colors">
                                Apply
                            </button>
                            @if($hasFilters)
                                <a href="{{ route('tasks.index', ['filter' => $filter]) }}"
                                   class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-500 px-2 py-1.5">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Add task form -->
                <div x-show="showAddTask" x-transition class="mb-6">
                    <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data"
                          class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800" x-data="{ files: [] }">
                        @csrf
                        <input type="text" name="title" placeholder="Task name" required autofocus
                            class="w-full text-sm font-medium border-0 p-0 focus:ring-0 placeholder-gray-400 dark:placeholder-gray-500 bg-transparent dark:text-gray-100" />
                        <textarea name="description" placeholder="Description" rows="2"
                            class="w-full text-sm border-0 p-0 mt-2 focus:ring-0 placeholder-gray-400 dark:placeholder-gray-500 resize-none bg-transparent dark:text-gray-100"></textarea>

                        <!-- File previews -->
                        <template x-if="files.length > 0">
                            <div class="flex flex-wrap gap-2 mt-2">
                                <template x-for="(file, index) in files" :key="index">
                                    <span class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded px-2 py-1 text-xs text-gray-600 dark:text-gray-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                        </svg>
                                        <span x-text="file.name"></span>
                                    </span>
                                </template>
                            </div>
                        </template>

                        <input type="file" name="attachments[]" multiple x-ref="taskFiles" class="hidden"
                            @change="files = Array.from($refs.taskFiles.files)" />

                        <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                            <input type="text" name="due_date" x-datepicker placeholder="Due date"
                                class="text-xs border border-gray-200 dark:border-gray-600 rounded-md px-2 py-1 w-28 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                            <select name="priority"
                                class="text-xs border border-gray-200 dark:border-gray-600 rounded-md pl-2 pr-7 py-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                                <option value="high">High</option>
                            </select>
                            <div x-data="{ rec: 'none' }" class="flex items-center gap-1">
                                <select name="recurrence" x-model="rec"
                                    class="text-xs border border-gray-200 dark:border-gray-600 rounded-md pl-2 pr-7 py-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                    <option value="none">No repeat</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="custom">Custom</option>
                                </select>
                                <template x-if="rec === 'custom'">
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs text-gray-400">every</span>
                                        <input type="number" name="recurrence_interval" min="2" value="2"
                                            class="text-xs border border-gray-200 dark:border-gray-600 rounded-md px-1.5 py-1 w-12 bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-red-500 focus:border-red-500" />
                                        <span class="text-xs text-gray-400">days</span>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="$refs.taskFiles.click()"
                                class="inline-flex items-center gap-1 text-xs border border-gray-200 dark:border-gray-600 rounded-md px-2 py-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Attachment
                            </button>
                            @if($labels->count() > 0)
                                <div class="relative" x-data="{ showLabels: false }">
                                    <button type="button" @click="showLabels = !showLabels"
                                        class="inline-flex items-center gap-1 text-xs border border-gray-200 dark:border-gray-600 rounded-md px-2 py-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        Labels
                                    </button>
                                    <div x-show="showLabels" @click.away="showLabels = false"
                                         class="absolute bottom-full left-0 mb-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg p-2 w-48 z-10">
                                        @foreach($labels as $label)
                                            <label class="flex items-center gap-2 px-2 py-1.5 text-xs rounded hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                                <input type="checkbox" name="label_ids[]" value="{{ $label->id }}" class="rounded border-gray-300 dark:border-gray-600 text-red-500 focus:ring-red-500">
                                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $label->color }}"></span>
                                                <span class="text-gray-700 dark:text-gray-300">{{ $label->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="flex-1"></div>
                            <button type="button" @click="showAddTask = false"
                                class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 px-3 py-1">Cancel</button>
                            <button type="submit"
                                class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg font-medium transition-colors">Add task</button>
                        </div>
                    </form>
                </div>

                <!-- Task list -->
                <div x-sortable>
                @forelse ($tasks as $task)
                    <div data-id="{{ $task->id }}" class="group flex items-start gap-3 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 -mx-2 px-2 rounded-lg transition-colors">
                        <!-- Drag handle -->
                        <div class="drag-handle cursor-grab active:cursor-grabbing mt-1.5 text-gray-300 dark:text-gray-600 hover:text-gray-400 dark:hover:text-gray-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="9" cy="6" r="1.5"/><circle cx="15" cy="6" r="1.5"/>
                                <circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/>
                                <circle cx="9" cy="18" r="1.5"/><circle cx="15" cy="18" r="1.5"/>
                            </svg>
                        </div>
                        <!-- Toggle complete -->
                        <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="mt-0.5">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="flex-shrink-0 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors
                                {{ $task->completed ? 'bg-gray-400 border-gray-400' : ($task->priority === 'high' ? 'border-red-400 hover:bg-red-50 dark:hover:bg-red-900/20' : ($task->priority === 'low' ? 'border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20' : 'border-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/20')) }}">
                                @if($task->completed)
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </button>
                        </form>

                        <!-- Task content -->
                        <a href="{{ route('tasks.show', $task) }}" class="flex-1 min-w-0">
                            <p class="text-sm {{ $task->completed ? 'line-through text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-gray-100' }}">
                                {{ $task->title }}
                            </p>
                            @if($task->description)
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 truncate">{{ $task->description }}</p>
                            @endif
                            <div class="flex items-center gap-3 mt-1">
                                @if($task->due_date)
                                    <span class="text-xs {{ $task->due_date->isPast() && !$task->completed ? 'text-red-500' : 'text-gray-400 dark:text-gray-500' }}">
                                        {{ $task->due_date->format('M d') }}
                                    </span>
                                @endif
                                @if($task->comments_count ?? $task->comments->count() > 0)
                                    <span class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        {{ $task->comments->count() }}
                                    </span>
                                @endif
                                @if($task->subtasks->count() > 0)
                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $task->subtasks->where('completed', true)->count() }}/{{ $task->subtasks->count() }}
                                    </span>
                                @endif
                                @if($task->isRecurring())
                                    <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="{{ ucfirst($task->recurrence) }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                @endif
                                @foreach($task->labels as $label)
                                    <span class="text-xs rounded-full px-2 py-0.5 text-white" style="background-color: {{ $label->color }}">{{ $label->name }}</span>
                                @endforeach
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-12">
                        @if(request('search'))
                            <p class="text-gray-400 dark:text-gray-500 text-sm">No tasks found for "{{ request('search') }}"</p>
                        @elseif($filter === 'completed')
                            <p class="text-gray-400 dark:text-gray-500 text-sm">No completed tasks yet.</p>
                        @else
                            <p class="text-gray-400 dark:text-gray-500 text-sm">No tasks yet. Click "+ Add task" to get started.</p>
                        @endif
                    </div>
                @endforelse
                </div>

                <!-- Add task inline button -->
                @if(!request('search'))
                    <button x-show="!showAddTask" @click="showAddTask = true"
                        class="flex items-center gap-2 text-gray-400 dark:text-gray-500 hover:text-red-500 text-sm mt-4 group/add transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add task
                    </button>
                @endif
            </div>
        </main>
    </div>
</x-app-layout>
