<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskUpdated extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $task;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Task $task
     */
    public function __construct(User $user, Task $task)
    {
        $this->user = $user;
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Task has being updated')
            ->view('emails.task_updated', ['user' => $this->user, 'task' => $this->task]);
    }
}
