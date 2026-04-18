<x-admin-layout>
    <x-slot name="title">Settings</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('admin.partials.sidebar', ['currentPage' => 'settings'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100">Settings</h1>
            </div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Platform settings</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Root admins only. Changes apply immediately to every user.</p>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Registration -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Sign-ups</h2>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="hidden" name="registration_open" value="0">
                            <input type="checkbox" name="registration_open" value="1" {{ $values['registration_open'] ? 'checked' : '' }}
                                class="w-4 h-4 mt-0.5 rounded text-red-500 focus:ring-red-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <span>
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registration open</span>
                                <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">When unchecked, the public register page returns "Registrations are currently closed." Existing users can still log in.</span>
                            </span>
                        </label>
                    </div>

                    <!-- Banner -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Maintenance banner</h2>
                        <label for="maintenance_banner" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Banner text (leave blank to hide)</label>
                        <textarea id="maintenance_banner" name="maintenance_banner" rows="3" maxlength="500"
                            placeholder="e.g. Scheduled maintenance Saturday 2:00 UTC — expect short downtime."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">{{ old('maintenance_banner', $values['maintenance_banner']) }}</textarea>
                        <x-input-error :messages="$errors->get('maintenance_banner')" class="mt-1" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Shown as a yellow strip at the top of every page (admin + user) when not blank.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg transition-colors">
                            Save settings
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-admin-layout>
