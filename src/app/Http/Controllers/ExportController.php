<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Bouncer;

class ExportController extends Controller
{
    // for all export features

    public function exportUsers()
    {
        // Only Admins should be allowed to access this resource
        if (Auth::user() and request()->user()->can('export-users')) {
            $table = User::all();
            $csvFile = "username,First Name,Last Name,Email\n";

            foreach ($table as $row) {
                $csvFile .= "{$row['name']},{$row['first_name']},{$row['last_name']},{$row['email']}\n";
            }

            return response($csvFile)
                ->withHeaders([
                    'Content-Type' => 'text/csv',
                    'Cache-Control' => 'no-store, no-cache',
                    'Content-Disposition' => 'attachment; filename=users.csv',
                ]);
        } else {
            return redirect()->route('login_page')->with('validate', 'Please login first.');
        }
    }

    public function exportUserAttempts() { 

        // Only Admins should be allowed to access this resource
        if (Auth::user() and request()->user()->can('export-student-quizzes')){

            $data = DB::table('users')
                ->select(
                    'users.email', 
                    'quizzes.code as quiz', 
                    'attempt_quizzes.created_at', 
                    'user_attempts.score',
                    'user_attempts.max_score',
                    'user_attempts.interpretation_guess'
                    )
                ->leftJoin('user_attempts', 'users.id', '=', 'user_attempts.user_id')
                ->leftJoin('attempt_quizzes', 'user_attempts.attempt_id', '=', 'attempt_quizzes.attempt_id')
                ->leftJoin('quizzes', 'quizzes.id', '=', 'attempt_quizzes.quiz_id')
                ->leftJoin('attempt_answer_items', 'attempt_answer_items.attempt_quiz_id', '=', 'attempt_quizzes.id')
                ->orderby('users.email')
                ->get();

            $csvFile = " User, Quiz Code, Attempted Time, Scores, Max Scores, Interpretation Guess\n";

            if(count($data)>0) {
                foreach($data as $row) {
                    $csvFile .= "{$row->email},{$row->quiz},{$row->created_at},{$row->score},{$row->max_score},{$row->interpretation_guess}\n";
                }
            }

            return response($csvFile)
                ->withHeaders([
                    'Content-Type' => 'text/csv',
                    'Cache-Control' => 'no-store, no-cache',
                    'Content-Disposition' => 'attachment; filename=user_quizzes.csv',
                ]);
        }
        else
        {
            return redirect()->route('login')->with('validate', 'Please login first.');
        }
    }

}
