<div class="task-card priority-{{ $task->priority }}-bar">
    <div class="flex justify-between items-start mb-4">
        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ $task->priority }} Priority</span>
    </div>

    <a href="{{ route('tasks.show', $task->id) }}" class="block mb-4">
        <h4 class="text-lg font-bold text-slate-800 leading-tight">{{ $task->title }}</h4>
    </a>


    <div class="flex items-center justify-between pt-4 border-t border-gray-50">
        <div class="flex -space-x-2">
            <div class="w-7 h-7 rounded-full bg-purple-500 border-2 border-white flex items-center justify-center text-[10px] text-white font-bold">JD</div>
        </div>

        <div class="flex space-x-2">
            @if($task->status === 'pending')
                <button onclick="updateStatus({{ $task->id }}, 'in_progress')" class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-colors"><i class="fas fa-play"></i></button>
            @elseif($task->status === 'in_progress')
                <button onclick="updateStatus({{ $task->id }}, 'done')" class="text-green-500 hover:bg-green-50 p-2 rounded-lg transition-colors"><i class="fas fa-check"></i></button>
            @endif
        </div>
    </div>
</div>
