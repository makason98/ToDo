<x-app-layout>
    <x-slot name="title">{{ $feedback->title }}</x-slot>

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
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate">{{ $feedback->title }}</h1>
            </div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <a href="{{ route('feedback.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to feedback
                </a>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">{{ session('status') }}</div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg
                            @if($feedback->type === 'bug') bg-red-100 dark:bg-red-900/30 text-red-500
                            @elseif($feedback->type === 'improvement') bg-blue-100 dark:bg-blue-900/30 text-blue-500
                            @else bg-gray-100 dark:bg-gray-700 text-gray-500
                            @endif
                            flex items-center justify-center shrink-0">
                            @if($feedback->type === 'bug')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9V5a4 4 0 018 0v4M5 9h14l-1 11H6L5 9z"/></svg>
                            @elseif($feedback->type === 'improvement')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 break-words">{{ $feedback->title }}</h2>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                    @if($feedback->status === 'open') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                    @elseif($feedback->status === 'in_progress') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                    @elseif($feedback->status === 'done') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                    @else bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                    @endif">{{ $feedback->statusLabel() }}</span>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $feedback->typeLabel() }} · submitted {{ $feedback->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $feedback->body }}</div>
                </div>

                @if($feedback->isResolved() || $feedback->resolution_note)
                    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Admin response</h3>
                        @if($feedback->resolution_note)
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $feedback->resolution_note }}</p>
                        @else
                            <p class="text-sm text-gray-400 dark:text-gray-500 italic">No note left.</p>
                        @endif
                        @if($feedback->resolved_at)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-3">
                                Status changed {{ $feedback->resolved_at->diffForHumans() }}
                                @if($feedback->resolver) by {{ $feedback->resolver->name }}@endif
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-app-layout>
