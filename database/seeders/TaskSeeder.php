<?php

namespace Database\Seeders;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $priorities = ['low', 'medium', 'high'];
        $statuses = ['pending', 'in_progress', 'done'];

        $subjects = ['project', 'proposal', 'pull request', 'documentation', 'bugfix', 'feature', 'test', 'deployment', 'design', 'research'];
        $verbs = ['Complete', 'Review', 'Update', 'Fix', 'Write', 'Prepare', 'Test', 'Deploy', 'Design', 'Research'];

        // Create 50 tasks with deterministic unique titles (avoid Faker so seeder works in prod without dev deps)
        for ($i = 0; $i < 50; $i++) {
            $title = $verbs[$i % count($verbs)] . ' ' . $subjects[$i % count($subjects)] . ' #' . ($i + 1);
            $dueDate = Carbon::now()->addDays(rand(0, 30))->format('Y-m-d');

            $status = $statuses[array_rand($statuses)];
            $priority = $priorities[array_rand($priorities)];

            Task::create([
                'title' => $title,
                'due_date' => $dueDate,
                'priority' => $priority,
                'status' => $status,
            ]);
        }
    }
}
