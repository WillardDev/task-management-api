<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Complete project proposal',
                'due_date' => now()->addDays(3)->format('Y-m-d'),
                'priority' => 'high',
                'status' => 'pending'
            ],
            [
                'title' => 'Review pull requests',
                'due_date' => now()->addDays(1)->format('Y-m-d'),
                'priority' => 'medium',
                'status' => 'in_progress'
            ],
            [
                'title' => 'Update documentation',
                'due_date' => now()->addDays(5)->format('Y-m-d'),
                'priority' => 'low',
                'status' => 'pending'
            ],
            [
                'title' => 'Fix critical bug',
                'due_date' => now()->format('Y-m-d'),
                'priority' => 'high',
                'status' => 'done'
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
