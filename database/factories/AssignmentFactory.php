<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'task_id' => Task::factory(),
            'assigned_at' => now(),
            'completed_at' => null,
        ];
    }

    /**
     * Mark the task as completed.
     */
    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'completed_at' => now(),
        ]);
    }
}
