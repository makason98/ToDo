<x-admin-layout>
    <x-slot name="title">Activity Log</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('admin.partials.sidebar', ['currentPage' => 'activity'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Activity Log</h1>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Activity Log</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $logs->total() }} entries · platform-wide audit trail</p>
                </div>

                <form method="GET" action="{{ route('admin.activity.index') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search description</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">User</label>
                            <input type="text" name="user" value="{{ $userSearch }}" placeholder="Name or email..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Action</label>
                            <select name="action" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="">Any</option>
                                @foreach($actions as $a)
                                    <option value="{{ $a }}" {{ $action === $a ? 'selected' : '' }}>{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Subject</label>
                            <select name="subject_type" class="w-full px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="">Any</option>
                                @foreach($subjectTypes as $s)
                                    <option value="{{ $s }}" {{ $subjectType === $s ? 'selected' : '' }}>{{ class_basename($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">From</label>
                            <input type="date" name="from" value="{{ $from }}" class="w-full px-2 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">To</label>
                            <input type="date" name="to" value="{{ $to }}" class="w-full px-2 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">Apply</button>
                        <a href="{{ route('admin.activity.index') }}" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">Clear</a>
                    </div>
                </form>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="text-center px-4 py-3 font-semibold">When</th>
                                    <th class="text-center px-4 py-3 font-semibold">User</th>
                                    <th class="text-center px-4 py-3 font-semibold">Action</th>
                                    <th class="text-center px-4 py-3 font-semibold">Subject</th>
                                    <th class="text-left px-4 py-3 font-semibold">Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap" title="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($log->user)
                                                <a href="{{ route('admin.users.show', $log->user) }}" class="text-red-500 hover:text-red-600">{{ $log->user->name }}</a>
                                            @else
                                                <span class="text-gray-400">system</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">{{ $log->action }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                            <span class="text-xs">{{ class_basename($log->subject_type) }}{{ $log->subject_id ? ' #' . $log->subject_id : '' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $log->description }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No activity matches your filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($logs->hasPages())
                    <div class="mt-4">{{ $logs->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</x-admin-layout>
