<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_be_listed()
    {
        $user = User::factory()->create();

        $this->get(route('v1.users.index'))
            ->assertSuccessful()
            ->assertSee($user->name);
    }

    /** @test */
    public function api_returns_404_when_there_are_no_users()
    {
        $this->get(route('v1.users.index'))
            ->assertNotFound();
    }
}
