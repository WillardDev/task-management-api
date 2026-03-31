@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl p-6 shadow">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold">Task Report</h2>
                <a href="{{ route('tasks.index') }}" class="text-sm text-blue-600">Back to board</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div id="calendar" class="p-4"></div>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">Tasks on <span id="selectedDate">{{ $currentDate }}</span></h3>
                    <div id="summaryContainer" class="mb-3">
                        <div class="flex items-center justify-between">
                            <div class="text-xs text-gray-500">Daily summary (from API)</div>
                            <div class="flex items-center space-x-2">
                                <button id="viewNeat" class="px-2 py-1 text-xs bg-gray-100 rounded">Neat</button>
                                <button id="viewJson" class="px-2 py-1 text-xs bg-white border rounded">JSON</button>
                            </div>
                        </div>

                        <div id="summaryNeat" class="mt-2 bg-white p-3 rounded text-sm overflow-auto">
                            <div class="text-sm text-gray-600">No date selected yet.</div>
                        </div>

                        <pre id="summaryJson" class="mt-2 bg-gray-50 p-3 rounded text-sm overflow-auto hidden">{
                            "date": "{{ $currentDate }}",
                            "summary": {
                                "high": {"pending": 0, "in_progress": 0, "done": 0},
                                "medium": {"pending": 0, "in_progress": 0, "done": 0},
                                "low": {"pending": 0, "in_progress": 0, "done": 0}
                            }
                        }</pre>
                    </div>
                    <div id="tasksList" class="space-y-3">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const tasksByDate = @json($tasksByDate);
            const today = new Date('{{ $currentDate }}');

            function startOfMonth(d) {
                return new Date(d.getFullYear(), d.getMonth(), 1);
            }

            function daysInMonth(d) {
                return new Date(d.getFullYear(), d.getMonth() + 1, 0).getDate();
            }

            function renderCalendar(current) {
                const cal = document.getElementById('calendar');
                cal.innerHTML = '';

                const monthStart = startOfMonth(current);
                const monthName = monthStart.toLocaleString(undefined, {
                    month: 'long'
                });
                const year = monthStart.getFullYear();

                const header = document.createElement('div');
                header.className = 'flex items-center justify-between mb-2';
                header.innerHTML = `<button id="prevMonth" class="px-2 py-1 bg-gray-100 rounded">&lt;</button>
                            <div class="font-medium">${monthName} ${year}</div>
                            <button id="nextMonth" class="px-2 py-1 bg-gray-100 rounded">&gt;</button>`;
                cal.appendChild(header);

                document.getElementById('prevMonth').addEventListener('click', () => {
                    current.setMonth(current.getMonth() - 1);
                    renderCalendar(current);
                });
                document.getElementById('nextMonth').addEventListener('click', () => {
                    current.setMonth(current.getMonth() + 1);
                    renderCalendar(current);
                });

                const grid = document.createElement('div');
                grid.className = 'grid grid-cols-7 gap-1';

                ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(d => {
                    const el = document.createElement('div');
                    el.className = 'text-xs text-center font-semibold';
                    el.textContent = d;
                    grid.appendChild(el);
                });

                const firstDay = new Date(monthStart.getFullYear(), monthStart.getMonth(), 1).getDay();
                for (let i = 0; i < firstDay; i++) {
                    const empty = document.createElement('div');
                    empty.className = 'p-3';
                    grid.appendChild(empty);
                }

                const totalDays = daysInMonth(current);
                for (let day = 1; day <= totalDays; day++) {
                    const dt = new Date(current.getFullYear(), current.getMonth(), day);
                    const ymd = dt.toISOString().slice(0, 10);

                    const cell = document.createElement('div');
                    cell.className = 'p-3 border rounded text-sm h-20 overflow-hidden relative';

                    const now = new Date();
                    now.setHours(0, 0, 0, 0);
                    if (dt < now) {
                        cell.classList.add('bg-gray-50', 'text-gray-400', 'cursor-not-allowed');
                    } else {
                        cell.classList.add('bg-white', 'cursor-pointer', 'hover:bg-blue-50');
                    }

                    const dayNum = document.createElement('div');
                    dayNum.className = 'font-medium';
                    dayNum.textContent = day;
                    cell.appendChild(dayNum);

                    if (tasksByDate[ymd] && tasksByDate[ymd].length > 0) {
                        const badge = document.createElement('div');
                        badge.className = 'absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-0.5 rounded-full';
                        badge.textContent = tasksByDate[ymd].length;
                        cell.appendChild(badge);
                    }

                    if (!(dt < now)) {
                        cell.addEventListener('click', () => selectDate(ymd));
                    }

                    grid.appendChild(cell);
                }

                cal.appendChild(grid);

                selectDate('{{ $currentDate }}');
            }

            function selectDate(ymd) {
                document.getElementById('selectedDate').textContent = ymd;
                const list = document.getElementById('tasksList');
                list.innerHTML = '';

                const tasks = tasksByDate[ymd] || [];

                const summaryEl = document.getElementById('summaryJson');
                fetch(`/api/tasks/report?date=${ymd}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(json => {
                        if (summaryEl) summaryEl.textContent = JSON.stringify(json, null, 4);
                        const neatEl = document.getElementById('summaryNeat');
                        if (neatEl) renderNeatSummary(json, neatEl);
                    })
                    .catch(err => {
                        if (summaryEl) summaryEl.textContent = JSON.stringify({
                            error: 'Failed to fetch summary'
                        });
                    });

                if (tasks.length === 0) {
                    list.innerHTML = '<div class="text-sm text-gray-500">No tasks for this date.</div>';
                    return;
                }

                tasks.forEach(t => {
                    const item = document.createElement('div');
                    item.className = 'p-3 border rounded';
                    item.innerHTML =
                        `<div class="font-semibold">${t.title}</div>
                                      <div class="text-xs text-gray-500">Priority: ${t.priority} — Status: ${t.status}</div>`;
                    list.appendChild(item);
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                const viewNeat = document.getElementById('viewNeat');
                const viewJson = document.getElementById('viewJson');
                const summaryJson = document.getElementById('summaryJson');
                const summaryNeat = document.getElementById('summaryNeat');

                function showNeat() {
                    if (summaryJson) summaryJson.classList.add('hidden');
                    if (summaryNeat) summaryNeat.classList.remove('hidden');
                    if (viewNeat) viewNeat.classList.add('bg-gray-100');
                    if (viewJson) viewJson.classList.remove('bg-gray-100');
                }

                function showJson() {
                    if (summaryJson) summaryJson.classList.remove('hidden');
                    if (summaryNeat) summaryNeat.classList.add('hidden');
                    if (viewJson) viewJson.classList.add('bg-gray-100');
                    if (viewNeat) viewNeat.classList.remove('bg-gray-100');
                }

                if (viewNeat) viewNeat.addEventListener('click', showNeat);
                if (viewJson) viewJson.addEventListener('click', showJson);

                // default to neat view
                showNeat();
            });

            function renderNeatSummary(json, container) {
                if (!json || !json.summary) {
                    container.innerHTML = '<div class="text-sm text-gray-500">No summary available.</div>';
                    return;
                }

                const s = json.summary;
                let html = '<div class="grid grid-cols-1 gap-2">';
                ['high', 'medium', 'low'].forEach(level => {
                    const counts = s[level] || {
                        pending: 0,
                        in_progress: 0,
                        done: 0
                    };
                    html += `<div class="p-2 border rounded">
                        <div class="font-semibold">${level.charAt(0).toUpperCase() + level.slice(1)}</div>
                        <div class="text-xs text-gray-600">Pending: ${counts.pending} · In Progress: ${counts.in_progress} · Done: ${counts.done}</div>
                    </div>`;
                });
                html += '</div>';
                container.innerHTML = html;
            }

            document.addEventListener('DOMContentLoaded', () => {
                const current = new Date('{{ $currentDate }}');
                renderCalendar(current);
            });
        </script>
    @endpush
@endsection
