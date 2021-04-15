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
use Illuminate\Support\Facades\Auth;

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
        return User::find($id);
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

        $user_id = $request->route('id') ?? -1;
        $attempt_id = -1;
        $user_attempt_id = $request->user_attempt_id ?? -1;
        $attempt_quiz_id = -1;
        $quiz_id = $request->quiz_id ?? -1;

        Log::info($user_attempt_id);
        Log::info($quiz_id);

        $bodyContent = $request->getContent();
        if($bodyContent == null) {
            Log::info([">>> body content is null ", $bodyContent]);
            // create new attempt and corresponding user_attempt
            Log::info(">>> Creating a new attempt.");
            $attempt = new Attempt;
            $attempt->user_id = $request->user_id;
            $attempt->save();
            $attempt_id = $attempt->id;

            Log::info([">>> Creating a new user_attempt, where attempt_id is: ", $attempt_id]);
            $userAttempt = new UserAttempt;
            $userAttempt->user_id = $request->user_id;
            $userAttempt->attempt_id = $attempt_id;
            $userAttempt->score = 0;
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
                if($request->has('score')) {
                    $user_attempt->score = $request->score;
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
            $attempt->user_id = $request->user_id;
            $attempt->save();
            $attempt_id = $attempt->id;

            Log::info([">>> Creating a new user_attempt, where attempt_id is: ", $attempt_id]);
            $userAttempt = new UserAttempt;
            $userAttempt->user_id = $request->user_id;
            $userAttempt->attempt_id = $attempt_id;
            $userAttempt->score = 0;
            if($request->has('score')) {
                $userAttempt->score = $request->score;
            }
            $userAttempt->max_score = $request->max_score;
            $userAttempt->interpretation_guess = $request->interpretation_guess;
            $userAttempt->attempt_number = $request->attempt;
            if ($request->has('time')) {
                $jsonObj['time'] = $request->time;
                $userAttempt->options = json_encode($jsonObj);
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
            if(!$request->has('quiz_id')) {
                return response()->json(['failed' => 'no quiz_id specified'], 500);
            }
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

        // NOTE: commenting this out for now, scores are being calculated in the QuizAttemptController at the moment
        //$scores = $this->getScores($quiz_id, $request->behavior_answers, $request->interpretation_answers);

        // update user_attempts scores
        /*DB::table('user_attempts')
        ->where('id', $user_attempt_id)
        ->update(['scores' => $scores["scores"], 'behavior_scores' => $scores["behavior_scores"], 'interpretation_guess' => $scores["interpretation_guess"]]);

        Log::info(['>>>>> scores: ', $scores]);*/

        return response()->json(['success' => true, 200]);// 'score' => $scores], 200);
    }

    /*private function getScores($quiz_id, $behavior_answers, $interpretation_answers) {

        $behavior_scores = 0;
        $interpretation_guess = true;
        $scores = 0;

        $behaviours = DB::table('quiz_options')
        ->where('quiz_id', $quiz_id)
        ->where('type', 'behaviour')
        ->get();

        $interpretations = DB::table('quiz_options')
        ->where('quiz_id', $quiz_id)
        ->where('type', 'interpretation')
        ->get();

        foreach ($behavior_answers as $be_answered) {
            foreach ($behaviours as $be_solution) {
                if ($be_answered == $be_solution->title) {
                    if ($be_solution->is_solution == true) {
                        $scores += $be_solution->marking_scheme;
                        $behavior_scores += $be_solution->marking_scheme;
                    }
                    else {
                        $scores -= $be_solution->marking_scheme;
                        $behavior_scores -= $be_solution->marking_scheme;
                    }
                }
            }
        }

        foreach ($interpretation_answers as $in_answered) {
            foreach ($interpretations as $in_solution) {
                if ($in_answered == $in_solution->title) {
                    if ($in_solution->is_solution == true) {
                        $scores += $in_solution->marking_scheme;
                        $interpretation_guess = $interpretation_guess and true;
                    }
                    else {
                        $scores -= $in_solution->marking_scheme;
                        $interpretation_guess = false;
                    }
                }
            }
        }

        return ['behavior_scores' => $behavior_scores, 'interpretation_guess' => $interpretation_guess, 'scores' => $scores] ;
    }*/

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
        User::find($id)->delete();
    }
}
