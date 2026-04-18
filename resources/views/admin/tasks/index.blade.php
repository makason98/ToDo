<x-admin-layout>
    <x-slot name="title">Tasks</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('admin.partials.sidebar', ['currentPage' => 'tasks'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Tasks</h1>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Tasks</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $tasks->total() }} matching</p>
                    </div>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Filters -->
                <form method="GET" action="{{ route('admin.tasks.index') }}"
                    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Title or description..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">User</label>
                            <input type="text" name="user" value="{{ $userSearch }}" placeholder="Search by name or email..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Priority</label>
                            <select name="priority" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="all" {{ $priority === 'all' ? 'selected' : '' }}>Any</option>
                                <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>Med</option>
                                <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Show</label>
                            <select name="deletion" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="active" {{ $deletion === 'active' ? 'selected' : '' }}>Live</option>
                                <option value="trashed" {{ $deletion === 'trashed' ? 'selected' : '' }}>Trashed</option>
                                <option value="all" {{ $deletion === 'all' ? 'selected' : '' }}>All</option>
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Sort</label>
                            <select name="sort" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>New</option>
                                <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Old</option>
                                <option value="due_soonest" {{ $sort === 'due_soonest' ? 'selected' : '' }}>Due</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">Apply</button>
                        <a href="{{ route('admin.tasks.index') }}" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Clear</a>
                    </div>
                </form>

                <!-- Tasks table -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="text-left px-4 py-3 font-semibold">Title</th>
                                    <th class="text-center px-4 py-3 font-semibold">Owner</th>
                                    <th class="text-center px-4 py-3 font-semibold">Priority</th>
                                    <th class="text-center px-4 py-3 font-semibold">Status</th>
                                    <th class="text-center px-4 py-3 font-semibold">Due</th>
                                    <th class="text-center px-4 py-3 font-semibold">Created</th>
                                    <th class="text-center px-4 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($tasks as $task)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 {{ $task->trashed() ? 'opacity-60' : '' }}">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-gray-900 dark:text-gray-100 truncate max-w-xs">{{ $task->title }}</span>
                                                @if($task->trashed())
                                                    <span class="text-xs px-1.5 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">Trashed</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('admin.users.show', $task->user) }}" class="text-red-500 hover:text-red-600">{{ $task->user->name }}</a>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-block w-2 h-2 rounded-full {{ $task->priority === 'high' ? 'bg-red-400' : ($task->priority === 'low' ? 'bg-blue-400' : 'bg-orange-400') }}" title="{{ ucfirst($task->priority) }}"></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">{{ ucfirst($task->priority) }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($task->completed)
                                                <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full">Completed</span>
                                            @else
                                                <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full">Active</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $task->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.tasks.show', $task->id) }}"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg shadow-sm hover:shadow transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View
                                                </a>
                                                @if($task->trashed())
                                                    <form method="POST" action="{{ route('admin.tasks.restore', $task->id) }}">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-sm hover:shadow transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                            </svg>
                                                            Restore
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.tasks.destroy', $task->id) }}"
                                                        onsubmit="return confirm('Soft-delete this task? It can be restored later.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm hover:shadow transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a2 2 0 012-2h2a2 2 0 012 2v3"/>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No tasks match your filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($tasks->hasPages())
                    <div class="mt-4">{{ $tasks->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</x-admin-layout>
