<?php

namespace App\Http\Controllers\Api\v1;

use App\Events\TaskCreated;
use App\Events\TaskDeleted;
use App\Events\TaskUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user): \Illuminate\Http\Response
    {
        $tasks = ($user->exists) ? $user->tasks()->get() : Task::all();

        if($tasks->isEmpty()) {
            abort(404, 'Tasks not found.');
        }

        return response($tasks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskStoreRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStoreRequest $request, User $user)
    {
        $task = $user->tasks()->create($request->validated());

        event(new TaskCreated($task));

        return response($task);
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Task $task)
    {
        if ($task->user()->isNot($user)) {
            abort(404, 'Task not found.');
        }

        return response($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskUpdateRequest $request
     * @param User $user
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, User $user, Task $task): \Illuminate\Http\Response
    {
        if ($task->user()->isNot($user)) {
            abort(404, 'Task not found.');
        }

        $task->fill($request->validated());
        $task->save();

        event(new TaskUpdated($task));

        return response([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Task $task): \Illuminate\Http\Response
    {
        if ($task->user()->isNot($user)) {
            abort(404, 'Task not found.');
        }

        $task->delete();

        event(new TaskDeleted($task));

        return response([], 204);
    }

    /**
     * Mark a task as completed.
     *
     * @param User $user
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function complete(User $user, Task $task): \Illuminate\Http\Response
    {
        if ($task->user()->isNot($user)) {
            abort(404, 'Task not found.');
        }

        if ($task->isCompleted()) {
            abort(422, 'Task already completed.');
        }

        $task->complete();

        return response([], 204);
    }
}
