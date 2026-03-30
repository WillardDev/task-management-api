<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('tasks')->group(function () {
    Route::get('/report', [TaskController::class, 'report']);
    Route::post('/', [TaskController::class, 'store']);
    Route::get('/', [TaskController::class, 'index']);
    Route::get('/{id}', [TaskController::class, 'show']);
    Route::patch('/{id}/status', [TaskController::class, 'updateStatus']);
    Route::delete('/{id}', [TaskController::class, 'destroy']);
});
