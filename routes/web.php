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
 * Seeder trigger endpoint (protected by env SEED_SECRET)
 * Example: /seed-tasks?secret=your_secret_here
 * NOTE: For security, set SEED_SECRET in production and keep it secret.
 */
Route::get('/seed-tasks', function (Request $request) {
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TaskSeeder', '--force' => true]);

    return response('Task seeder executed', 200);
});

/**
 * Temporary migration endpoint (UNPROTECTED)
 * Visit /migrate to run migrations. Remove this route after use.
 */
// Route::get('/migrate', function () {
//     Artisan::call('migrate', ['--force' => true]);

//     return response('Migrations completed!', 200);
// });
