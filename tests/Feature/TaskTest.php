<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function guests_cannot_list_tasks()
    {
        Task::factory()->create();

        $this->get(route('v1.users.tasks.index'))
            ->assertUnauthorized();
    }

    /** @test */
    public function authenticated_users_can_list_tasks()
    {
        $this->login();

        $task = Task::factory()->create();

        $this->get(route('v1.users.tasks.index'))
            ->assertSuccessful()
            ->assertSee($task->title);
    }

    /** @test */
    public function guests_cannot_list_users_tasks()
    {
        $user = User::factory()->create();

        Task::factory()->create(['user_id' => $user->id]);

        $this->get(route('v1.users.tasks.index.single', $user))
            ->assertUnauthorized();
    }

    /** @test */
    public function authenticated_users_can_list_users_tasks()
    {
        $this->login();

        $task = Task::factory()->create(['user_id' => auth()->id()]);

        $this->get(route('v1.users.tasks.index.single', auth()->user()))
            ->assertSuccessful()
            ->assertSee($task->title);
    }

    /** @test */
    public function guests_cannot_create_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->raw();

        $this->post(route('v1.users.tasks.store', $user), $task)
            ->assertUnauthorized();
    }

    /** @test */
    public function authenticated_users_can_create_task()
    {
        $this->login();

        $task = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->post(route('v1.users.tasks.store', auth()->user()), $task)
            ->assertSuccessful();

        $this->assertDatabaseHas('tasks', $task);
    }

    /** @test */
    public function authenticated_users_can_only_create_task_with_valid_title()
    {
        $this->login();

        $task = [
            'description' => $this->faker->paragraph,
        ];

        $this->post(route('v1.users.tasks.store', auth()->user()), $task)
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');

        $task = [
            'title' => str_repeat('a', 256),
            'description' => $this->faker->paragraph,
        ];

        $this->post(route('v1.users.tasks.store', auth()->user()), $task)
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function authenticated_users_can_only_create_task_with_valid_description()
    {
        $this->login();

        $task = [
            'title' => $this->faker->sentence,
            'description' => str_repeat('a', 65536),
        ];

        $this->post(route('v1.users.tasks.store', auth()->user()), $task)
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');
    }

    /** @test */
    public function guests_cannot_see_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory(['user_id' => $user->id])->create();

        $this->get(route('v1.users.tasks.show', [$user, $task]))
            ->assertUnauthorized();
    }

    /** @test */
    public function authenticated_users_can_see_a_task()
    {
        $this->login();

        $task = Task::factory(['user_id' => auth()->id()])->create();

        $this->get(route('v1.users.tasks.show', [auth()->user(), $task]))
            ->assertSuccessful()
            ->assertSee($task->title);
    }

    /** @test */
    public function guests_cannot_update_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory(['user_id' => $user->id])->create();

        $taskUpdate = [
            'user_id' => User::factory()->create()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->put(route('v1.users.tasks.update', [$user, $task]), $taskUpdate)
            ->assertUnauthorized();
    }

    /** @test */
    public function authenticated_users_can_update_a_task()
    {
        $this->login();

        $task = Task::factory(['user_id' => auth()->id()])->create();

        $taskUpdate = [
            'user_id' => User::factory()->create()->id,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->put(route('v1.users.tasks.update', [auth()->user(), $task]), $taskUpdate)
            ->assertSuccessful();

        $this->assertDatabaseHas('tasks', $taskUpdate);
    }

    /** @test */
    public function authenticated_users_can_only_update_a_task_with_non_empty_data()
    {
        $this->login();

        $task = Task::factory(['user_id' => auth()->id()])->create();

        $taskUpdate = [];

        $this->put(route('v1.users.tasks.update', [auth()->user(), $task]), $taskUpdate)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'description']);
    }

    /** @test */
    public function authenticated_users_can_only_update_a_task_user_id_with_a_valid_user_id()
    {
        $this->login();

        $task = Task::factory(['user_id' => auth()->id()])->create();

        $taskUpdate = [
            'user_id' => 'abc',
        ];

        $this->put(route('v1.users.tasks.update', [auth()->user(), $task]), $taskUpdate)
            ->assertStatus(422)
            ->assertJsonValidationErrors('user_id');

        $taskUpdate = [
            'user_id' => 9999,
        ];

        $this->put(route('v1.users.tasks.update', [auth()->user(), $task]), $taskUpdate)
            ->assertStatus(422)
            ->assertJsonValidationErrors('user_id');
    }

    /** @test */
    public function authenticated_users_can_only_update_a_task_title_with_a_valid_title()
    {
        $this->login();

        $task = Task::factory(['user_id' => auth()->id()])->create();

        $taskUpdate = [
            'title' => str_repeat('a', 256),
        ];

        $this->put(route('v1.users.tasks.update', [auth()->user(), $task]), $taskUpdate)
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function authenticated_users_can_only_update_a_task_description_with_a_valid_description()
    {
        $this->login();

        $task = Task::factory(['user_id' => auth()->id()])->create();

        $taskUpdate = [
            'title' => $this->faker->sentence,
            'description' => str_repeat('a', 65536),
        ];

        $this->put(route('v1.users.tasks.update', [auth()->user(), $task]), $taskUpdate)
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');
    }

    /** @test */
    public function guests_cannot_soft_delete_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory(['user_id' => $user->id])->create();

        $this->delete(route('v1.users.tasks.destroy', [$user, $task]))
            ->assertUnauthorized();
    }

    /** @test */
    public function authenticated_users_can_soft_delete_a_task()
    {
        $this->login();

        $task = Task::factory(['user_id' => auth()->id()])->create();

        $this->delete(route('v1.users.tasks.destroy', [auth()->user(), $task]))
            ->assertSuccessful();

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function guests_cannot_complete_a_task()
    {
        $user = User::factory()->create();
        $task = Task::factory(['user_id' => $user->id])->create();

        $this->post(route('v1.users.tasks.complete', [$user, $task]))
            ->assertUnauthorized();
    }

    /** @test */
    public function authenticated_users_can_complete_a_task()
    {
        $this->login();

        $task = Task::factory(['user_id' => auth()->id()])->create();

        $this->post(route('v1.users.tasks.complete', [auth()->user(), $task]))
            ->assertSuccessful();

        $task->refresh();

        $this->assertNotNull($task->completed_at);
    }
}
