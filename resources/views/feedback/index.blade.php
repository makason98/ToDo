<x-app-layout>
    <x-slot name="title">Feedback</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('tasks.partials.sidebar', ['currentPage' => 'feedback'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Feedback</h1>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Your feedback</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bugs reports and improvement ideas you've sent to the team.</p>
                    </div>
                    <a href="{{ route('feedback.create') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        New feedback
                    </a>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">{{ session('status') }}</div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($feedback as $item)
                        <a href="{{ route('feedback.show', $item) }}" class="block p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg
                                    @if($item->type === 'bug') bg-red-100 dark:bg-red-900/30 text-red-500
                                    @elseif($item->type === 'improvement') bg-blue-100 dark:bg-blue-900/30 text-blue-500
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-500
                                    @endif
                                    flex items-center justify-center shrink-0">
                                    @if($item->type === 'bug')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9V5a4 4 0 018 0v4M5 9h14l-1 11H6L5 9z"/></svg>
                                    @elseif($item->type === 'improvement')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11H7a2 2 0 01-2-2V7a2 2 0 012-2h2m0 6V5m0 6v6a2 2 0 002 2h2a2 2 0 002-2v-6m-6 0h6m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m0 6V5"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $item->title }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                            @if($item->status === 'open') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                            @elseif($item->status === 'in_progress') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                            @elseif($item->status === 'done') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                            @else bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                            @endif">{{ $item->statusLabel() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">{{ $item->body }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $item->typeLabel() }} · submitted {{ $item->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-sm text-gray-400 dark:text-gray-500">You haven't submitted feedback yet.</p>
                            <a href="{{ route('feedback.create') }}" class="inline-block mt-3 text-sm text-red-500 hover:text-red-600 font-medium">Send your first request →</a>
                        </div>
                    @endforelse
                </div>

                @if($feedback->hasPages())
                    <div class="mt-4">{{ $feedback->links() }}</div>
                @endif
            </div>
        </main>
    </div>
</x-app-layout>
