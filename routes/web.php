<?php

use App\Http\Controllers\TaskWebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

/**
 * Temporary migration endpoint
 * Protect by setting MIGRATE_SECRET in your Railway environment variables and
 * visiting /migrate?s=<secret>. Remove this route after use.
 */
Route::get('/migrate', function (Request $request) {
    $secret = env('MIGRATE_SECRET');
    if (empty($secret) || $request->query('s') !== $secret) {
        abort(403, 'Forbidden');
    }

    // Run migrations (force in production)
    Artisan::call('migrate', ['--force' => true]);

    return response('Migrations completed!', 200);
});
