<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskWebController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tasks', [TaskWebController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskWebController::class, 'create'])->name('tasks.create');
Route::get('/tasks/{task}', [TaskWebController::class, 'show'])->name('tasks.show');

// Simple health endpoint for platform health checks
Route::get('/health', function () {
    return response('OK', 200);
});
