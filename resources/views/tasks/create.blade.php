@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-3xl p-10 shadow-xl shadow-blue-900/5">
            <h2 class="text-2xl font-black text-slate-900 mb-8">New Task Details</h2>

            <form id="taskForm" class="space-y-8">
                @csrf
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 block">Task Title</label>
                    <input type="text" id="title" name="title" required
                        class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500/20 font-medium text-slate-700"
                        placeholder="What needs to be done?">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 block">Due Date</label>
                        <input type="date" id="due_date" name="due_date" required
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500/20 font-medium text-slate-700">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 block">Priority</label>
                        <select id="priority" name="priority"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500/20 font-medium text-slate-700">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-4 pt-4">
                    <button id="submitBtn" type="submit"
                        class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-blue-200 hover:scale-[1.02] transition-transform">Create
                        Task</button>
                    <a href="{{ route('tasks.index') }}"
                        class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-bold text-center hover:bg-gray-200 transition-colors">Cancel</a>
                </div>
                <div id="formStatus" class="mt-4 text-sm text-green-600"></div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('taskForm').addEventListener('submit', async (e) => {
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                const statusEl = document.getElementById('formStatus');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
                statusEl.textContent = '';

                const formData = {
                    title: document.getElementById('title').value,
                    due_date: document.getElementById('due_date').value,
                    priority: document.getElementById('priority').value
                };

                try {
                    const response = await fetch('/api/tasks', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(formData)
                    });

                    let data = null;
                    const contentType = response.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        try {
                            data = await response.json();
                        } catch (err) {
                            data = null;
                        }
                    }

                    if (response.ok) {
                        statusEl.textContent = 'Task created — redirecting...';
                        // small delay so users see the confirmation
                        setTimeout(() => {
                            window.location.href = '{{ route('tasks.index') }}';
                        }, 700);
                        return;
                    }

                    // non-2xx responses
                    if (data && data.errors) {
                        // collect validation errors
                        let errorMsg = '';
                        Object.values(data.errors).forEach(errs => {
                            errorMsg += errs.join('\n') + '\n';
                        });
                        statusEl.classList.remove('text-green-600');
                        statusEl.classList.add('text-red-600');
                        statusEl.textContent = errorMsg;
                    } else if (data && data.message) {
                        statusEl.classList.remove('text-green-600');
                        statusEl.classList.add('text-red-600');
                        statusEl.textContent = data.message;
                    } else {
                        // fallback: try to read text body
                        let text = 'Failed to create task';
                        try {
                            text = await response.text();
                        } catch (err) {
                            /* ignore */ }
                        statusEl.classList.remove('text-green-600');
                        statusEl.classList.add('text-red-600');
                        statusEl.textContent = text;
                    }
                } catch (error) {
                    const statusEl = document.getElementById('formStatus');
                    statusEl.classList.remove('text-green-600');
                    statusEl.classList.add('text-red-600');
                    statusEl.textContent = 'Error: ' + error.message;
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            });
        </script>
    @endpush
@endsection
