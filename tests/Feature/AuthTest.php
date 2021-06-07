<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function user_can_be_registered()
    {
        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'secret',
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['name' => $user['name'], 'email' => $user['email']]);
    }

    /** @test */
    public function user_can_be_registered_with_distinct_email()
    {
        $email = $this->faker->email;

        $user = [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => 'secret',
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertSuccessful();

        $user = [
            'name' => $this->faker->name,
            'email' => $email,
            'password' => 'secret',
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function user_can_only_be_registered_with_valid_name()
    {
        $user = [
            'email' => $this->faker->email,
            'password' => 'secret',
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $user = [
            'name' => str_repeat('a', 256),
            'email' => $this->faker->email,
            'password' => 'secret',
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function user_can_only_be_registered_with_valid_email()
    {
        $user = [
            'name' => $this->faker->name,
            'password' => 'secret',
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        $user = [
            'name' => $this->faker->name,
            'email' => 'abc',
            'password' => 'secret',
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        $user = [
            'name' => $this->faker->name,
            'email' => str_repeat('a', 256) . '@gmail.com',
            'password' => 'secret',
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function user_can_only_be_registered_with_valid_password()
    {
        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => str_repeat('a', 5),
        ];

        $this->post(route('v1.users.register'), $user)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function user_can_login()
    {
        $password = '123456';

        $user = User::factory()->create(['password' => password_hash($password, PASSWORD_DEFAULT)]);

        $this->post(route('v1.users.login'), ['email' => $user->email, 'password' => $password])
            ->assertSuccessful()
            ->assertSee('access_token');
    }

    /** @test */
    public function user_can_only_login_with_valid_email()
    {
        $login = [
            'password' => '123456',
        ];

        $this->post(route('v1.users.login'), $login)
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $login = [
            'email' => 'abc',
            'password' => '123456',
        ];

        $this->post(route('v1.users.login'), $login)
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $login = [
            'email' => str_repeat('a', 256) . '@gmail.com',
            'password' => '123456',
        ];

        $this->post(route('v1.users.login'), $login)
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $login = [
            'email' => $this->faker->email,
            'password' => '123456',
        ];

        $this->post(route('v1.users.login'), $login)
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    /** @test */
    public function user_can_only_login_with_valid_password()
    {
        $login = [
            'email' => $this->faker->email,
        ];

        $this->post(route('v1.users.login'), $login)
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');

        $login = [
            'email' => $this->faker->email,
            'password' => str_repeat('a', 5),
        ];

        $this->post(route('v1.users.login'), $login)
            ->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }
}
