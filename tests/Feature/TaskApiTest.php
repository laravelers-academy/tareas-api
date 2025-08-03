<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_crear_una_tarea()
    {
        $payload = [
            'title' => 'Nueva tarea desde prueba',
            'description' => 'Descripción de prueba',
            'completed' => false
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Tarea creada correctamente',
                'data' => [
                    'title' => 'Nueva tarea desde prueba',
                    'description' => 'Descripción de prueba',
                    'completed' => false,
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Nueva tarea desde prueba'
        ]);
    }

    /** @test */
    public function puede_listar_todas_las_tareas()
    {
        Task::factory()->create([
            'title' => 'Tarea 1'
        ]);
        Task::factory()->create([
            'title' => 'Tarea 2'
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Listado de tareas',
            ])
            ->assertJsonCount(2, 'data');
    }
}
