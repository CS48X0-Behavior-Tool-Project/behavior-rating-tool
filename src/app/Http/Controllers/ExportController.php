<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Bouncer;

class ExportController extends Controller
{
    // for all export features

    public function exportUsers() {

        // Only Admins should be allowed to access this resource
        if (Auth::user() and Bouncer::is(request()->user())->an('admin')){
            $table = User::all();
            $csvFile = "username,First Name,Last Name,Email\n";

            foreach($table as $row) {
                $csvFile .= "{$row['name']},{$row['first_name']},{$row['last_name']},{$row['email']}\n";
            }

            return response($csvFile)
                ->withHeaders([
                    'Content-Type' => 'text/csv',
                    'Cache-Control' => 'no-store, no-cache',
                    'Content-Disposition' => 'attachment; filename=users.csv',
                ]);
        }
        else
        {
            return redirect()->route('login')->with('validate', 'Please login first.');
        }
    }

}
