<?php

namespace Tests\Feature;

use App\Models\Video;
use App\Models\Quiz;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\QuizSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Bouncer::allow('admin')->to(['delete', 'edit', 'create', 'view'], [User::class, Video::class, Quiz::class]);
        Bouncer::allow('admin')->to('export-users');

        $this->seed(QuizSeeder::class);
        $this->createRolePermission();   // add role-permission setup data

        // Create a fake video for testing
        Video::create([
            'name' => 'test',
            'original_name' => 'fake_original_name',
            'disk' => 'fake_disk',
            'path' => 'fake_path'
        ]);
    }

    private function createAdmin()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        return $user;
    }

    private function createRolePermission() {

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

    /**
     * Administrator can create a valid quiz
     *
     * @return void
     */
    public function test_admin_can_create_valid_quiz()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/create_quiz', [
                'video-id' => '1',
                'video-name' => 'Rabbit1',
                'animal-radio' => ['New'],
                'a-new' => 'Rabbit',
                'behaviour-check' => ['1', '2'],
                'box-0' => null,
                'box-1' => 'Test_B_1',
                'box-2' => 'Test_B_2',
                'box-3' => 'Test_B_3',
                'box-4' => 'Test_B_4',
                'box-5' => 'Test_B_5',
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Test_I_1',
                'inter-2' => 'Test_I_2',
                'inter-3' => 'Test_I_3',
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertRedirect('/create_quiz');
        $response->assertSessionHas('quiz-status', 'Quiz Rabbit1 Created Successfully');
    }

    public function test_non_authenticated_user_can_not_create_valid_quiz()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/create_quiz', [
                'video-id' => '1',
                'video-name' => 'test',
                'animal-radio' => ['New'],
                'a-new' => 'Rabbit',
                'behaviour-check' => ['1', '2'],
                'box-0' => null,
                'box-1' => 'Test_B_1',
                'box-2' => 'Test_B_2',
                'box-3' => 'Test_B_3',
                'box-4' => 'Test_B_4',
                'box-5' => 'Test_B_5',
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Test_I_1',
                'inter-2' => 'Test_I_2',
                'inter-3' => 'Test_I_3',
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_not_create_quiz_with_invalid_video()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/create_quiz', [
                'video-id' => '2',
                'video-name' => 'test',
                'animal-radio' => ['New'],
                'a-new' => 'Rabbit',
                'behaviour-check' => ['1', '2'],
                'box-0' => null,
                'box-1' => 'Test_B_1',
                'box-2' => 'Test_B_2',
                'box-3' => 'Test_B_3',
                'box-4' => 'Test_B_4',
                'box-5' => 'Test_B_5',
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Test_I_1',
                'inter-2' => 'Test_I_2',
                'inter-3' => 'Test_I_3',
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertRedirect('/create_quiz');
        $response->assertSessionHas('video-status', 'Video ID Mismatch');
    }

    public function test_admin_can_not_create_quiz_with_no_animal_selected()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/create_quiz', [
                'video-id' => '1',
                'video-name' => 'test',
                'animal-radio' => null,
                'a-new' => 'Rabbit',
                'behaviour-check' => ['1', '2'],
                'box-0' => null,
                'box-1' => 'Test_B_1',
                'box-2' => 'Test_B_2',
                'box-3' => 'Test_B_3',
                'box-4' => 'Test_B_4',
                'box-5' => 'Test_B_5',
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Test_I_1',
                'inter-2' => 'Test_I_2',
                'inter-3' => 'Test_I_3',
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertRedirect('/create_quiz');
        $response->assertSessionHas('animal-status', 'No Animal Selected');
    }

    public function test_admin_can_not_create_quiz_with_no_animal_input()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/create_quiz', [
                'video-id' => '1',
                'video-name' => 'test',
                'animal-radio' => ['New'],
                'a-new' => null,
                'behaviour-check' => ['1', '2'],
                'box-0' => null,
                'box-1' => 'Test_B_1',
                'box-2' => 'Test_B_2',
                'box-3' => 'Test_B_3',
                'box-4' => 'Test_B_4',
                'box-5' => 'Test_B_5',
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Test_I_1',
                'inter-2' => 'Test_I_2',
                'inter-3' => 'Test_I_3',
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertRedirect('/create_quiz');
        $response->assertSessionHas('animal-status', 'Animal Field Empty');
    }

    public function test_admin_can_not_create_quiz_with_invalid_behaviours()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/create_quiz', [
                'video-id' => '1',
                'video-name' => 'test',
                'animal-radio' => ['New'],
                'a-new' => 'Rabbit',
                'behaviour-check' => null,
                'box-0' => null,
                'box-1' => 'Test_B_1',
                'box-2' => 'Test_B_2',
                'box-3' => 'Test_B_3',
                'box-4' => 'Test_B_4',
                'box-5' => 'Test_B_5',
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Test_I_1',
                'inter-2' => 'Test_I_2',
                'inter-3' => 'Test_I_3',
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertRedirect('/create_quiz');
        $response->assertSessionHas('behaviour-status', 'Behaviours Incomplete');
    }

    public function test_admin_can_not_create_quiz_with_missing_behaviours()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/create_quiz', [
                'video-id' => '1',
                'video-name' => 'test',
                'animal-radio' => ['New'],
                'a-new' => 'Rabbit',
                'behaviour-check' => ['1', '2'],
                'box-0' => null,
                'box-1' => 'Test_B_1',
                'box-2' => null,
                'box-3' => null,
                'box-4' => null,
                'box-5' => null,
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Test_I_1',
                'inter-2' => 'Test_I_2',
                'inter-3' => 'Test_I_3',
                'inter-4' => null,
                'inter-5' => null,
            ]);
        $response->assertRedirect('/create_quiz');
        $response->assertSessionHas('behaviour-status', 'Checked Fields Must Be Filled In');
    }

    public function test_admin_can_not_create_quiz_with_invalid_interpretation()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/create_quiz', [
                'video-id' => '1',
                'video-name' => 'test',
                'animal-radio' => ['New'],
                'a-new' => 'Rabbit',
                'behaviour-check' => ['1', '2'],
                'box-0' => null,
                'box-1' => 'Test_B_1',
                'box-2' => 'Test_B_2',
                'box-3' => 'Test_B_3',
                'box-4' => 'Test_B_4',
                'box-5' => 'Test_B_5',
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => null,
                'inter-1' => 'Test_I_1',
                'inter-2' => 'Test_I_2',
                'inter-3' => 'Test_I_3',
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertRedirect('/create_quiz');
        $response->assertSessionHas('int-status', 'Interpretations Incomplete');
    }

    public function test_admin_can_not_create_quiz_with_missing_interpretation()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/create_quiz', [
                'video-id' => '1',
                'video-name' => 'test',
                'animal-radio' => ['New'],
                'a-new' => 'Rabbit',
                'behaviour-check' => ['1', '2'],
                'box-0' => null,
                'box-1' => 'Test_B_1',
                'box-2' => 'Test_B_2',
                'box-3' => 'Test_B_3',
                'box-4' => 'Test_B_4',
                'box-5' => 'Test_B_5',
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => null,
                'inter-2' => 'Test_I_2',
                'inter-3' => 'Test_I_3',
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertRedirect('/create_quiz');
        $response->assertSessionHas('int-status', 'Selected Field Must Be Filled In');
    }

    public function test_admin_can_edit_quiz()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/edit_quiz/1', [
                'video-id' => '1',
                'video-name' => 'Horse001',
                'animal-radio' => ['New'],
                'a-new' => 'Pony',
                'behaviour-check' => ['1'],
                'box-0' => null,
                'box-1' => 'Kicking',
                'box-2' => 'Dancing',
                'box-3' => null,
                'box-4' => null,
                'box-5' => null,
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Angry',
                'inter-2' => 'Laughing',
                'inter-3' => null,
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertRedirect('/quizzes');
        $response->assertSessionHas('edit-status', 'Successfully Edited Quiz Horse001');
    }

    public function test_non_authenticated_user_can_not_edit_quiz()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/edit_quiz/1', [
                'video-id' => '1',
                'video-name' => 'Horse001',
                'animal-radio' => ['New'],
                'a-new' => 'Pony',
                'behaviour-check' => ['1'],
                'box-0' => null,
                'box-1' => 'Kicking',
                'box-2' => 'Dancing',
                'box-3' => null,
                'box-4' => null,
                'box-5' => null,
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Angry',
                'inter-2' => 'Laughing',
                'inter-3' => null,
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_not_edit_quiz_with_no_animal_selected()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/edit_quiz/1', [
                'video-id' => '1',
                'video-name' => 'Horse001',
                'animal-radio' => null,
                'a-new' => null,
                'behaviour-check' => ['1'],
                'box-0' => null,
                'box-1' => 'Kicking',
                'box-2' => 'Dancing',
                'box-3' => null,
                'box-4' => null,
                'box-5' => null,
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Angry',
                'inter-2' => 'Laughing',
                'inter-3' => null,
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertSessionHas('animal-status', 'No Animal Selected');
    }

    public function test_admin_can_not_edit_quiz_with_no_animal_input()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/edit_quiz/1', [
                'video-id' => '1',
                'video-name' => 'Horse001',
                'animal-radio' => ['New'],
                'a-new' => null,
                'behaviour-check' => ['1'],
                'box-0' => null,
                'box-1' => 'Kicking',
                'box-2' => 'Dancing',
                'box-3' => null,
                'box-4' => null,
                'box-5' => null,
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Angry',
                'inter-2' => 'Laughing',
                'inter-3' => null,
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertSessionHas('animal-status', 'Animal Field Empty');
    }

    public function test_admin_can_not_edit_quiz_with_invalid_behaviours()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/edit_quiz/1', [
                'video-id' => '1',
                'video-name' => 'Horse001',
                'animal-radio' => ['New'],
                'a-new' => 'Pony',
                'behaviour-check' => null,
                'box-0' => null,
                'box-1' => 'Kicking',
                'box-2' => 'Dancing',
                'box-3' => null,
                'box-4' => null,
                'box-5' => null,
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Angry',
                'inter-2' => 'Laughing',
                'inter-3' => null,
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertSessionHas('behaviour-status', 'Behaviours Incomplete');
    }

    public function test_admin_can_not_edit_quiz_with_missing_behaviours()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/edit_quiz/1', [
                'video-id' => '1',
                'video-name' => 'Horse001',
                'animal-radio' => ['New'],
                'a-new' => 'Pony',
                'behaviour-check' => ['1'],
                'box-0' => null,
                'box-1' => null,
                'box-2' => 'Dancing',
                'box-3' => null,
                'box-4' => null,
                'box-5' => null,
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => 'Angry',
                'inter-2' => 'Laughing',
                'inter-3' => null,
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertSessionHas('behaviour-status', 'Checked Fields Must Be Filled In');
    }

    public function test_admin_can_not_edit_quiz_with_invalid_interpretations()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/edit_quiz/1', [
                'video-id' => '1',
                'video-name' => 'Horse001',
                'animal-radio' => ['New'],
                'a-new' => 'Pony',
                'behaviour-check' => ['1'],
                'box-0' => null,
                'box-1' => 'Kicking',
                'box-2' => 'Dancing',
                'box-3' => null,
                'box-4' => null,
                'box-5' => null,
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => null,
                'inter-1' => 'Angry',
                'inter-2' => 'Laughing',
                'inter-3' => null,
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertSessionHas('int-status', 'Interpretations Incomplete');
    }

    public function test_admin_can_not_edit_quiz_with_missing_interpretations()
    {
        $user = $this->createAdmin();

        $response = $this->actingAs($user)
            ->post('/edit_quiz/1', [
                'video-id' => '1',
                'video-name' => 'Horse001',
                'animal-radio' => ['New'],
                'a-new' => 'Pony',
                'behaviour-check' => ['1'],
                'box-0' => null,
                'box-1' => 'Kicking',
                'box-2' => 'Dancing',
                'box-3' => null,
                'box-4' => null,
                'box-5' => null,
                'box-6' => null,
                'box-7' => null,
                'box-8' => null,
                'box-9' => null,
                'interpretation-radio' => '1',
                'inter-1' => null,
                'inter-2' => 'Laughing',
                'inter-3' => null,
                'inter-4' => null,
                'inter-5' => null,
            ]);

        $response->assertSessionHas('int-status', 'Selected Field Must Be Filled In');
    }
}
