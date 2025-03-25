<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $admin = User::where('email', 'admin@example.com')->first();
        $creator = $admin ?? $users->first();

        // Create tasks with assignments
        for ($i = 1; $i <= 10; $i++) {
            $task = Task::create([
                'title' => "Task {$i}",
                'description' => "This is the description for task {$i}.",
                'status' => $i % 3 === 0 ? 'completed' : ($i % 3 === 1 ? 'in_progress' : 'pending'),
                'due_date' => now()->addDays(rand(1, 30)),
                'created_by' => $creator->id,
            ]);

            // Assign random users to each task (excluding creator)
            $assignees = $users->where('id', '!=', $creator->id)->random(rand(1, 3));

            $assignData = [];
            foreach ($assignees as $assignee) {
                $assignData[$assignee->id] = ['assigned_at' => now()];
            }

            $task->assignedUsers()->attach($assignData);
        }
    }
}
