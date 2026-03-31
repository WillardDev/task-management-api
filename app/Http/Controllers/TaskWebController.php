<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaskWebController extends Controller
{
    public function index()
    {
        $tasks = Task::orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('due_date', 'asc')
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function report(Request $request)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::today();

        $start = $date->copy()->startOfMonth()->toDateString();
        $end = $date->copy()->endOfMonth()->toDateString();

        $tasks = Task::whereBetween('due_date', [$start, $end])->get()
            ->groupBy(function ($task) {
                return $task->due_date->format('Y-m-d');
            });

        $tasksForJs = [];
        foreach ($tasks as $day => $group) {
            $tasksForJs[$day] = $group->map(function ($t) {
                return [
                    'id' => $t->id,
                    'title' => $t->title,
                    'priority' => $t->priority,
                    'status' => $t->status,
                ];
            })->values()->toArray();
        }

        return view('tasks.report', [
            'tasksByDate' => $tasksForJs,
            'currentDate' => $date->format('Y-m-d'),
        ]);
    }
}
