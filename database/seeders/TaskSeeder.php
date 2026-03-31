<?php

namespace Database\Seeders;

use App\Models\Task;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();

        $priorities = ['low', 'medium', 'high'];
        $statuses = ['pending', 'in_progress', 'done'];

        // Create 50 tasks with varied priorities and statuses
        for ($i = 0; $i < 50; $i++) {
            $title = ucfirst($faker->words(3, true)).' #'.($i + 1);
            $dueDate = $faker->dateTimeBetween('now', '+30 days')->format('Y-m-d');

            // Weighted distribution: more pending, fewer done
            $status = $faker->randomElement($statuses);

            $priority = $faker->randomElement($priorities);

            Task::create([
                'title' => $title,
                'due_date' => $dueDate,
                'priority' => $priority,
                'status' => $status,
            ]);
        }
    }
}
