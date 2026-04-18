<div data-id="{{ $task->id }}" class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-3 hover:shadow-sm transition-shadow cursor-grab active:cursor-grabbing">
    <a href="{{ route('tasks.show', $task) }}" class="block">
        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 {{ $task->completed ? 'line-through text-gray-400 dark:text-gray-500' : '' }}">
            {{ $task->title }}
        </p>
        @if($task->description)
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 truncate">{{ $task->description }}</p>
        @endif
        <div class="flex flex-wrap items-center gap-2 mt-2">
            @if($task->due_date)
                <span class="text-xs {{ $task->due_date->isPast() && !$task->completed ? 'text-red-500' : 'text-gray-400 dark:text-gray-500' }}">
                    {{ $task->due_date->format('M d') }}
                </span>
            @endif
            <span class="w-2 h-2 rounded-full {{ $task->priority === 'high' ? 'bg-red-400' : ($task->priority === 'low' ? 'bg-blue-400' : 'bg-orange-400') }}"></span>
            @foreach($task->labels as $label)
                <span class="text-xs rounded-full px-1.5 py-0.5 text-white leading-none" style="background-color: {{ $label->color }}">{{ $label->name }}</span>
            @endforeach
        </div>
    </a>
    <!-- Status change -->
    <form method="POST" action="{{ route('tasks.updateStatus', $task) }}" class="mt-2">
        @csrf
        @method('PATCH')
        <select name="status" onchange="this.form.submit()"
            class="w-full text-xs border border-gray-200 dark:border-gray-600 rounded-md pl-2 pr-6 py-1 bg-white dark:bg-gray-800 dark:text-gray-300 focus:ring-red-500 focus:border-red-500">
            <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
            <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
        </select>
    </form>
</div>
