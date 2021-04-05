<?php

namespace Tests\Feature;

use App\Models\Video;
use App\Models\Quiz;
use App\Models\User;
use Database\Seeders\QuizSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Bouncer::allow('admin')->to(['delete', 'edit', 'create', 'view'], [User::class, Video::class, Quiz::class]);
        Bouncer::allow('admin')->to('export-users');
        $this->seed(QuizSeeder::class);
    }

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

    public function test_admin_can_view_add_user_page()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        $response = $this->actingAs($user)
            ->get('/user/add');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_create_quiz_page()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        $response = $this->actingAs($user)
            ->get('/quiz/create');

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
            ->get('/quiz/list');

        $response->assertStatus(200);
    }

    // public function test_guest_can_view_confirmation_page()
    // {
    //     $response = $this->get('/confirmation');
    //     $response->assertOk();
    // }
}
