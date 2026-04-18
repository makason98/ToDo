<x-admin-layout>
    <x-slot name="title">Feedback</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('admin.partials.sidebar', ['currentPage' => 'feedback'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Feedback</h1>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Feedback</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $feedback->total() }} matching · user bug reports and improvement requests</p>
                </div>

                <!-- Status summary -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-blue-500">{{ $counts['open'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Open</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-yellow-500">{{ $counts['in_progress'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">In progress</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-green-500">{{ $counts['done'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Done</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-gray-500">{{ $counts['rejected'] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Rejected</p>
                    </div>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">{{ session('status') }}</div>
                @endif

                <form method="GET" action="{{ route('admin.feedback.index') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Title or body..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">User</label>
                            <input type="text" name="user" value="{{ $userSearch }}" placeholder="Name or email..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                            <select name="status" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="unresolved" {{ $status === 'unresolved' ? 'selected' : '' }}>Unresolved (open + in progress)</option>
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                                @foreach(\App\Models\FeedbackRequest::STATUSES as $value => $label)
                                    <option value="{{ $value }}" {{ $status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Type</label>
                            <select name="type" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All</option>
                                @foreach(\App\Models\FeedbackRequest::TYPES as $value => $label)
                                    <option value="{{ $value }}" {{ $type === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">Apply</button>
                        <a href="{{ route('admin.feedback.index') }}" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Clear</a>
                    </div>
                </form>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="text-left px-4 py-3 font-semibold">Title</th>
                                    <th class="text-center px-4 py-3 font-semibold">Type</th>
                                    <th class="text-center px-4 py-3 font-semibold">Status</th>
                                    <th class="text-center px-4 py-3 font-semibold">From</th>
                                    <th class="text-center px-4 py-3 font-semibold">Submitted</th>
                                    <th class="text-center px-4 py-3 font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($feedback as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $item->title }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5 max-w-md">{{ $item->body }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-full
                                                @if($item->type === 'bug') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300
                                                @elseif($item->type === 'improvement') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                                @endif">{{ $item->typeLabel() }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 rounded-full
                                                @if($item->status === 'open') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                                @elseif($item->status === 'in_progress') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                                @elseif($item->status === 'done') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                                @else bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                                @endif">{{ $item->statusLabel() }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($item->user)
                                                <a href="{{ route('admin.users.show', $item->user) }}" class="text-red-500 hover:text-red-600">{{ $item->user->name }}</a>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap" title="{{ $item->created_at }}">{{ $item->created_at->diffForHumans() }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.feedback.show', $item) }}"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-sm hover:shadow transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                    Open
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No feedback matches your filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($feedback->hasPages())
                    <div class="mt-4">{{ $feedback->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</x-admin-layout>
