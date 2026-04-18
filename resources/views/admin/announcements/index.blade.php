<x-admin-layout>
    <x-slot name="title">Announcements</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('admin.partials.sidebar', ['currentPage' => 'announcements'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Announcements</h1>
            </div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Announcements</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $announcements->total() }} total · platform broadcast history</p>
                    </div>
                    <a href="{{ route('admin.announcements.create') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        New announcement
                    </a>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">{{ session('status') }}</div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="text-left px-4 py-3 font-semibold">Title</th>
                                    <th class="text-center px-4 py-3 font-semibold">Audience</th>
                                    <th class="text-center px-4 py-3 font-semibold">Recipients</th>
                                    <th class="text-center px-4 py-3 font-semibold">Sender</th>
                                    <th class="text-center px-4 py-3 font-semibold">Sent</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($announcements as $a)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $a->title }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">{{ $a->body }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center text-xs font-medium px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">{{ $a->audienceLabel() }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300 font-medium">{{ $a->recipients_count }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($a->sender)
                                                <a href="{{ route('admin.users.show', $a->sender) }}" class="text-red-500 hover:text-red-600">{{ $a->sender->name }}</a>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 whitespace-nowrap" title="{{ $a->created_at }}">{{ $a->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No announcements yet. Click "New announcement" to send your first.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($announcements->hasPages())
                    <div class="mt-4">{{ $announcements->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</x-admin-layout>
