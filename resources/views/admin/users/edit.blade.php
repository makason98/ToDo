<x-admin-layout>
    <x-slot name="title">Edit {{ $user->name }}</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('admin.partials.sidebar', ['currentPage' => 'users'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate">Edit {{ $user->name }}</h1>
            </div>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <!-- Header -->
                <div class="mb-6">
                    <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to user
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">Edit user</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Update profile, password, verification, and admin role.</p>
                </div>

                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Profile -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Profile</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Leave blank to keep current password.</p>
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New password</label>
                                <input id="password" type="password" name="password" autocomplete="new-password"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm new password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                            </div>
                        </div>
                    </div>

                    <!-- Verification + admin role -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Permissions</h2>
                        <div class="space-y-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="verified" value="0">
                                <input type="checkbox" name="verified" value="1"
                                    {{ old('verified', $user->email_verified_at ? '1' : '0') === '1' ? 'checked' : '' }}
                                    class="w-4 h-4 rounded text-red-500 focus:ring-red-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Email verified</span>
                            </label>

                            <div>
                                <label for="admin_role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin role</label>
                                <select id="admin_role" name="admin_role"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                    <option value="">Not an admin (regular user)</option>
                                    @foreach(auth()->user()->assignableRoles() as $value => $label)
                                        <option value="{{ $value }}" {{ old('admin_role', $user->admin_role) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('admin_role')" class="mt-1" />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Choose "Not an admin" to revoke admin access.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.users.show', $user) }}"
                            class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</x-admin-layout>
