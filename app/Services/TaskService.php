<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskCompleted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TaskService
{
    /**
     * Set task attributes and save
     */
    private function setTaskAttributes($request, Task $task, bool $isNew)
    {
        // Set the task attributes
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->due_date = $request->due_date;

        // Set creator only for new tasks
        if ($isNew) {
            $task->created_by = Auth::id();
        }

        $task->save();

        return $task;
    }

    /**
     * Format the response array
     */
    private function formatResponse(bool $success, string $message)
    {
        return [
            'success' => $success,
            'message' => $message
        ];
    }

    /**
     * Handle user assignments to a task and notify newly assigned users
     */
    private function handleAssignments(Task $task, array $assigneeIds, bool $isNew)
    {
        $assigneeIds = array_filter($assigneeIds);
        $newlyAssignedUsers = collect();

        // Create a sync array with assigned_at value to replace existing assignments
        $syncData = [];
        foreach ($assigneeIds as $userId) {
            if (empty($userId)) continue;

            if (!$isNew) {
                // Get user to update the assigned_at timestamp
                $existingAssignment = $task->assignedUsers()->where('user_id', $userId)->first();
                $assignedAt = $existingAssignment ? $existingAssignment->pivot->assigned_at : now();

                // If user is newly assigned, set isNewAssignment to true
                $isNewAssignment = !$existingAssignment;
            } else {
                $assignedAt = now();
                $isNewAssignment = true;
            }

            $syncData[$userId] = ['assigned_at' => $assignedAt];

            // Collect newly assigned users for notification
            if ($isNewAssignment) {
                $assignedUser = User::find($userId);
                if ($assignedUser) {
                    $newlyAssignedUsers->push($assignedUser);
                }
            }
        }

        $task->assignedUsers()->sync($syncData);

        // Send notifications to newly assigned users
        if ($newlyAssignedUsers->isNotEmpty()) {
            Notification::send($newlyAssignedUsers, new TaskAssigned($task));
        }
    }

    /**
     * Send completion notifications for a task
     */
    private function sendCompletionNotifications(Task $task)
    {
        $notificationRecipients = collect();

        // Add creator if they're not the current user
        if ($task->created_by != Auth::id()) {
            $notificationRecipients->push($task->creator);
        }

        // Add all assigned users except the current user
        foreach ($task->assignedUsers as $user) {
            if ($user->id != Auth::id()) {
                $notificationRecipients->push($user);
            }
        }

        // Send completion notifications
        if ($notificationRecipients->isNotEmpty()) {
            Notification::send($notificationRecipients, new TaskCompleted($task));
        }
    }

    /**
     * Save a task (create or update)
     */
    public function saveTask($request, Task $task)
    {
        try {
            $isNew = !$task->exists;
            $previousStatus = $isNew ? null : $task->status;

            // Set task attributes and save
            $this->setTaskAttributes($request, $task, $isNew);

            // Handle assignment changes
            if ($request->has('assigned_to') && is_array($request->assigned_to)) {
                $this->handleAssignments($task, $request->assigned_to, $isNew);
            } else {
                $task->assignedUsers()->detach();
            }

            // Handle task completion notifications
            if (!$isNew && $request->status === 'completed' && $previousStatus !== 'completed') {
                $this->sendCompletionNotifications($task);
            }

            return $this->formatResponse(true, 'Task ' . ($isNew ? 'created' : 'updated') . ' successfully');
        } catch (\Exception $e) {
            return $this->formatResponse(false, 'Task ' . ($isNew ? 'creation' : 'update') . ' failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a task
     */
    public function deleteTask(Task $task)
    {
        // Delete all assignments
        $task->assignedUsers()->detach();

        // Delete the task
        return $task->delete();
    }

    /**
     * Get filtered tasks based on user preferences
     */
    public function getFilteredTasks($filter, $userId)
    {
        // Handle the "my_tasks" filter which uses created_by column
        if ($filter === 'my_tasks') {
            return Task::where('created_by', $userId)
                ->with('assignedUsers')
                ->paginate(10);
        } elseif ($filter === 'assigned_to_me') {
            return Task::has('assignedUsers')
                ->whereRelation('assignedUsers', 'user_id', $userId)
                ->with('assignedUsers')
                ->paginate(10);
        } else {
            return collect();
        }
    }
}
