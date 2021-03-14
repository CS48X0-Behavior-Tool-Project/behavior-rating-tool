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
        
            $data = DB::select('select 
                            u.name, 
                            u.email, 
                            q.code as quiz, 
                            aq.created_at as conducted, 
                            ua.scores
                        from users u
                        inner join user_attempts ua
                            on u.id = ua.user_id
                        inner join attempt_quizzes aq
                            on ua.attempt_id = aq.attempt_id
                        inner join quizzes q
                            on q.id = aq.quiz_id
                        inner join attempt_answer_items aai
                            on aai.attempt_quiz_id = aq.id
                        order by u.name;');

            $csvFile = "username, Email, Quiz Code, Attempts, Scores\n";

            foreach($data as $row) {
                // $csvFile .= "{$row['name']},{$row['email']},{$row['quiz']},{$row['conducted']},{$row['scores']}\n";
                $csvFile .= "{$row->name},{$row->email},{$row->quiz},{$row->conducted},{$row->scores}\n";
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
