<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_can_view_account_route()
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/account');

        $response->assertStatus(200);
    }

    public function test_non_authenticated_can_not_view_account_route()
    {
        $response = $this->get('/account');
        $response->assertRedirect('/login');
    }

    public function test_user_update_first_name_of_account()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/account', [
                'fname' => 'TEST',
            ]);

        $this->assertEquals('Name changed successfully!', session('name_message'));
        $this->assertEquals('TEST', $user->first_name);
    }

    public function test_user_update_last_name_of_account()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/account', [
                'lname' => 'TEST',
            ]);
        $this->assertEquals('Name changed successfully!', session('name_message'));
        $this->assertEquals('TEST', $user->last_name);
    }

    public function test_user_update_email_of_account()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/account', [
                'old-email' => $user->email,
                'email' => 'test_email@example.com',
                'email-confirm' => 'test_email@example.com',
            ]);
        $this->assertEquals('Email changed successfully!', session('email_message'));
        $this->assertEquals('test_email@example.com', $user->email);
    }

    public function test_user_update_password_of_account()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/account', [
                'old-password' => 'password',
                'password' => 'this_is_a_new_password',
                'password-confirm' => 'this_is_a_new_password',
            ]);
        $this->assertEquals('Password changed successfully!', session('password_message'));
        $this->assertEquals(true, Hash::check('this_is_a_new_password', $user->password));
    }

    public function test_user_update_with_invalid_email_account()
    {
        $user = User::factory()->create();
        $user_oldemail = $user->email;
        $this->actingAs($user)
            ->post('/account', [
                'old-email' => 'asdf',
                'email' => 'asdf',
                'email-confirm' => 'asdf',
            ]);
        $this->assertEquals('Old Email does not match current email.', session('email_error'));
        $this->assertEquals($user_oldemail, $user->email);
    }

    public function test_user_update_with_blank_email_account()
    {
        $user = User::factory()->create();
        $user_oldemail = $user->email;
        $this->actingAs($user)
            ->post('/account', [
                'old-email' => $user_oldemail,
                'email' => NULL,
                'email-confirm' => NULL,
            ]);
        $this->assertEquals('Please fill in all the fields.', session('email_error'));
        $this->assertEquals($user_oldemail, $user->email);
    }

    public function test_user_update_with_duplicate_email_account()
    {
        // Generate two users
        $userOne = User::factory()->create();
        $userTwo = User::factory()->create();

        $userOneOldEmail = $userOne->email;
        $userTwoEmail = $userTwo->email;

        // POST with userOne requesting access to userTwo's email
        $this->actingAs($userOne)
            ->post('/account', [
                'old-email' => $userOneOldEmail,
                'email' => $userTwoEmail,
                'email-confirm' => $userTwoEmail,
            ]);

        $this->assertEquals('That email is already in use.', session('email_error'));
        $this->assertEquals($userOneOldEmail, $userOne->email);
    }

    public function test_user_incorrect_password_of_account()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/account', [
                'old-password' => 'incorrect_password',
                'password' => 'this_is_a_new_password',
                'password-confirm' => 'this_is_a_new_password',
            ]);
        $this->assertEquals('Incorrect Password.', session('password_error'));
        $this->assertEquals(true, Hash::check('password', $user->password));
    }

    public function test_user_blank_password()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/account', [
                'old-password' => 'password',
                'password' => NULL,
                'password-confirm' => NULL,
            ]);
        $this->assertEquals('Please fill in all the fields.', session('password_error'));
        $this->assertEquals(true, Hash::check('password', $user->password));
    }
}
