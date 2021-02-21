<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Check for non-authenticated user views not implemented yet
     */
    // public function test_non_authenticated_user_can_not_view_add_users()
    // {
    //     $response = $this->get('/add_user')
    //         ->assertUnauthorized();
    // }

    /**
     * Check for authenticated user views not implemented yet
     */
    // public function test_authenticated_user_can_not_view_add_users()
    // {
    //     $this->actingAs(User::class);
    //     $response = $this->get('add_user')
    //         ->assertUnauthorized();
    // }

    public function testSubmissionOfSingleValidUser()
    {
        $response = $this->post('/add_user', [
            'fname' => 'Test',
            'lname' => 'User',
            'email' => 'test@example.com',
            'role' => 'student',
            'add_single_user' => null
        ]);
        $this->assertCount(1, User::all());
        $response->assertRedirect('/add_user');
    }

    // public function testSubmissionOfSingleNullFnameUser()
    // {
    //     $response = $this->post('/add_user', [
    //         'fname' => null,
    //         'lname' => 'User',
    //         'email' => 'test@example.com',
    //         'role' => 'student',
    //         'add_single_user' => null
    //     ]);
    //     $this->assertCount(0, User::all());
    //     $response->assertRedirect('/add_user');
    // }

    // public function testSubmissionOfSingleNullLnameUser()
    // {
    //     $response = $this->post('/add_user', [
    //         'fname' => 'Test',
    //         'lname' => null,
    //         'email' => 'test@example.com',
    //         'role' => 'student',
    //         'add_single_user' => null
    //     ]);
    //     $this->assertCount(0, User::all());
    //     $response->assertRedirect('/add_user');
    // }

    // public function testSubmissionOfSingleNullEmailUser()
    // {
    //     $response = $this->post('/add_user', [
    //         'fname' => 'Test',
    //         'lname' => 'User',
    //         'email' => null,
    //         'role' => 'student',
    //         'add_single_user' => null
    //     ]);
    //     $this->assertCount(0, User::all());
    //     $response->assertRedirect('/add_user'); // response status 500, should redirect the user back to the page with an error
    // }

    // public function testSubmissionOfSingleNullRoleUser()
    // {
    //     $response = $this->post('/add_user', [
    //         'fname' => 'Test',
    //         'lname' => 'User',
    //         'email' => 'test@example.com',
    //         'role' => null,
    //         'add_single_user' => null
    //     ]);
    //     $this->assertCount(0, User::all());
    //     $response->assertRedirect('/add_user');
    // }
}
