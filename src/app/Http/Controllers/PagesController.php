<?php

namespace App\Http\Controllers;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\QuizController;

use App\Models\User;
use App\Models\Quiz;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{

    private $qc;
    /**
     * These controller methods simply load up the appropriate views from the pages folder.
     */

    public function __construct()
    {
        $this->qc = new QuizController();
        $this->middleware('auth')->except('getLoginPage');
    }

    public function getLoginPage()
    {
        if (Auth::user()) {
            return redirect()->route('home_route');
        }
        return view('auth.login');
    }

    public function getHomePage()
    {
        return view('home');
    }

    /**
     * ! Token authorization is required
     * @return View|Factory 
     * @throws BindingResolutionException 
     */
    public function getConfirmationPage()
    {
        return view('account_creation');
    }

    public function getQuizList()
    {
        $animals = Quiz::all()->pluck('animal')->unique();

        $quizzes = $this->qc->getAllQuizzes();

        return view('quizzes')->with(['animals' => $animals, 'quizzes' => $quizzes]);
    }

    public function getQuizById($id)
    {
        $quiz = $this->qc->getQuiz($id);

        return view('single_quiz')->with([
            'code' => $quiz->code, 'options' => $quiz->quiz_question_options,
            'video' => $quiz->video
        ]);
    }

    public function getCreateQuiz()
    {
        if (request()->user()->can('create', Quiz::class)) {
            // search the database for different animal species to populate a radio button list
            $animals = Quiz::all()->pluck('animal')->unique();

            return view('admin_create_quiz')->with('animals', $animals);
        }
        return redirect()->back();
    }

    public function getAddUser()
    {
        if (request()->user()->can('create', User::class)) {
            return $this->adminView(request(), 'admin_add_user');
        }
        return redirect()->back();
    }

    public function getAccountManagement()
    {
        return view('account');
    }

    public function exportData()
    {
        if (request()->user()->can('export-users')) {
            return view('export');
        }
        return redirect()->back();
    }
}
