@extends('layouts.app')

@section('content')
<div class="flex justify-between items-end mb-10">
    <div>
        <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Task Management</h1>
    </div>
    <a href="{{ route('tasks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-lg shadow-blue-200">
        <i class="fas fa-plus mr-2"></i> New Task
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

    <div class="space-y-6">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest px-2">Pending</h3>
        @foreach($tasks->where('status', 'pending') as $task)
            @include('tasks.partials.card', ['task' => $task])
        @endforeach
    </div>

    <div class="space-y-6">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest px-2">In Progress</h3>
        @foreach($tasks->where('status', 'in_progress') as $task)
            @include('tasks.partials.card', ['task' => $task])
        @endforeach
    </div>

    <div class="space-y-6">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest px-2">Done</h3>
        @foreach($tasks->where('status', 'done') as $task)
            @include('tasks.partials.card', ['task' => $task])
        @endforeach
    </div>
</div>

@push('scripts')
<style>
    .task-card { @apply bg-white rounded-2xl p-6 shadow-sm border border-gray-50 hover:shadow-xl transition-all duration-300 relative; }
    .priority-high-bar { @apply border-l-4 border-red-500; }
    .priority-medium-bar { @apply border-l-4 border-yellow-400; }
    .priority-low-bar { @apply border-l-4 border-blue-400; }
</style>
<script>
    async function updateStatus(taskId, newStatus) {
        const response = await fetch(`/api/tasks/${taskId}/status`, {
            method: 'PATCH',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
            body: JSON.stringify({ status: newStatus })
        });
        if (response.ok) window.location.reload();
    }
</script>
@endpush
@endsection
