<?php

namespace App\Http\Controllers;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\QuizController;

use App\Models\User;
use App\Models\Quiz;

use App\Models\Attempt;
use App\Models\AttemptQuiz;

use App\Models\QuizOption;
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

        //array holding every attempt id the current user has done
        $userAttempts = Attempt::where('user_id','=',auth()->user()->id)->pluck('id')->toArray();

        $attemptsPerQuiz = array();
        $quizIDs = Quiz::all()->pluck('id')->toArray();

        foreach ($quizIDs as $key => $value) {

            //count how many attempts the user made on each quiz
            $attemptNumber = 0;
            foreach ($userAttempts as $key2 => $value2) {
              $quizId = AttemptQuiz::where('attempt_id','=',$value2)->limit(1)->pluck('quiz_id')->toArray();
              if (in_array($value, $quizId)) {
                $attemptNumber++;
              }
            }

            //key for this array is the quiz id, value is the number of attempts the user has on that quiz
            $attemptsPerQuiz[$value] = $attemptNumber;
        }

        //generating list of unique attempt values for the filter
        $uniqueAttempts = array_unique($attemptsPerQuiz);
        sort($uniqueAttempts);

        //key will be the quiz ID, values will be array of scores from each attempt
        $quizScores = array();
        foreach ($attemptsPerQuiz as $key => $value) {

          if ($value > 0) {
            $scoreList = array();
            foreach ($userAttempts as $key => $value) {
              // code...
            }

          }


        }

        print_r($attemptsPerQuiz);
        //return view('quizzes')->with(['animals' => $animals, 'quizzes' => $quizzes,
          //'attempts' => $attemptsPerQuiz, 'uniqueAttempts' => $uniqueAttempts]);
    }

    public function getQuizById($id)
    {
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
        'video' => $quiz->video, 'attempt' => $attemptNumber, 'time' => microtime(true)]);
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

        // TODO: might want to instead check if the user can EDIT the quiz, not create
        if (request()->user()->can('create', Quiz::class)) {
            return view('admin_edit_quiz')->with(['animals' => $animals,
                'quiz' => $quiz, 'b_options' => $b_options, 'i_options' => $i_options]);
        }
        return redirect()->back();
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
