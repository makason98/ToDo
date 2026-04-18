<x-app-layout>
    <x-slot name="title">{{ $task->title }}</x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6 sm:py-8"
         x-data="{ confirmOpen: false, confirmAction: null, confirmTitle: '', confirmMessage: '' }">
        <!-- Back button -->
        <button onclick="if(document.referrer && !document.referrer.includes('/tasks/{{ $task->id }}')) { history.back(); } else { window.location='{{ route('tasks.index') }}'; }"
            class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </button>

        <!-- Task header -->
        <div class="flex items-start gap-3" x-data="{ editing: false }">
            <!-- Toggle -->
            <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="mt-1">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors
                    {{ $task->completed ? 'bg-gray-400 border-gray-400' : ($task->priority === 'high' ? 'border-red-400 hover:bg-red-50 dark:hover:bg-red-900/20' : ($task->priority === 'low' ? 'border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20' : 'border-orange-400 hover:bg-orange-50 dark:hover:bg-orange-900/20')) }}">
                    @if($task->completed)
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    @endif
                </button>
            </form>

            <div class="flex-1">
                <!-- View mode -->
                <div x-show="!editing" @click="editing = true" class="cursor-pointer">
                    <h1 class="text-xl font-bold {{ $task->completed ? 'line-through text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-gray-100' }}">
                        {{ $task->title }}
                    </h1>
                    @if($task->description)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ $task->description }}</p>
                    @else
                        <p class="mt-2 text-sm text-gray-400 dark:text-gray-500">Click to add description...</p>
                    @endif
                </div>

                <!-- Edit mode -->
                <form x-show="editing" method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-3">
                    @csrf
                    @method('PUT')
                    <input type="text" name="title" value="{{ $task->title }}"
                        class="w-full text-xl font-bold border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                    <textarea name="description" rows="3" placeholder="Description"
                        class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500 resize-none bg-white dark:bg-gray-700 dark:text-gray-100">{{ $task->description }}</textarea>
                    <div class="flex flex-wrap items-center gap-2">
                        <input type="text" name="due_date" x-datepicker value="{{ $task->due_date?->format('Y-m-d') }}" placeholder="Due date"
                            class="text-xs border border-gray-200 dark:border-gray-600 rounded-md px-2 py-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100" />
                        <select name="priority"
                            class="text-xs border border-gray-200 dark:border-gray-600 rounded-md pl-2 pr-7 py-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                            <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        <div x-data="{ rec: '{{ $task->recurrence ?? 'none' }}' }" class="flex items-center gap-1">
                            <select name="recurrence" x-model="rec"
                                class="text-xs border border-gray-200 dark:border-gray-600 rounded-md pl-2 pr-7 py-1 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 dark:text-gray-100">
                                <option value="none">No repeat</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="custom">Custom</option>
                            </select>
                            <template x-if="rec === 'custom'">
                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-gray-400">every</span>
                                    <input type="number" name="recurrence_interval" min="2" value="{{ $task->recurrence_interval ?? 2 }}"
                                        class="text-xs border border-gray-200 dark:border-gray-600 rounded-md px-1.5 py-1 w-12 bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-red-500 focus:border-red-500" />
                                    <span class="text-xs text-gray-400">days</span>
                                </div>
                            </template>
                        </div>
                        @if($labels->count() > 0)
                            <div class="relative" x-data="{ showLabels: false }">
                                <button type="button" @click="showLabels = !showLabels"
                                    class="inline-flex items-center gap-1 text-xs border border-gray-200 dark:border-gray-600 rounded-md px-2 py-1 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Labels
                                </button>
                                <div x-show="showLabels" @click.away="showLabels = false"
                                     class="absolute top-full left-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg p-2 w-48 z-10">
                                    @foreach($labels as $label)
                                        <label class="flex items-center gap-2 px-2 py-1.5 text-xs rounded hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                            <input type="checkbox" name="label_ids[]" value="{{ $label->id }}"
                                                {{ $task->labels->contains($label->id) ? 'checked' : '' }}
                                                class="rounded border-gray-300 dark:border-gray-600 text-red-500 focus:ring-red-500">
                                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $label->color }}"></span>
                                            <span class="text-gray-700 dark:text-gray-300">{{ $label->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="flex-1"></div>
                        <button type="button" @click="editing = false" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 px-3 py-1">Cancel</button>
                        <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg font-medium transition-colors">Save</button>
                    </div>
                </form>
            </div>

            <!-- Delete task -->
            <form method="POST" action="{{ route('tasks.destroy', $task) }}" x-ref="deleteTaskForm">
                @csrf
                @method('DELETE')
                <button type="button" @click="confirmTitle = 'Delete task?'; confirmMessage = 'This task and all its comments will be permanently deleted.'; confirmAction = $refs.deleteTaskForm; confirmOpen = true"
                    class="text-gray-300 dark:text-gray-600 hover:text-red-500 transition-colors mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Task meta -->
        <div class="flex flex-wrap items-center gap-4 mt-4 ml-9 text-xs text-gray-400 dark:text-gray-500">
            @if($task->due_date)
                <span class="flex items-center gap-1 {{ $task->due_date->isPast() && !$task->completed ? 'text-red-500' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $task->due_date->format('M d, Y') }}
                </span>
            @endif
            <span class="flex items-center gap-1">
                <span class="w-2 h-2 rounded-full {{ $task->priority === 'high' ? 'bg-red-400' : ($task->priority === 'low' ? 'bg-blue-400' : 'bg-orange-400') }}"></span>
                {{ ucfirst($task->priority) }} priority
            </span>
            @if($task->isRecurring())
                <span class="flex items-center gap-1 text-blue-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ $task->recurrence === 'custom' ? 'Every ' . $task->recurrence_interval . ' days' : ucfirst($task->recurrence) }}
                </span>
            @endif
            @foreach($task->labels as $label)
                <span class="text-xs rounded-full px-2 py-0.5 text-white" style="background-color: {{ $label->color }}">{{ $label->name }}</span>
            @endforeach
        </div>

        <!-- Subtasks / Checklist -->
        <div class="mt-6 ml-0 sm:ml-9">
            @php
                $subtaskTotal = $task->subtasks->count();
                $subtaskDone = $task->subtasks->where('completed', true)->count();
            @endphp

            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Sub-tasks
                    @if($subtaskTotal > 0)
                        <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">{{ $subtaskDone }}/{{ $subtaskTotal }}</span>
                    @endif
                </h3>
            </div>

            <!-- Progress bar -->
            @if($subtaskTotal > 0)
                <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full mb-3">
                    <div class="h-1.5 bg-green-500 rounded-full transition-all" style="width: {{ ($subtaskDone / $subtaskTotal) * 100 }}%"></div>
                </div>
            @endif

            <!-- Subtask list -->
            <div class="space-y-1">
                @foreach($task->subtasks as $subtask)
                    <div class="flex items-center gap-2 group py-1">
                        <!-- Toggle checkbox -->
                        <form method="POST" action="{{ route('subtasks.toggle', $subtask) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="shrink-0 w-4 h-4 rounded border flex items-center justify-center transition-colors
                                {{ $subtask->completed ? 'bg-green-500 border-green-500' : 'border-gray-300 dark:border-gray-600 hover:border-green-400' }}">
                                @if($subtask->completed)
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </button>
                        </form>

                        <!-- Title -->
                        <span class="flex-1 text-sm {{ $subtask->completed ? 'line-through text-gray-400 dark:text-gray-500' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $subtask->title }}
                        </span>

                        <!-- Delete -->
                        <form method="POST" action="{{ route('subtasks.destroy', $subtask) }}" class="opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-300 dark:text-gray-600 hover:text-red-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Add subtask form -->
            <form method="POST" action="{{ route('subtasks.store', $task) }}" class="flex items-center gap-2 mt-2">
                @csrf
                <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <input type="text" name="title" placeholder="Add sub-task" required
                    class="flex-1 text-sm border-0 border-b border-transparent focus:border-gray-300 dark:focus:border-gray-600 p-0 pb-1 focus:ring-0 bg-transparent text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500" />
                <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium shrink-0">Add</button>
            </form>
        </div>

        <!-- Task attachments -->
        @if($task->attachments->count() > 0)
            <div class="mt-4 ml-0 sm:ml-9">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attachments</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($task->attachments as $attachment)
                        @if($attachment->isImage())
                            <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $attachment->path) }}" alt="{{ $attachment->filename }}"
                                     class="h-20 rounded-lg border border-gray-200 dark:border-gray-600" />
                            </a>
                        @elseif($attachment->isAudio())
                            <div>
                                <audio controls class="h-8">
                                    <source src="{{ asset('storage/' . $attachment->path) }}" type="{{ $attachment->mime_type }}">
                                </audio>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $attachment->filename }}</p>
                            </div>
                        @else
                            <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank"
                               class="inline-flex items-center gap-2 text-xs text-red-500 hover:text-red-600 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                {{ $attachment->filename }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Comments section -->
        <div class="mt-8 ml-0 sm:ml-9">
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Comments ({{ $task->comments->count() }})</h3>

            <!-- Comment list -->
            @foreach($task->comments as $comment)
                <div class="mb-4 bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <span class="text-xs font-medium text-red-600 dark:text-red-400">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $comment->user->name }}</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        @if($comment->user_id === auth()->id())
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" x-ref="deleteComment{{ $comment->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="confirmTitle = 'Delete comment?'; confirmMessage = 'This comment will be permanently deleted.'; confirmAction = $refs.deleteComment{{ $comment->id }}; confirmOpen = true"
                                    class="text-gray-300 dark:text-gray-600 hover:text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($comment->body)
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $comment->body }}</p>
                    @endif

                    <!-- Attachments -->
                    @foreach($comment->attachments as $attachment)
                        <div class="mt-2">
                            @if($attachment->isImage())
                                <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $attachment->path) }}" alt="{{ $attachment->filename }}"
                                         class="max-w-xs rounded-lg border border-gray-200 dark:border-gray-600" />
                                </a>
                            @elseif($attachment->isAudio())
                                <audio controls class="w-full max-w-sm">
                                    <source src="{{ asset('storage/' . $attachment->path) }}" type="{{ $attachment->mime_type }}">
                                </audio>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $attachment->filename }}</p>
                            @else
                                <a href="{{ asset('storage/' . $attachment->path) }}" target="_blank"
                                   class="inline-flex items-center gap-2 text-sm text-red-500 hover:text-red-600 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    {{ $attachment->filename }}
                                    <span class="text-xs text-gray-400 dark:text-gray-500">({{ number_format($attachment->size / 1024, 1) }} KB)</span>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach

            <!-- Add comment form -->
            <div x-data="commentForm()" class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white dark:bg-gray-800">
                <form method="POST" action="{{ route('comments.store', $task) }}" enctype="multipart/form-data">
                    @csrf
                    <textarea name="body" x-model="body" placeholder="Comment" rows="3"
                        class="w-full text-sm border-0 px-4 pt-3 pb-2 focus:ring-0 placeholder-gray-400 dark:placeholder-gray-500 resize-none bg-transparent dark:text-gray-100"></textarea>

                    <!-- Audio recording preview -->
                    <template x-if="audioBlob">
                        <div class="px-4 pb-2">
                            <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700 rounded-lg px-3 py-2">
                                <audio :src="audioUrl" controls class="h-8 flex-1"></audio>
                                <button type="button" @click="removeAudio()" class="text-gray-400 hover:text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- File preview -->
                    <template x-if="files.length > 0">
                        <div class="px-4 pb-2 flex flex-wrap gap-2">
                            <template x-for="(file, index) in files" :key="index">
                                <span class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded px-2 py-1 text-xs text-gray-600 dark:text-gray-300">
                                    <span x-text="file.name"></span>
                                    <button type="button" @click="removeFile(index)" class="text-gray-400 hover:text-red-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </span>
                            </template>
                        </div>
                    </template>

                    <input type="file" name="attachments[]" multiple x-ref="fileInput" class="hidden" @change="handleFiles($event)" />
                    <input type="file" name="attachments[]" x-ref="audioInput" class="hidden" accept="audio/*" />

                    <div class="flex items-center gap-1 px-3 py-2 border-t border-gray-200 dark:border-gray-600">
                        <!-- File attach -->
                        <button type="button" @click="$refs.fileInput.click()" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Attach file">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </button>

                        <!-- Audio record -->
                        <button type="button" @click="toggleRecording()" class="p-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                :class="recording ? 'text-red-500 animate-pulse' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300'" title="Record audio">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                        </button>

                        <!-- Emoji picker -->
                        <div class="relative" x-data="{ showEmoji: false }">
                            <button type="button" @click="showEmoji = !showEmoji" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Emoji">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                            <div x-show="showEmoji" @click.away="showEmoji = false"
                                 class="absolute bottom-full left-0 mb-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg p-2 grid grid-cols-8 gap-1 w-72 max-h-48 overflow-y-auto z-10">
                                @foreach(['😀','😂','😍','🥰','😎','🤔','👍','👎','❤️','🔥','✅','⭐','🎉','💪','🙏','😊','😢','😡','🤯','💡','📝','🎯','🚀','⏰','📌','✨','💬','👏'] as $emoji)
                                    <button type="button" @click="body += '{{ $emoji }}'; showEmoji = false"
                                        class="text-xl hover:bg-gray-100 dark:hover:bg-gray-700 rounded p-1 text-center">{{ $emoji }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex-1"></div>

                        <button type="button" @click="$el.closest('form').reset(); body = ''; files = []; removeAudio()"
                            class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 px-3 py-1">Cancel</button>
                        <button type="submit"
                            class="text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg font-medium transition-colors">Comment</button>
                    </div>
                </form>
            </div>
            <x-input-error :messages="$errors->get('body')" class="mt-1" />
        </div>

        <x-confirm-modal />
    </div>

    @push('scripts')
    <script>
    function commentForm() {
        return {
            body: '',
            files: [],
            recording: false,
            mediaRecorder: null,
            audioChunks: [],
            audioBlob: null,
            audioUrl: null,

            handleFiles(event) {
                this.files = Array.from(event.target.files);
            },

            removeFile(index) {
                this.files.splice(index, 1);
                const dt = new DataTransfer();
                this.files.forEach(f => dt.items.add(f));
                this.$refs.fileInput.files = dt.files;
            },

            async toggleRecording() {
                if (this.recording) {
                    this.mediaRecorder.stop();
                    this.recording = false;
                    return;
                }

                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    this.audioChunks = [];
                    this.mediaRecorder = new MediaRecorder(stream);

                    this.mediaRecorder.ondataavailable = (e) => {
                        this.audioChunks.push(e.data);
                    };

                    this.mediaRecorder.onstop = () => {
                        this.audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                        this.audioUrl = URL.createObjectURL(this.audioBlob);

                        const file = new File([this.audioBlob], 'recording.webm', { type: 'audio/webm' });
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        this.$refs.audioInput.files = dt.files;

                        stream.getTracks().forEach(t => t.stop());
                    };

                    this.mediaRecorder.start();
                    this.recording = true;
                } catch (err) {
                    alert('Could not access microphone.');
                }
            },

            removeAudio() {
                this.audioBlob = null;
                this.audioUrl = null;
                this.$refs.audioInput.value = '';
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
