<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Only use mail channel
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::info('Sending task assigned email', [
            'task_id' => $this->task->id,
            'user_email' => $notifiable->email
        ]);

        return (new MailMessage)
            ->subject('New Task Assignment')
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('You have been assigned a new task.')
            ->line('Task: ' . $this->task->title)
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Due date: ' . $this->task->due_date)
            ->line('Thank you for using our application!');
    }
}
