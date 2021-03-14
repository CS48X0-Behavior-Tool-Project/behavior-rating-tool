<?php

namespace Tests\Feature;

use Bouncer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;
    public function test_guest_can_view_login_page()
    {
        $response = $this->get('/');
        $response->assertOk();
    }

    // public function test_admin_can_not_view_login_page()
    // {
    //     $user = User::factory()->create();
    //     $user->assign('admin');
    //     $response = $this->actingAs($user)
    //         ->get('/account');

    //     $response->assertStatus(200);
    // }

    public function test_admin_can_view_home_page()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        $response = $this->actingAs($user)
            ->get('/home');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_add_user_page()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        $response = $this->actingAs($user)
            ->get('/add_user');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_create_quiz_page()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        $response = $this->actingAs($user)
            ->get('/create_quiz');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_account_page()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        $response = $this->actingAs($user)
            ->get('/account');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_quizzes_page()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        $response = $this->actingAs($user)
            ->get('/quizzes');

        $response->assertStatus(200);
    }

    public function test_guest_can_view_confirmation_page()
    {
        $response = $this->get('/confirmation');
        $response->assertOk();
    }
}
