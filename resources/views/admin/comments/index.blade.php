<x-admin-layout>
    <x-slot name="title">Comments</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('admin.partials.sidebar', ['currentPage' => 'comments'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Comments</h1>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Comments</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $comments->total() }} matching</p>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">{{ session('status') }}</div>
                @endif

                <form method="GET" action="{{ route('admin.comments.index') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search body</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search comment text..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Author</label>
                            <input type="text" name="user" value="{{ $userSearch }}" placeholder="Search by name or email..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Show</label>
                            <select name="deletion" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="active" {{ $deletion === 'active' ? 'selected' : '' }}>Live</option>
                                <option value="trashed" {{ $deletion === 'trashed' ? 'selected' : '' }}>Trashed</option>
                                <option value="all" {{ $deletion === 'all' ? 'selected' : '' }}>All</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">Apply</button>
                        <a href="{{ route('admin.comments.index') }}" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Clear</a>
                    </div>
                </form>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="text-left px-4 py-3 font-semibold">Body</th>
                                    <th class="text-center px-4 py-3 font-semibold">Author</th>
                                    <th class="text-center px-4 py-3 font-semibold">Task</th>
                                    <th class="text-center px-4 py-3 font-semibold">Created</th>
                                    <th class="text-center px-4 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($comments as $comment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 {{ $comment->trashed() ? 'opacity-60' : '' }}">
                                        <td class="px-4 py-3 max-w-md">
                                            <div class="flex items-center gap-2">
                                                <span class="text-gray-700 dark:text-gray-300 line-clamp-2">{{ $comment->body }}</span>
                                                @if($comment->trashed())
                                                    <span class="text-xs px-1.5 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded shrink-0">Trashed</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($comment->user)
                                                <a href="{{ route('admin.users.show', $comment->user) }}" class="text-red-500 hover:text-red-600">{{ $comment->user->name }}</a>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($comment->task)
                                                <a href="{{ route('admin.tasks.show', $comment->task->id) }}" class="text-red-500 hover:text-red-600 truncate inline-block max-w-xs">{{ $comment->task->title }}</a>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $comment->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-2">
                                                @if($comment->trashed())
                                                    <form method="POST" action="{{ route('admin.comments.restore', $comment->id) }}">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-green-500 hover:bg-green-600 text-white rounded-lg shadow-sm hover:shadow transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                            Restore
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.comments.destroy', $comment->id) }}" onsubmit="return confirm('Soft-delete this comment? It can be restored later.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm hover:shadow transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a2 2 0 012-2h2a2 2 0 012 2v3"/></svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No comments match your filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($comments->hasPages())
                    <div class="mt-4">{{ $comments->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</x-admin-layout>
