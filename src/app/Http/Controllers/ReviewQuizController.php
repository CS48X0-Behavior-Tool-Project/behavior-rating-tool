<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Quiz;
use App\Models\UserAttempt;
use Illuminate\Support\Facades\DB;
use \stdClass;


class ReviewQuizController extends Controller
{
    private $uc;
    private $qc;

    public function __construct() {
        $this->uc = new UserController();
        $this->qc = new QuizController();
    }

    public function getUserQuizzes() {
        if (Auth::user()){
            $id = Auth::id();
            $quizzes = DB::table('users')
            ->select(
                'users.email',
                'quizzes.code as quiz',
                'quizzes.id as quiz_id',
                'attempt_quizzes.created_at',
                'user_attempts.id as user_attempt_id',
                'user_attempts.score',
                'user_attempts.max_score',
                'user_attempts.interpretation_guess',
                'user_attempts.options->time as time'
                )
            ->join('user_attempts', 'users.id', '=', 'user_attempts.user_id')
            ->join('attempt_quizzes', 'user_attempts.attempt_id', '=', 'attempt_quizzes.attempt_id')
            ->join('quizzes', 'quizzes.id', '=', 'attempt_quizzes.quiz_id')
            ->join('attempt_answer_items', 'attempt_answer_items.attempt_quiz_id', '=', 'attempt_quizzes.id')
            ->orderby('quizzes.code')
            ->where('users.id', $id)
            ->get();

            Log::info(['>>> ReviewQuizController - getUserQuizzes: ',$quizzes]);

            $admin_data = $this->getAllStudentQuizzes();

            return view('review_all')->with(['quizzes' => $quizzes, 'admin_data' => $admin_data]);
        }
        else
        {
            return redirect()->route('login')->with('validate', 'Please login first.');
        }
    }

    public function exportUserQuizSummary() {
        $admin_data = $this->getAllStudentQuizzes();
        $csvFile = "Student,Quiz Code,Number of Attempts,Best Score,Interpretation, Best Time\n";

        foreach ($admin_data as $row) {
            $csvFile .= "{$row->email},{$row->code},{$row->attempts}, "." {$row->score}/{$row->max_score},{$row->interpretation_guess}, {$row->time}\n";
        }

        return response($csvFile)
            ->withHeaders([
                'Content-Type' => 'text/csv',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename=student_quiz_summary.csv',
            ]);
    }

    public function exportUserQuizSummaryJson() {
        $admin_data = $this->getAllStudentQuizzes();
        $file = array();
        foreach ($admin_data as $row) {
            $single = new stdClass();
            $single->student = $row->email;
            $single->quiz_code = $row->code;
            $single->number_of_attempts = $row->attempts;
            $single->best_score = $row->score."/".$row->max_score;
            $single->interpretation_guess = $row->interpretation_guess; 
            $single->best_time = $row->time;
            array_push($file, $single);
        }

        return response($file)
            ->withHeaders([
                'Content-Type' => 'application/json; charset=utf-8',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename=student_quiz_summary.json',
            ]);
    }

    public function getReviewQuizByUserAttemptId($userAttemptId)
    {
        $quiz = $this->getQuizByUserAttemptID($userAttemptId);
        $studentAnswers = $this->getAnswers($userAttemptId);

        //extract the attempt number
        $attemptNumber = UserAttempt::where('attempt_id','=',$userAttemptId)->pluck('attempt_number')->toArray()[0];

        return view('review_quiz')->with(['code' => $quiz->code,
                                            'options' => $quiz->quiz_question_options,
                                            'video' => $quiz->video,
                                            'behaviour_score' => $studentAnswers->score,
                                            'max_behaviour_score' => $studentAnswers->max_score,
                                            'interpretation_score' => $studentAnswers->interpretation_guess,
                                            'attempt' => $attemptNumber,
                                            'behavior_answers' => json_decode($studentAnswers->behavior_answers, true),
                                            'interpretation_answers' => json_decode($studentAnswers->interpretation_answers, true),
                                            'time' => isset($studentAnswers->time)?$studentAnswers->time:'N/A']);
    }

