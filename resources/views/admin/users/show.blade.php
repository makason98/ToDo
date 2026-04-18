<x-admin-layout>
    <x-slot name="title">{{ $user->name }}</x-slot>

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
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</h1>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to users
                    </a>
                    @if(auth()->user()->canEditUser($user))
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Edit
                        </a>
                    @endif
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Profile card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center text-2xl font-semibold shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h2>
                                @if($user->isAdmin())
                                    <span class="text-xs px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded font-semibold">{{ $user->adminRoleLabel() }}</span>
                                @endif
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 rounded">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Unverified
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 break-all">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->tasks_count }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total tasks</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-green-500">{{ $completedTasksCount }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Completed</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-blue-500">{{ $activeTasksCount }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Active</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                        <p class="text-2xl font-bold text-purple-500">{{ $user->labels_count }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Labels</p>
                    </div>
                </div>

                <!-- Account details -->
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Account details</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">User ID</dt>
                            <dd class="text-gray-900 dark:text-gray-100 mt-0.5">#{{ $user->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Admin role</dt>
                            <dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ $user->adminRoleLabel() ?? 'Not an admin' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Email verified at</dt>
                            <dd class="text-gray-900 dark:text-gray-100 mt-0.5">
                                {{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Registered</dt>
                            <dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ $user->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Last updated</dt>
                            <dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ $user->updated_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </main>
    </div>
</x-admin-layout>
