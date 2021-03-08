<?php

namespace App\Http\Controllers;

use Bouncer;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\QuizController;

use App\Models\Quiz;
use App\Models\Attempt;
use App\Models\AttemptQuiz;

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

      //array holding every attempt id the current user has done
      $userAttempts = Attempt::where('user_id','=',auth()->user()->id)->pluck('id')->toArray();

      //count how many attempts the user has already taken on the quiz and send it to frontend
      // TODO: there has to be a cleaner way to do this with Eloquent but can't find it right now
      // NOTE: I think the best solution would be to combine the 'attempts' and 'attempt_quizzes' tables
      $attemptNumber = 1;
      foreach ($userAttempts as $key => $value) {
        $quizId = AttemptQuiz::where('attempt_id','=',$value)->limit(1)->pluck('quiz_id')->toArray();
        if (in_array($id, $quizId)) {
          $attemptNumber++;
        }
      }

      return view('single_quiz')->with(['code' => $quiz->code, 'options' => $quiz->quiz_question_options,
        'video' => $quiz->video, 'attempt' => $attemptNumber]);
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