    public function getReviewUserQuiz($id, $quiz) {
        if($quiz == 'all') {
            $quiz = '%';
        }
        if($id == 'all') {
            $id = '%';
        }
        if (Auth::user()){
            $quizzes = DB::table('users')
            ->select(
                'users.id',
                'users.email',
                'users.first_name',
                'users.last_name',
                'quizzes.code as quiz',
                'quizzes.id as quiz_id',
                'attempt_quizzes.created_at',
                'user_attempts.id as user_attempt_id',
                'user_attempts.score',
                'user_attempts.max_score',
                'user_attempts.interpretation_guess',
                'user_attempts.options->time as time'
                )
            ->join('user_attempts', 'users.id', '=', 'user_attempts.user_id')
            ->join('attempt_quizzes', 'user_attempts.attempt_id', '=', 'attempt_quizzes.attempt_id')
            ->join('quizzes', 'quizzes.id', '=', 'attempt_quizzes.quiz_id')
            ->join('attempt_answer_items', 'attempt_answer_items.attempt_quiz_id', '=', 'attempt_quizzes.id')
            ->orderby('quizzes.code')
            ->where('users.id','LIKE', $id)
            ->where('quizzes.code', 'LIKE', $quiz)
            ->get();

            Log::info(['>>> ReviewQuizController - getReviewUserQuiz: ',$quizzes]);
            
            return view('review_all')->with(['quizzes' => $quizzes, 'admin_data' => 'STUDENT']);  // if admin_data==STUDENT, means admin revice single user quiz
        }
        else
        {
            return redirect()->route('login')->with('validate', 'Please login first.');
        }
    }

    private function getQuizByUserAttemptID($userAttemptId)
    {
        // Implement logic to fetch quiz by userAttemptID
        $quizID = DB::table('quizzes')
            ->select('quizzes.id')
            ->join('attempt_quizzes', 'attempt_quizzes.quiz_id', '=', 'quizzes.id')
            ->join('user_attempts', 'user_attempts.attempt_id', '=', 'attempt_quizzes.attempt_id')
            ->where('user_attempts.id', $userAttemptId)
            ->first();

        $qid = $quizID->id;
        $quizOps = DB::table('quiz_options')
            ->where('quiz_id', $qid)
            ->get();

        $quizInfor = Quiz::find($qid);
        $quizInfor->quiz_question_options = $quizOps;

        return $quizInfor;
    }

    private function getAnswers($userAttemptId) {

        $data = DB::table('user_attempts')
        ->select(
            'quizzes.code as quiz',
            'quizzes.id as quiz_id',
            'attempt_quizzes.created_at',
            'user_attempts.score',
            'user_attempts.max_score',
            'user_attempts.interpretation_guess',
            'user_attempts.options->time as time',
            'attempt_answer_items.id as attempt_answer_id',
            'attempt_answer_items.behavior_answers',
            'attempt_answer_items.interpretation_answers'
            )
        ->join('attempt_quizzes', 'user_attempts.attempt_id', '=', 'attempt_quizzes.attempt_id')
        ->join('quizzes', 'quizzes.id', '=', 'attempt_quizzes.quiz_id')
        ->join('attempt_answer_items', 'attempt_answer_items.attempt_quiz_id', '=', 'attempt_quizzes.id')
        ->where('user_attempts.id', $userAttemptId)
        ->first();

        return $data;
    }

    private function getAllStudentQuizzes() {
        $data = DB::table('users')
        ->select(
            'users.id',
            'users.email',
            'users.first_name',
            'users.last_name',
            'quizzes.code',
            'user_attempts.id as user_attempt_id',
            'user_attempts.score',
            'user_attempts.max_score',
            'user_attempts.interpretation_guess',
            'user_attempts.options->time as time',
            DB::raw('count(*) over (partition by users.email, quizzes.code) as attempts'),
            DB::raw('row_number() over (partition by users.email, quizzes.code order by user_attempts.interpretation_guess desc, user_attempts.score desc) as best_index')
        )
        ->join('user_attempts', 'user_attempts.user_id','=','users.id')
        ->join('attempt_quizzes', 'attempt_quizzes.attempt_id','=','user_attempts.attempt_id')
        ->join('quizzes', 'quizzes.id','=','attempt_quizzes.quiz_id')
        ->get();

        $data = $data->where('best_index', '=', '1');
        Log::info(['>>> review-by-admin: getAllStudentQuizzes: ',$data]);
        return $data;
    }
}
