<?php

namespace App\Events;

use App\Models\Task;

class TaskUpdated
{
    public $task;

    /**
     * Create a new event instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}
