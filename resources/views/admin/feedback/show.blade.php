<x-admin-layout>
    <x-slot name="title">{{ $feedback->title }}</x-slot>

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
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate">{{ $feedback->title }}</h1>
            </div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <a href="{{ route('admin.feedback.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to feedback
                </a>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">{{ session('status') }}</div>
                @endif

                <!-- Request -->
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
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                    @if($feedback->status === 'open') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                                    @elseif($feedback->status === 'in_progress') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300
                                    @elseif($feedback->status === 'done') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300
                                    @else bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400
                                    @endif">{{ $feedback->statusLabel() }}</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ $feedback->typeLabel() }} ·
                                @if($feedback->user)
                                    from <a href="{{ route('admin.users.show', $feedback->user) }}" class="text-red-500 hover:text-red-600">{{ $feedback->user->name }}</a>
                                    <span class="text-gray-400">({{ $feedback->user->email }})</span>
                                @else
                                    <span class="text-gray-400">user deleted</span>
                                @endif
                                · submitted {{ $feedback->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $feedback->body }}</div>
                </div>

                <!-- Resolution form -->
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Update status</h3>
                    <form method="POST" action="{{ route('admin.feedback.update', $feedback) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select id="status" name="status" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                @foreach(\App\Models\FeedbackRequest::STATUSES as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $feedback->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-1" />
                        </div>

                        <div>
                            <label for="resolution_note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Note to user (optional)</label>
                            <textarea id="resolution_note" name="resolution_note" rows="4" maxlength="5000" placeholder="Explain the resolution, or why it was rejected..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">{{ old('resolution_note', $feedback->resolution_note) }}</textarea>
                            <x-input-error :messages="$errors->get('resolution_note')" class="mt-1" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">The submitter will see this on their feedback page and receive a notification when the status changes.</p>
                        </div>

                        @if($feedback->resolved_at)
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Last change: {{ $feedback->resolved_at->diffForHumans() }}
                                @if($feedback->resolver) by {{ $feedback->resolver->name }}@endif
                            </p>
                        @endif

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <a href="{{ route('admin.feedback.index') }}" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Cancel</a>
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</x-admin-layout>
