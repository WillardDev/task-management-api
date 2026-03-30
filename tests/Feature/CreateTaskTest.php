<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_can_create_a_task()
    {
        $payload = [
            'title' => 'Test Task from Feature Test',
            'due_date' => now()->addDay()->format('Y-m-d'),
            'priority' => 'medium'
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', [
            'title' => $payload['title'],
            'priority' => $payload['priority']
        ]);
    }
}
