<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewQuizController extends Controller
{
    private $uc;
    private $qc;

    public function __construct() {
        $this->uc = new UserController();
        $this->qc = new QuizController();
    }

    public function getUserQuizzes() {
        $id = Auth::id();
        $quizzes = $this->uc->getUserQuizScores($id);
        Log::info(['>>> ReviewQuizController - getUserQuizzes($user_id): ',$id,$quizzes]);

        return view('review_quiz')->with('quizzes',$quizzes);
    }

    public function getReviewbyUserAttemptID($id) {
        $data = DB::table('user_attempts')
        ->select(
            'quizzes.code as quiz', 
            'quizzes.id as quiz_id',
            'attempt_quizzes.created_at', 
            'user_attempts.score',
            'user_attempts.max_score',
            'user_attempts.interpretation_guess',
            'user_attempts.options',
            'attempt_answer_items.id as attempt_answer_id',
            'attempt_answer_items.behavior_answers',
            'attempt_answer_items.interpretation_answers'
            )
        ->join('attempt_quizzes', 'user_attempts.attempt_id', '=', 'attempt_quizzes.attempt_id')
        ->join('quizzes', 'quizzes.id', '=', 'attempt_quizzes.quiz_id')
        ->join('attempt_answer_items', 'attempt_answer_items.attempt_quiz_id', '=', 'attempt_quizzes.id')
        ->where('user_attempts.id', $id)
        ->get();

        return $data;
    }
}
