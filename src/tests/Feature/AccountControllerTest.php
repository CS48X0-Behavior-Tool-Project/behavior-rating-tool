<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Hash;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_can_view_account_route()
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->get('/account');

        $response->assertStatus(200);
    }

    public function test_non_authenticated_can_not_view_account_route()
    {
        $response = $this->get('/account');
        $response->assertStatus(500);   // will have to be updated to redirect once
        // once check is implemented
    }

    public function test_user_update_first_name_of_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->post('/account', [
                'fname' => 'TEST',
            ]);

        $this->assertEquals('Name changed successfully!', session('name_message'));
        $this->assertEquals('TEST', $user->first_name);
    }

    public function test_user_update_last_name_of_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession(['banned' => false])
            ->post('/account', [
                'lname' => 'TEST',
            ]);
        $this->assertEquals('Name changed successfully!', session('name_message'));
        $this->assertEquals('TEST', $user->last_name);
    }

    public function test_user_update_email_of_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/account', [
                'old-email' => $user->email,
                'email' => 'test_email@example.com',
            ]);
        $this->assertEquals('Email changed successfully!', session('email_message'));
        $this->assertEquals('test_email@example.com', $user->email);
    }

    public function test_user_update_password_of_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/account', [
                'old-password' => 'password',
                'password' => 'this_is_a_new_password',
            ]);
        $this->assertEquals('Password changed successfully!', session('password_message'));
        $this->assertEquals(true, Hash::check('this_is_a_new_password', $user->password));
    }
}
