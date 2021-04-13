<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Bouncer;
use Illuminate\Support\Facades\Log;
use \stdClass;

class ExportController extends Controller
{
    // for all export features

    public function exportUsers()
    {
        // Only Admins should be allowed to access this resource | have ability to export-users
        if (Auth::user() and request()->user()->can('export-users')) {
            $users = $this->getAllUsers();
            $csvFile = "Email,First Name,Last Name,Grad_Year,Experience,Role\n";

            foreach ($users as $row) {
                $csvFile .= "{$row->email},{$row->first_name},{$row->last_name},{$row->grad_year},{$row->experience},{$row->role}\n";
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

    public function exportUsersJson() {
        $users = $this->getAllUsers();
        $file = array();
        foreach ($users as $row) {
            $single = new stdClass();
            $single->email = $row->email;
            $single->first_name = $row->first_name;
            $single->last_name = $row->last_name;
            $single->grad_year = $row->grad_year;
            $single->experience = $row->experience; 
            $single->role = $row->role;
            array_push($file, $single);
        }

        return response($file)
            ->withHeaders([
                'Content-Type' => 'application/json; charset=utf-8',
                'Cache-Control' => 'no-store, no-cache',
                'Content-Disposition' => 'attachment; filename=users.json',
            ]);
    }

    private function getAllUsers(){
        $users = DB::table('users')
        ->select(   
            'users.email',
            'users.first_name',
            'users.last_name',
            'users.options->grad_year as grad_year',
            'users.options->experience as experience',
            'assigned_roles.entity_id',
            'assigned_roles.id',
            'roles.id',
            'roles.title as role'
        )
        ->leftJoin('assigned_roles', 'assigned_roles.entity_id', '=', 'users.id')
        ->leftJoin('roles', 'roles.id', '=', 'assigned_roles.role_id')
        ->get();

        Log::info($users);
        return $users;
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
                    'user_attempts.interpretation_guess',
                    'user_attempts.options->time as time'
                    )
                ->join('user_attempts', 'users.id', '=', 'user_attempts.user_id')
                ->join('attempt_quizzes', 'user_attempts.attempt_id', '=', 'attempt_quizzes.attempt_id')
                ->join('quizzes', 'quizzes.id', '=', 'attempt_quizzes.quiz_id')
                ->join('attempt_answer_items', 'attempt_answer_items.attempt_quiz_id', '=', 'attempt_quizzes.id')
                ->orderby('users.email')
                ->get();
                Log::info($data);
            $csvFile = " User, Quiz Code, Attempted Time, Time Spent(sec), Scores, Max Scores, Interpretation Guess\n";

            if(count($data)>0) {
                foreach($data as $row) {
                    $csvFile .= "{$row->email},{$row->quiz},{$row->created_at},{$row->time},{$row->score},{$row->max_score},{$row->interpretation_guess}\n";
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
