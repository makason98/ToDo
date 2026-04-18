<x-admin-layout>
    <x-slot name="title">New Announcement</x-slot>

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
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">New Announcement</h1>
            </div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to announcements
                </a>

                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">New announcement</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">An in-app notification will be sent to every user matching the selected audience.</p>

                <form id="announcement-form" method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-6"
                    x-data="{
                        audience: '{{ old('audience', 'all') }}',
                        counts: @js($previewCounts),
                        labels: @js(\App\Models\Announcement::AUDIENCES),
                        confirmOpen: false,
                    }"
                    @submit.prevent="confirmOpen = true">
                    @csrf

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}" required maxlength="255"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                            <x-input-error :messages="$errors->get('title')" class="mt-1" />
                        </div>

                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
                            <textarea id="body" name="body" rows="6" required maxlength="5000"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">{{ old('body') }}</textarea>
                            <x-input-error :messages="$errors->get('body')" class="mt-1" />
                        </div>

                        <div>
                            <label for="audience" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Audience</label>
                            <select id="audience" name="audience" x-model="audience"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                @foreach(\App\Models\Announcement::AUDIENCES as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                This will reach
                                <span class="font-semibold text-red-500" x-text="counts[audience]"></span>
                                user(s).
                            </p>
                            <x-input-error :messages="$errors->get('audience')" class="mt-1" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Send announcement
                        </button>
                    </div>

                    <!-- Confirmation modal (teleported to body to escape any transformed parent) -->
                    <template x-teleport="body">
                        <div x-show="confirmOpen" x-cloak
                             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                             class="fixed inset-0 z-50 flex items-center justify-center p-4"
                             @keydown.escape.window="confirmOpen = false">
                            <div class="absolute inset-0 bg-black/40" @click="confirmOpen = false"></div>

                            <div x-show="confirmOpen"
                                 x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                 class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6 z-10 mx-auto">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Send this announcement?</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            It will be delivered to
                                            <span class="font-semibold text-red-500" x-text="counts[audience]"></span>
                                            user(s) in the
                                            <span class="font-semibold text-gray-700 dark:text-gray-300" x-text="labels[audience]"></span>
                                            group. This cannot be undone.
                                        </p>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-3 mt-6">
                                    <button type="button" @click="confirmOpen = false"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                    <button type="button" @click="confirmOpen = false; document.getElementById('announcement-form').submit()"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        Send
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </form>
            </div>
        </main>
    </div>
</x-admin-layout>
