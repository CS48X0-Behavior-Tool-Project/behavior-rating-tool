<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_can_be_instantiated()
    {
        $user = User::factory()->make();
        $this->assertInstanceOf(User::class, $user);
    }

    public function test_user_can_be_persisted()
    {
        $user = User::factory()->create();

        $this->assertDatabaseCount('users', 1);
    }
}
