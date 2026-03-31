<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create([
            'title' => $request->title,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Task::query();

        // status filter
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        // sort by priority, then by due_date ascending
        $tasks = $query
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->orderBy('due_date', 'asc')
            ->get();

        // Always return a consistent response shape with a data array (empty when no tasks)
        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'message' => 'Tasks retrieved successfully',
            'data' => TaskResource::collection($tasks),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $task = Task::find($id);

        if (! $task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Task retrieved successfully',
            'data' => new TaskResource($task),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(UpdateTaskStatusRequest $request, int $id): JsonResponse
    {
        $task = Task::find($id);

        if (! $task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $newStatus = $request->status;

        // check if status transition is valid
        if (! $task->canTransitionTo($newStatus)) {
            return response()->json([
                'message' => 'Invalid status transition',
                'current_status' => $task->status,
                'requested_status' => $newStatus,
            ], 400);
        }

        $task->update(['status' => $newStatus]);

        return response()->json([
            'message' => 'Task status updated successfully',
            'data' => new TaskResource($task),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $task = Task::find($id);
        if (! $task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        // only done tasks can be deleted
        if (! $task->canBeDeleted()) {
            return response()->json([
                'message' => 'Only tasks with status "done" can be deleted',
                'current_status' => $task->status,
            ], 403);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ], 200);
    }

    /**
     * Daily task report
     * GET /api/tasks/report?date=2026-03-28
     */
    public function report(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $date = $request->date;

        $tasks = Task::whereDate('due_date', $date)->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'date' => $date,
                'message' => 'No tasks due on this date',
                'summary' => [
                    'high' => ['pending' => 0, 'in_progress' => 0, 'done' => 0],
                    'medium' => ['pending' => 0, 'in_progress' => 0, 'done' => 0],
                    'low' => ['pending' => 0, 'in_progress' => 0, 'done' => 0],
                ],
            ], 200);
        }

        $summary = [
            'high' => ['pending' => 0, 'in_progress' => 0, 'done' => 0],
            'medium' => ['pending' => 0, 'in_progress' => 0, 'done' => 0],
            'low' => ['pending' => 0, 'in_progress' => 0, 'done' => 0],
        ];

        foreach ($tasks as $task) {
            $summary[$task->priority][$task->status]++;
        }

        return response()->json([
            'date' => $date,
            'summary' => $summary,
        ], 200);
    }
}
