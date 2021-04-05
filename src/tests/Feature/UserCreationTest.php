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
