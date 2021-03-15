<?php

namespace Tests\Feature;

use App\Models\Video;
use App\Models\Quiz;
use App\Models\User;
use Database\Seeders\BouncerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(BouncerSeeder::class);
    }

    private function fetchAdminUser() {
        $user = User::where('email', env('ADMIN_USER_EMAIL'))->first();
        return $user;
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

    public function test_admin_can_view_home_page()
    {
        $user = $this->fetchAdminUser();
        $response = $this->actingAs($user)
            ->get('/home');

        $response->assertOk();
    }

    public function test_admin_can_view_add_user_page()
    {
        $user = $this->fetchAdminUser();
        $response = $this->actingAs($user)
            ->get('/add_user');

        $response->assertOk();
    }

    public function test_admin_can_view_create_quiz_page()
    {
        $user = $this->fetchAdminUser();
        $response = $this->actingAs($user)
            ->get('/create_quiz');
        $response->assertOk();
    }

    public function test_admin_can_view_account_page()
    {
        $user = $this->fetchAdminUser();
        $response = $this->actingAs($user)
            ->get('/account');

        $response->assertOk();
    }

    public function test_admin_can_view_quizzes_page()
    {
        $user = $this->fetchAdminUser();
        $response = $this->actingAs($user)
            ->get('/quizzes');

        $response->assertOk();
    }

    // public function test_guest_can_view_confirmation_page()
    // {
    //     $response = $this->get('/confirmation');
    //     $response->assertOk();
    // }
}
