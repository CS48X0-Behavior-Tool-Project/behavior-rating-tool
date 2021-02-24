<?php

namespace App\Http\Controllers;

use Bouncer;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * These controller methods simply load up the appropriate views from the pages folder.
     */

    public function getLoginPage()
    {
        return view('auth.login');
    }

    public function getHomePage()
    {
        return view('home');
    }

    public function getConfirmationPage()
    {
        return view('account_creation');
    }

    public function getQuizList()
    {
        return view('quizzes');
    }

    public function getCreateQuiz()
    {
        /*$animals = array(
          'Cow',                //non-horse animals will be added to this array
          'Chicken',            //these are just tests for now
          'Dog',
          'Kitty Cat',
        );  */

        $animals = array();

        // TODO: search the database for different animal species to populate a radio button list

        return $this->adminView(request(), 'admin_create_quiz')->with('animals', $animals);
    }

    public function getAddUser()
    {
        return $this->adminView(request(), 'admin_add_user');
    }

    public function getAccountManagement()
    {
        return view('account');
    }

    private function adminView(Request $request, $path)
    {
        if ($request->user()->isAn('admin')) {
            return view($path);
        } else {
            return abort(404);
        }
    }
}
