<?php

namespace App\Http\Controllers;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\UserController;

use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizOption;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{

    private $uc;
    private $qc;

    public function __construct()
    {
        $this->uc = new UserController();
        $this->qc = new QuizController();
    }

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
    public function getUsers()
    {
        $users = $this->uc->getAllUsers();

        return $this->adminView(request(), 'admin_view_all_users')->with('users', $users);
    }

    public function getUserById($id)
    {
        $user = $this->uc->getUser($id);

        return $this->adminView(request(), 'single_user')->with('user', $user);
    }

    public function attemptQuiz($id)
    {
          // TODO: Fetch quiz using passed quiz id
          // Using the ID, we should fetch the quiz
          // which should contain path directory OR URL
          if (!file_exists(public_path('/assets/videos/' . $id . '.mp4'))) {
              abort(404);
          } else {
              return view('quiz_attempt', ['id' => $id]);
          }
      }

    public function getQuizById($id) {

      $quiz = $this->qc->getQuiz($id);

      return view('single_quiz')->with(['code' => $quiz->code, 'options' => $quiz->quiz_question_options,
        'video' => $quiz->video]);

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

    // Selector page for
    public function getEditQuiz()
    {
        $quizzes = Quiz::all();

        return view('edit_quiz_selector')->with('quizzes',$quizzes);
    }

    // Edits a single quiz
    public function getEditQuizByID($id)
    {
        $animals = Quiz::all()->pluck('animal')->unique();
        $quiz = Quiz::find($id);
        $b_options = QuizOption::where('quiz_id', '=', $quiz->id)->where('type', '=', 'behaviour')->get();
        $i_options = QuizOption::where('quiz_id', '=', $quiz->id)->where('type', '=', 'interpretation')->get();

        return $this->adminView(request(), 'admin_edit_quiz')->with(['animals' => $animals,
            'quiz' => $quiz, 'b_options' => $b_options, 'i_options' => $i_options]);
    }

    public function getAddUser()
    {
        if (request()->user()->can('create', User::class)) {
            return view('admin_add_user');
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
