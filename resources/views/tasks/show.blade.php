@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('tasks.index') }}" class="group inline-flex items-center text-sm font-bold text-gray-400 hover:text-blue-600 transition-colors">
            <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center mr-3 group-hover:shadow-md transition-all">
                <i class="fas fa-chevron-left text-xs"></i>
            </div>
            Back to Board
        </a>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-900/5 overflow-hidden border border-gray-50">
        <div class="h-3 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

        <div class="p-10 md:p-14">
            <div class="flex flex-wrap gap-3 mb-8">
                <span class="priority-{{ $task->priority }} px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm">
                    <i class="fas fa-circle mr-2 text-[8px]"></i> {{ $task->priority }} Priority
                </span>
                <span class="status-{{ $task->status }} px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm">
                    {{ str_replace('_', ' ', $task->status) }}
                </span>
            </div>

            <h2 class="text-5xl font-black text-slate-900 mb-10 tracking-tight leading-tight">
                {{ $task->title }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                <div class="bg-slate-50 rounded-3xl p-6 border border-gray-100/50">
                    <div class="flex items-center space-x-4 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="far fa-calendar-alt text-lg"></i>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Deadline</p>
                    </div>
                    <p class="text-xl font-bold text-slate-800 ml-14">
                        {{ $task->due_date->format('M d, Y') }}
                    </p>
                </div>

                <div class="bg-slate-50 rounded-3xl p-6 border border-gray-100/50">
                    <div class="flex items-center space-x-4 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600">
                            <i class="far fa-clock text-lg"></i>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Time Remaining</p>
                    </div>
                    <p class="text-xl font-bold text-slate-800 ml-14">
                        {{ $task->due_date->diffForHumans() }}
                    </p>
                </div>
            </div>

            <div class="pt-10 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 border border-white shadow-sm flex items-center justify-center font-bold text-slate-500">
                        {{ substr($task->title, 0, 1) }}
                    </div>
                </div>

                <div class="flex w-full sm:w-auto gap-3">
                    @if($task->status === 'pending')
                        <button onclick="updateStatus({{ $task->id }}, 'in_progress')"
                                class="flex-1 sm:flex-none bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">
                            <i class="fas fa-play mr-2"></i> Start Task
                        </button>
                    @elseif($task->status === 'in_progress')
                        <button onclick="updateStatus({{ $task->id }}, 'done')"
                                class="flex-1 sm:flex-none bg-green-500 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-green-200 hover:bg-green-600 transition-all">
                            <i class="fas fa-check mr-2"></i> Complete
                        </button>
                    @elseif($task->status === 'done')
                        <button onclick="deleteTask({{ $task->id }})"
                                class="flex-1 sm:flex-none bg-red-50 text-red-500 px-8 py-4 rounded-2xl font-bold hover:bg-red-500 hover:text-white transition-all">
                            <i class="fas fa-trash-alt mr-2"></i> Delete
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    /* Status & Priority Theming */
    .priority-high { @apply bg-red-50 text-red-600; }
    .priority-medium { @apply bg-yellow-50 text-yellow-600; }
    .priority-low { @apply bg-blue-50 text-blue-600; }

    .status-pending { @apply bg-slate-100 text-slate-600; }
    .status-in_progress { @apply bg-yellow-400 text-white; }
    .status-done { @apply bg-green-500 text-white; }
</style>

<script>
    async function updateStatus(taskId, newStatus) {
        try {
            const response = await fetch(`/api/tasks/${taskId}/status`, {
                method: 'PATCH',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
                body: JSON.stringify({ status: newStatus })
            });
            if (response.ok) window.location.reload();
        } catch (error) {
            alert('Error updating task: ' + error.message);
        }
    }

    async function deleteTask(taskId) {
        if (!confirm('Are you sure you want to delete this task?')) return;
        try {
            const response = await fetch(`/api/tasks/${taskId}`, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': token}
            });
            if (response.ok) window.location.href = '{{ route("tasks.index") }}';
        } catch (error) {
            alert('Error deleting task: ' + error.message);
        }
    }
</script>
@endpush
@endsection
