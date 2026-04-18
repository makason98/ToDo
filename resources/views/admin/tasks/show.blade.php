<x-admin-layout>
    <x-slot name="title">{{ $task->title }}</x-slot>

    <div class="flex h-[calc(100vh-64px)]" x-data="{ sidebarOpen: false }">
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/30 z-20 sm:hidden" @click="sidebarOpen = false"></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed sm:static sm:translate-x-0 z-30 w-64 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-full overflow-y-auto transition-transform duration-200 ease-in-out">
            @include('admin.partials.sidebar', ['currentPage' => 'tasks'])
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sm:hidden flex items-center gap-3 p-4 border-b border-gray-200 dark:border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-500 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900 dark:text-gray-100 truncate">{{ $task->title }}</h1>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
                <a href="{{ route('admin.tasks.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition-colors mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to tasks
                </a>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 text-sm rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 break-words">{{ $task->title }}</h2>
                                @if($task->trashed())
                                    <span class="text-xs px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded">Trashed</span>
                                @endif
                                @if($task->completed)
                                    <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full">Completed</span>
                                @else
                                    <span class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full">Active</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Owned by <a href="{{ route('admin.users.show', $task->user) }}" class="text-red-500 hover:text-red-600">{{ $task->user->name }}</a>
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($task->trashed())
                                <form method="POST" action="{{ route('admin.tasks.restore', $task->id) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-lg shadow-sm">Restore</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.tasks.destroy', $task->id) }}" onsubmit="return confirm('Soft-delete this task? It can be restored later.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg shadow-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    @if($task->description)
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-4 whitespace-pre-line">{{ $task->description }}</p>
                    @endif
                </div>

                <!-- Details -->
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Details</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div><dt class="text-gray-500 dark:text-gray-400">Priority</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ ucfirst($task->priority) }}</dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Status field</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ $task->status ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Due date</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}</dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Recurrence</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ $task->recurrence ?? 'none' }}</dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Created</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ $task->created_at->format('M d, Y H:i') }}</dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Updated</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ $task->updated_at->format('M d, Y H:i') }}</dd></div>
                        @if($task->trashed())
                            <div><dt class="text-gray-500 dark:text-gray-400">Deleted at</dt><dd class="text-gray-900 dark:text-gray-100 mt-0.5">{{ $task->deleted_at->format('M d, Y H:i') }}</dd></div>
                        @endif
                    </dl>

                    @if($task->labels->count() > 0)
                        <div class="mt-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Labels</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                @foreach($task->labels as $label)
                                    <span class="inline-flex items-center gap-1.5 text-xs px-2 py-1 rounded-full" style="background-color: {{ $label->color }}20; color: {{ $label->color }}">
                                        <span class="w-2 h-2 rounded-full" style="background-color: {{ $label->color }}"></span>
                                        {{ $label->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Subtasks -->
                @if($task->subtasks->count() > 0)
                    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Subtasks ({{ $task->subtasks->count() }})</h3>
                        <ul class="space-y-2">
                            @foreach($task->subtasks as $subtask)
                                <li class="flex items-center gap-2 text-sm">
                                    <span class="w-4 h-4 rounded border border-gray-300 dark:border-gray-600 flex items-center justify-center {{ $subtask->completed ? 'bg-green-500 border-green-500' : '' }}">
                                        @if($subtask->completed)
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </span>
                                    <span class="text-gray-700 dark:text-gray-300 {{ $subtask->completed ? 'line-through text-gray-400 dark:text-gray-500' : '' }}">{{ $subtask->title }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Comments -->
                <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Comments ({{ $task->comments->count() }})</h3>
                    @forelse($task->comments as $comment)
                        <div class="py-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $comment->user->name ?? 'Unknown' }}</span>
                                <span>·</span>
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $comment->body }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-4">No comments.</p>
                    @endforelse
                </div>

                <!-- Attachments -->
                @if($task->attachments->count() > 0)
                    <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Attachments ({{ $task->attachments->count() }})</h3>
                        <ul class="space-y-2 text-sm">
                            @foreach($task->attachments as $attachment)
                                <li class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span>{{ $attachment->filename }}</span>
                                    <span class="text-xs text-gray-400">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-admin-layout>
