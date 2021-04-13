<?php

namespace Tests\Feature;

use App\Models\Video;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Tests\TestCase;

class UserCreationTest extends TestCase
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

    private function createAdmin()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        return $user;
    }

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
    //     $this->actingAs(User::class());
    //     $response = $this->get('add_user')
    //         ->assertUnauthorized();
    // }

    public function test_submission_of_single_valid_user()
    {
        $user = $this->createAdmin();
        $response = $this->actingAs($user)->from('/add_user')->post('/add_user', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'role' => 'student',
            'add_single_user' => null
        ]);
        $this->assertCount(2, User::all());
        $response->assertRedirect('/add_user');
    }

    public function test_submission_of_single_null_first_name_user()
    {
        $user = $this->createAdmin();
        $response = $this->actingAs($user)->from('/add_user')->post('/add_user', [
            'first_name' => null,
            'last_name' => 'User',
            'email' => 'test@example.com',
            'role' => 'student',
            'add_single_user' => null
        ]);
        $this->assertCount(1, User::all());
        $response->assertSessionHasErrors([
            'first_name'
        ]);
        $response->assertRedirect('/add_user');
    }

    public function test_submission_of_single_null_last_name_user()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->from('/add_user')->post('/add_user', [
            'first_name' => 'Test',
            'last_name' => null,
            'email' => 'test@example.com',
            'role' => 'student',
            'add_single_user' => null
        ]);
        $this->assertCount(1, User::all());
        $response->assertRedirect('/add_user');
    }

    public function test_submission_of_single_null_email_user()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->from('/add_user')->post('/add_user', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => null,
            'role' => 'student',
            'add_single_user' => null
        ]);
        $this->assertCount(1, User::all());
        $response->assertRedirect('/add_user'); // response status 500, should redirect the user back to the page with an error
    }

    public function test_submission_of_single_null_role_user()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)->from('/add_user')->post('/add_user', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'role' => null,
            'add_single_user' => null
        ]);
        $this->assertCount(1, User::all());
        $response->assertRedirect('/add_user');
    }

    public function test_user_through_csv()
    {
        $user = $this->createAdmin();

        $header = 'First Name,Last Name,Email';
        $row1 = 'Test,User,test@example.com';
        $row2 = ',User,test2@example.com';
        $row3 = 'Test,,test3@example.com';
        $row4 = 'John,Doe,,';
        $row5 = ',,,';
        $row6 = 'John,Doe,.test-@example.';

        $content = implode("\n", [$header, $row1, $row2, $row3, $row4, $row5, $row6]);

        $inputs = [
            'mycsv' =>
            UploadedFile::fake()->createWithContent(
                'test.csv',
                $content
            )
        ];

        $response = $this->actingAs($user)->from('/add_user')->postJson(
            '/add_user',
            $inputs
        );

        $this->assertCount(2, User::all());
        $response->assertRedirect('/add_user');
    }
}
