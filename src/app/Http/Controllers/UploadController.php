<?php

namespace App\Http\Controllers;

use Bouncer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

use App\Auth\EmailAuthentication;
use App\Models\UserLoginToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use SimpleXLSX;

$count_message;
$add_user_message;
$count_msg_with_duplicates;
$count_msg_no_new;
$add_user_err_message;

class UploadController extends Controller
{

    public function __construct()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    /**
     * Called when /add_user gets a post request from the forms.
     */
    public function upload()
    {

        if (request()->has('mycsv')) {
            return $this->uploadFile();
        } else if (request()->has('add_single_user')) {
            return $this->uploadUser();
        } else {
            return redirect()->route('add_user_route');
        }
    }

    // TODO: allow different file types

    /**
     * Extract data from file.
     */
    public function uploadFile()
    {
        $extension = request()->mycsv->getClientOriginalExtension();

        switch ($extension) {
          case 'csv':
            $data = array_map('str_getcsv', file(request()->mycsv));
            break;
          case 'xlsx':
            $data = SimpleXLSX::parse(request()->mycsv)->rows();
            break;
          default:
            return redirect()->back()
                ->with('file_error_message', 'Invalid File Type (must be .csv or .xlsx)');
        }

        unset($data[0]);

        $new_user_count = 0;
        $failed_entry_count = 0;
        $duplicateEmailsArray = [];

        foreach ($data as $value) {
            $firstName = $value[0];
            $lastName = $value[1];
            $email = $value[2];

            $entry = [
                "first_name" => $firstName,
                "last_name" => $lastName,
                "email" => $email
            ];

            $validator = Validator::make(
                $entry,
                [
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|unique:users|email:rfc'
                ]
            );

            if ($validator->fails()) {
                $failedRules = $validator->failed();
                if (isset($failedRules['email']['Unique'])) {
                    array_push($duplicateEmailsArray, $email);
                }
                $failed_entry_count++;
            } else {
                $this->dbInsert($firstName, $lastName, $email, 'student');
                $new_user_count++;
            }
        }

        $user_count_message = $new_user_count . '/' . count($data) . " users added.";

        return redirect()->back()
            ->with('user_count_message', $user_count_message)
            ->with('duplicate_email_error', $duplicateEmailsArray);
    }

    /**
     * Upload a single user from the form.
     */
    public function uploadUser()
    {
        global $add_user_message;

        $firstName = request()->input('first_name');
        $lastName = request()->input('last_name');
        $email = request()->input('email');
        $role = request()->input('role');

        request()
            ->validate([
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|unique:users|email:rfc',
                'role' => 'required'
            ]);

        $this->dbInsert($firstName, $lastName, $email, $role);

        return redirect()->back()->with('add_message', 'New user added!');
    }

    /**
     * Insert new user data into the database.
     */
    public function dbInsert($firstName, $lastName, $email, $role)
    {
        $username = strtolower(substr($firstName, 0, 1) . $lastName); //needed for now, until we get rid of registration tab
        $user = User::create([
            'password' => Hash::make('password'),
            'email' => $email,
            'name' => $username,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'options' => json_encode((object)[])
        ]);

        $user->assign($role);

        // send an email to the new user
        $this->emailNewUser($email);
    }

    /**
     * Send an email to the new user upon account creation, directing them to the website.
     */
    public function emailNewUser($email)
    {

        $auth = new EmailAuthentication($email);
        $auth->requestLink();
    }

    /**
     * Validate the token sent in the email link.
     */
    public function validateToken(Request $request, UserLoginToken $token)
    {
        $token->delete();

        if ($token->isExpired()) {
            return;
        }
        //login the user and redirect them to the account confirmation page where they set their password and take the survey
        Auth::login($token->user);
        return redirect()->route('confirmation_route');
    }
}
