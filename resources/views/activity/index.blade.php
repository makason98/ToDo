<x-app-layout>
    <x-slot name="title">Activity Log</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('tasks.partials.sidebar', ['currentPage' => 'activity'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Activity Log</h1>
            </div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <h1 class="hidden sm:block text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Activity Log</h1>

                @forelse($logs as $log)
                    <div class="flex items-start gap-3 py-3 border-b border-gray-100 dark:border-gray-700">
                        <!-- Icon based on action -->
                        <div class="mt-0.5 shrink-0">
                            @if($log->action === 'created')
                                <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                            @elseif($log->action === 'completed')
                                <div class="w-7 h-7 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @elseif($log->action === 'deleted')
                                <div class="w-7 h-7 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </div>
                            @elseif($log->action === 'commented')
                                <div class="w-7 h-7 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                            @elseif($log->action === 'status_changed')
                                <div class="w-7 h-7 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                            @else
                                <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $log->description }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <p class="text-gray-400 dark:text-gray-500 text-sm">No activity yet. Start creating tasks!</p>
                    </div>
                @endforelse

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
