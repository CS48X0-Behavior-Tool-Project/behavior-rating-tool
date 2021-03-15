<?php

namespace Tests\Feature;

use App\Models\Video;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        // Create a fake video for testing
        Video::create([
            'name' => 'test',
        ]);
    }

    private function createAdmin()
    {
        $user = User::factory()->create();
        $user->assign('admin');
        return $user;
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
                'video-name' => 'test',
                'animal-radio' => ['New'],
                'a-new' => 'Rabbit',
                'behaviour-check' => ['1', '2'],
                'box-one' => 'Test_B_1',
                'box-two' => 'Test_B_2',
                'box-three' => 'Test_B_3',
                'box-four' => 'Test_B_4',
                'box-five' => 'Test_B_5',
                'box-six' => null,
                'box-seven' => null,
                'box-eight' => null,
                'box-nine' => null,
                'box-ten' => null,
                'interpretation-radio' => '1',
                'inter-one' => 'Test_I_1',
                'inter-two' => 'Test_I_2',
                'inter-three' => 'Test_I_3',
                'inter-four' => null,
                'inter-five' => null,
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
                'box-one' => 'Test_B_1',
                'box-two' => 'Test_B_2',
                'box-three' => 'Test_B_3',
                'box-four' => 'Test_B_4',
                'box-five' => 'Test_B_5',
                'box-six' => null,
                'box-seven' => null,
                'box-eight' => null,
                'box-nine' => null,
                'box-ten' => null,
                'interpretation-radio' => '1',
                'inter-one' => 'Test_I_1',
                'inter-two' => 'Test_I_2',
                'inter-three' => 'Test_I_3',
                'inter-four' => null,
                'inter-five' => null,
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
                'box-one' => 'Test_B_1',
                'box-two' => 'Test_B_2',
                'box-three' => 'Test_B_3',
                'box-four' => 'Test_B_4',
                'box-five' => 'Test_B_5',
                'box-six' => null,
                'box-seven' => null,
                'box-eight' => null,
                'box-nine' => null,
                'box-ten' => null,
                'interpretation-radio' => '1',
                'inter-one' => 'Test_I_1',
                'inter-two' => 'Test_I_2',
                'inter-three' => 'Test_I_3',
                'inter-four' => null,
                'inter-five' => null,
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
                'box-one' => 'Test_B_1',
                'box-two' => 'Test_B_2',
                'box-three' => 'Test_B_3',
                'box-four' => 'Test_B_4',
                'box-five' => 'Test_B_5',
                'box-six' => null,
                'box-seven' => null,
                'box-eight' => null,
                'box-nine' => null,
                'box-ten' => null,
                'interpretation-radio' => '1',
                'inter-one' => 'Test_I_1',
                'inter-two' => 'Test_I_2',
                'inter-three' => 'Test_I_3',
                'inter-four' => null,
                'inter-five' => null,
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
                'box-one' => 'Test_B_1',
                'box-two' => 'Test_B_2',
                'box-three' => 'Test_B_3',
                'box-four' => 'Test_B_4',
                'box-five' => 'Test_B_5',
                'box-six' => null,
                'box-seven' => null,
                'box-eight' => null,
                'box-nine' => null,
                'box-ten' => null,
                'interpretation-radio' => '1',
                'inter-one' => 'Test_I_1',
                'inter-two' => 'Test_I_2',
                'inter-three' => 'Test_I_3',
                'inter-four' => null,
                'inter-five' => null,
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
                'box-one' => 'Test_B_1',
                'box-two' => 'Test_B_2',
                'box-three' => 'Test_B_3',
                'box-four' => 'Test_B_4',
                'box-five' => 'Test_B_5',
                'box-six' => null,
                'box-seven' => null,
                'box-eight' => null,
                'box-nine' => null,
                'box-ten' => null,
                'interpretation-radio' => '1',
                'inter-one' => 'Test_I_1',
                'inter-two' => 'Test_I_2',
                'inter-three' => 'Test_I_3',
                'inter-four' => null,
                'inter-five' => null,
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
                'box-one' => 'Test_B_1',
                'box-two' => null,
                'box-three' => null,
                'box-four' => null,
                'box-five' => null,
                'box-six' => null,
                'box-seven' => null,
                'box-eight' => null,
                'box-nine' => null,
                'box-ten' => null,
                'interpretation-radio' => '1',
                'inter-one' => 'Test_I_1',
                'inter-two' => 'Test_I_2',
                'inter-three' => 'Test_I_3',
                'inter-four' => null,
                'inter-five' => null,
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
                'box-one' => 'Test_B_1',
                'box-two' => 'Test_B_2',
                'box-three' => 'Test_B_3',
                'box-four' => 'Test_B_4',
                'box-five' => 'Test_B_5',
                'box-six' => null,
                'box-seven' => null,
                'box-eight' => null,
                'box-nine' => null,
                'box-ten' => null,
                'interpretation-radio' => null,
                'inter-one' => 'Test_I_1',
                'inter-two' => 'Test_I_2',
                'inter-three' => 'Test_I_3',
                'inter-four' => null,
                'inter-five' => null,
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
                'box-one' => 'Test_B_1',
                'box-two' => 'Test_B_2',
                'box-three' => 'Test_B_3',
                'box-four' => 'Test_B_4',
                'box-five' => 'Test_B_5',
                'box-six' => null,
                'box-seven' => null,
                'box-eight' => null,
                'box-nine' => null,
                'box-ten' => null,
                'interpretation-radio' => '1',
                'inter-one' => null,
                'inter-two' => 'Test_I_2',
                'inter-three' => 'Test_I_3',
                'inter-four' => null,
                'inter-five' => null,
            ]);

        $response->assertRedirect('/create_quiz');
        $response->assertSessionHas('int-status', 'Selected Field Must Be Filled In');
    }
}
