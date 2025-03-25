<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $task;
    protected $completedBy;

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
        $this->completedBy = Auth::user() ? Auth::user()->name : 'A user';

        // Set the queue using the method provided by the Queueable trait
        $this->onQueue('emails');
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
        $url = route('tasks.show', $this->task->id);

        Log::info('Sending task completed email', [
            'task_id' => $this->task->id,
            'user_email' => $notifiable->email
        ]);

        return (new MailMessage)
            ->subject('Task Completed')
            ->greeting('Hello ' . ($notifiable->name ?? '') . '!')
            ->line('A task has been marked as completed.')
            ->line('Task: ' . $this->task->title)
            ->line('Completed by: ' . $this->completedBy)
            ->action('View Task', $url)
            ->line('Completed on: ' . now()->format('Y-m-d H:i'))
            ->line('Thank you for using our application!');
    }
}
