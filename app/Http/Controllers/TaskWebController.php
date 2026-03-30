<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

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
}
