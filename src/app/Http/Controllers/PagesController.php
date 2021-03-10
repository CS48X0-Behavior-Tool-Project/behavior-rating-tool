<?php

namespace App\Http\Controllers;

use Bouncer;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\UserController;

use App\Models\Quiz;

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
        $animals = Quiz::all()->pluck('animal')->unique();

        $quizzes = $this->qc->getAllQuizzes();

        return view('quizzes')->with(['animals'=>$animals, 'quizzes'=>$quizzes]);
    }

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
        // search the database for different animal species to populate a radio button list
        $animals = Quiz::all()->pluck('animal')->unique();

        return $this->adminView(request(), 'admin_create_quiz')->with('animals',$animals);
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

    public function exportData()
    {
        return view('export');
    }
}
