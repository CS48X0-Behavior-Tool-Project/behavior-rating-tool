<?php

namespace App\Http\Controllers;

use Bouncer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Api\QuizController;

class PagesController extends Controller
{

    private $qc;
    /**
     * These controller methods simply load up the appropriate views from the pages folder.
     */

   public function __construct() {
     $this->qc = new QuizController();
   }

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
        $animals = DB::table('quiz_questions')
                ->pluck('animal')->unique();

        $quizzes = $this->qc->getAllQuizzes();

        return view('quizzes')->with(['animals'=>$animals, 'quizzes'=>$quizzes]);
    }

    public function getQuizById($id) {

      $quiz = $this->qc->getQuiz($id);

      return view('single_quiz')->with(['options' => $quiz->quiz_question_options, 'video' => $quiz->video]);
    }

    public function getCreateQuiz()
    {
        // search the database for different animal species to populate a radio button list
        $animals = DB::table('quiz_questions')
                ->where('animal', '<>', 'Horse')
                ->pluck('animal')->unique();

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
}
