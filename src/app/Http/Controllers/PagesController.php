<?php

namespace App\Http\Controllers;

use Bouncer;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\QuizController;

use App\Models\Quiz;
use App\Models\QuizOption;

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
        $animals = Quiz::all()->pluck('animal')->unique();

        $quizzes = $this->qc->getAllQuizzes();

        return view('quizzes')->with(['animals'=>$animals, 'quizzes'=>$quizzes]);
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
        $options = QuizOption::where('quiz_id', '=', $quiz->id)->get();

        return $this->adminView(request(), 'admin_edit_quiz')->with(['animals' => $animals,
            'quiz' => $quiz, 'options' => $options]);
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
