<?php

namespace App\Http\Controllers\Api;

use Bouncer;
use App\Models\User;
use App\Models\Attempt;
use App\Models\UserAttempt;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\AttemptQuiz;
use App\Models\AttemptAnswerItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getAllUsers()
    {
        // Implement logic to fetch all users
        $users = User::all(); 
        return $users;
    }

    public function getUser($id)
    {
        // Implement logic to fetch user
    }

    public function getUserAttempts($id)
    {
        // Implement logic to fetch user quiz attempts
        $attempts = DB::table('user_attempts')
        ->where('user_id', $id)
        ->get();
        Log::info($attempts);
        return $attempts;
    }

    public function upsertUserAttempts(Request $request)
    {
        // insert if new, update if exist (update behavior and interpretation given an user attempt)

        $user_id = -1;
        $attempt_id = -1;
        $user_attempt_id = -1;
        $attempt_quiz_id = -1;
        $quiz_id = -1;

        $bodyContent = $request->getContent();
        if($bodyContent == null) {
            Log::info([">>> body content is null ", $bodyContent]);
            // create new attempt and corresponding user_attempt
            Log::info(">>> Creating a new attempt.");
            $attempt = new Attempt;
            $attempt->user_id = $request->route('id');
            $attempt->save();
            $attempt_id = $attempt->id;
    
            Log::info([">>> Creating a new user_attempt, where attempt_id is: ", $attempt_id]);
            $userAttempt = new UserAttempt;
            $userAttempt->user_id = $request->route('id');
            $userAttempt->attempt_id = $attempt_id;
            $userAttempt->scores = 0;
            $userAttempt->save();
            Log::info(">>> DONE: user_attempt created !");
            return response()->json(['success' => true, 'attempt_id' => $attempt_id, 'user_attempt_id' => $userAttempt->id], 200);
        }


        // updata / insert user_attempt (and attempt)
        if($request->has('user_attempt_id')) {
            // $attempt_id = select attempt_id from user_attempts where id = $user_attempt_id
            $user_attempt_id = $request->user_attempt_id;
            Log::info([">>> user_attempt_id is not null: ",$user_attempt_id]);
            $user_attempt = UserAttempt::where('id', $user_attempt_id)->first();

            if($user_attempt != null) {
                $attempt_id =  $user_attempt->attempt_id;
                Log::info([">>> attempt_id is ", $attempt_id]);
                if($request->has('scores')) {
                    $user_attempt->scores = $request->scores;
                    $user_attempt->save();
                }
            }
            else {
                Log::info(">>> Error: The given user_attempt_id not eixt in the table user_attempts.");
            }
        }
        else {
            // create new attempt and corresponding user_attempt
            Log::info(">>> Creating a new attempt.");
            $attempt = new Attempt;
            $attempt->user_id = $request->route('id');
            $attempt->save();
            $attempt_id = $attempt->id;
    
            Log::info([">>> Creating a new user_attempt, where attempt_id is: ", $attempt_id]);
            $userAttempt = new UserAttempt;
            $userAttempt->user_id = $request->route('id');
            $userAttempt->attempt_id = $attempt_id;
            $userAttempt->scores = 0;
            if($request->has('scores')) {
                $userAttempt->scores = $request->scores;
            }
            $userAttempt->save();
            $user_attempt_id = $userAttempt->id;
            Log::info(">>> DONE: user_attempt created !");
        }

        // updata / insert attempt_quizzws
        $attempt_quiz = AttemptQuiz::where('attempt_id', $attempt_id)->first();

        if($attempt_quiz != null) {
            $attempt_quiz_id = $attempt_quiz->id;
            Log::info([">>> attempt_quizzes is not null: ", $attempt_quiz_id]);
        }
        else {
            Log::info([">>> creating a new attempt_quiz (attempt_id, quiz_id): ", $attempt_id, $request->quiz_id]);
            $attemptQuiz = new AttemptQuiz;
            $attemptQuiz->attempt_id = $attempt_id;
            $attemptQuiz->quiz_id = $request->quiz_id;
            $attemptQuiz->save();

            $attempt_quiz_id = $attemptQuiz->id;
        }

        // updata / insert attempt_answer_items
        $attempt_answers = AttemptAnswerItem::where('attempt_quiz_id', $attempt_quiz_id)->first();

        if ($attempt_answers != null) {
            // update answers
            Log::info([">>> attempt_answers is not null: ", $attempt_answers]);
            $attempt_answers->behavior_answers = json_encode($request->behavior_answers);
            $attempt_answers->interpretation_answers = json_encode($request->interpretation_answers);
            $attempt_answers->save();

        } 
        else {
            // insert attempt answers
            Log::info([">>> creating a new attempt_answer_items (attempt_quiz_id): ", $attempt_quiz_id]);
            $attempt_answer_item = new AttemptAnswerItem;
            $attempt_answer_item->attempt_quiz_id = $attempt_quiz_id;
            $attempt_answer_item->behavior_answers = json_encode($request->behavior_answers);
            $attempt_answer_item->interpretation_answers = json_encode($request->interpretation_answers);
            $attempt_answer_item->save();
        }

        return response()->json(['success' => true], 200);
    }

    public function deleteUserAllAttempts(Request $request, $id) {
        // delete all the user attempts specify an user_id

        UserAttempt::where('user_id', $id)->delete();
        Attempt::where('user_id', $id)->delete();
    }

    public function createUser(Request $request)
    {
    }

    public function updateUser(Request $request, $id)
    {
        $user = $request->user();
        if (Bouncer::is($user)->an('admin')) {
            $toDelete = User::find($id);
            $toDelete->delete();
            return redirect('/')->with('message', 'Delete succeeded!');
        } else {
            return redirect('/')->with('message', 'Delete failed!');
        }
    }

    public function deleteUser(Request $request, $id)
    {
        // Implement logic to delete user
    }
}
