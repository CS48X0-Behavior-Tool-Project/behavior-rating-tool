<?php

namespace Tests\Feature;

use App\Models\Video;
use App\Models\Quiz;
use App\Models\User;
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

        Bouncer::allow('student')->to('view-profile');
        Bouncer::allow('student')->to('edit-profile');
        Bouncer::allow('student')->to('review-my-quizzes');  
        Bouncer::allow('student')->to('conduct-quizzes');

        Bouncer::allow('expert')->to('view-profile');
        Bouncer::allow('expert')->to('edit-profile');
        Bouncer::allow('expert')->to('create-quizzes');
        Bouncer::allow('expert')->to('update-quizzes');
        Bouncer::allow('expert')->to('review-my-quizzes');  
        Bouncer::allow('expert')->to('conduct-quizzes');

        Bouncer::allow('ta')->to('view-users-page');
        Bouncer::allow('ta')->to('create-users');
        Bouncer::allow('ta')->to('edit-users');
        Bouncer::allow('ta')->to('import-users');
        Bouncer::allow('ta')->to('export-users');
        Bouncer::allow('ta')->to('view-profile');
        Bouncer::allow('ta')->to('edit-profile');
        Bouncer::allow('ta')->to('create-quizzes');
        Bouncer::allow('ta')->to('update-quizzes');
        Bouncer::allow('ta')->to('review-my-quizzes');      // review page by a student
        Bouncer::allow('ta')->to('review-quizzes');         // review for all students
        Bouncer::allow('ta')->to('export-student-quizzes');
        Bouncer::allow('ta')->to('conduct-quizzes');

        Bouncer::allow('admin')->to('view-users-page');
        Bouncer::allow('admin')->to('create-users');
        Bouncer::allow('admin')->to('edit-users');
        Bouncer::allow('admin')->to('delete-users');
        Bouncer::allow('admin')->to('import-users');
        Bouncer::allow('admin')->to('export-users');
        Bouncer::allow('admin')->to('view-profile');
        Bouncer::allow('admin')->to('edit-profile');
        Bouncer::allow('admin')->to('create-quizzes');
        Bouncer::allow('admin')->to('update-quizzes');
        Bouncer::allow('admin')->to('delete-quizzes');
        Bouncer::allow('admin')->to('review-my-quizzes');
        Bouncer::allow('admin')->to('review-quizzes');
        Bouncer::allow('admin')->to('export-student-quizzes');
        Bouncer::allow('admin')->to('conduct-quizzes');
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

    // public function test_guest_can_view_confirmation_page()
    // {
    //     $response = $this->get('/confirmation');
    //     $response->assertOk();
    // }
}
