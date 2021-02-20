<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Quiz;

class QuizController extends Controller
{
    public function getAllQuizzes()
    {
        // Implement logic to fetch all quizzes
        $quizzes = Quiz::all(); 
        return $quizzes;
    }

    public function getQuiz($id)
    {
        // Implement logic to fetch quiz

        $quizOps = DB::select('select * from quiz_question_options where quiz_question_id = ?', [$id]);
        $quizInfor = Quiz::find($id);
        $quizInfor->quiz_question_options = $quizOps;
        return $quizInfor;
    }

    public function getQuizAttempts($id)
    {
        // Implement logic to fetch quiz attempts
        
    }

    public function createQuiz(Request $request)
    {
        // Implement logic to create quiz
    }

    public function updateQuiz(Request $request, $id)
    {
        // Implement logic to update quiz
    }

    public function deleteQuiz($id)
    {
        // Implement logic to delete quiz
        Quiz::find($id)->delete();
    }
}