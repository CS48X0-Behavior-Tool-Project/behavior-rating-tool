<?php

namespace App\Http\Controllers;

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

    public function attemptQuiz($id)
    {
        // TODO: Fetch quiz using passed quiz id
        return view('quiz_attempt', ['id' => $id]);
    }

    public function getCreateQuiz()
    {
        return view('admin_create_quiz');
    }

    public function getAddUser()
    {
        return view('admin_add_user');
    }

    public function getAccountManagement()
    {
        return view('account');
    }
}