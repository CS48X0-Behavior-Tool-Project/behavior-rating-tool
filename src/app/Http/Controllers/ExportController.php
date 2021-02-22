<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Auth;

class ExportController extends Controller
{
    // for all export features
    
    public function exportUsers() {
        
        if (Auth::user()){
            $table = User::all();
            $filename = "users.csv";
            $handle = fopen($filename, 'w+');

            fputcsv($handle, array('username', 'First Name', 'Last Name', 'E-mail'));

            foreach($table as $row) {
                fputcsv($handle, array($row['name'], $row['first_name'], $row['last_name'], $row['email']));
            }
        
            fclose($handle);
        
            $headers = array(
                'Content-Type' => 'text/csv',
            );
        
            return Response::download($filename, 'users.csv', $headers);
        }
        else
        {
            return redirect()->route('login')->with('validate', 'Please login first.');
        }
    }

}
