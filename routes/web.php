<?php

use App\Http\Controllers\TaskWebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskWebController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskWebController::class, 'create'])->name('tasks.create');
Route::get('/tasks/report', [TaskWebController::class, 'report'])->name('tasks.report');
Route::get('/tasks/{task}', [TaskWebController::class, 'show'])->name('tasks.show');

// Route::get('/seed-tasks', function (Request $request) {
//     Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\TaskSeeder', '--force' => true]);

//     return response('Task seeder executed', 200);
// });

// Route::get('/migrate', function () {
//     Artisan::call('migrate', ['--force' => true]);

//     return response('Migrations completed!', 200);
// });
