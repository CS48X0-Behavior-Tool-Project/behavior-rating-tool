<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizController extends Controller
{
    public function getAllQuizzes()
    {
        // Implement logic to fetch all quizzes
        $quizzes = Quiz::all();    // TODO: need to add the quiz answer option list
        return $quizzes;
    }

    public function getQuiz($id)
    {
        // Implement logic to fetch quiz
        return Quiz::find($id);
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