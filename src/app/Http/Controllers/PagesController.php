<?php

namespace App\Http\Controllers;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\UserController;

use App\Models\User;
use App\Models\Quiz;

use App\Models\Attempt;
use App\Models\AttemptQuiz;
use App\Models\UserAttempt;

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
        $this->middleware('auth')->except('getLoginPage');
    }

    /**
     * These controller methods simply load up the appropriate views from the pages folder.
     */

    public function getLoginPage()
    {
        if (Auth::user()) {
            return redirect()->route('quizzes_route');
        }
        return view('auth.login');
    }

    public function getQuizList()
    {
        $animals = Quiz::all()->pluck('animal')->unique();

        $quizzes = $this->qc->getAllQuizzes();

        //array holding every attempt id the current user has done
        $userAttempts = Attempt::where('user_id','=',auth()->user()->id)->pluck('id')->toArray();

        $attemptsPerQuiz = array();
        $quizIDs = Quiz::all()->pluck('id')->toArray();

        $attemptIdPerQuiz = array();

        foreach ($quizIDs as $key => $value) {
            $attemptIdList = array();

            //count how many attempts the user made on each quiz
            $attemptNumber = 0;
            foreach ($userAttempts as $key2 => $value2) {
              $quizId = AttemptQuiz::where('attempt_id','=',$value2)->limit(1)->pluck('quiz_id')->toArray();
              if (in_array($value, $quizId)) {
                $attemptNumber++;
                $attemptIdList[] = $value2;
              }
            }

            //array has quiz id as key, and a list of each attempt id as value
            $attemptIdPerQuiz[$value] = $attemptIdList;

            //key for this array is the quiz id, value is the number of attempts the user has on that quiz
            $attemptsPerQuiz[$value] = $attemptNumber;
        }

        //generating list of unique attempt values for the filter
        $uniqueAttempts = array_unique($attemptsPerQuiz);
        sort($uniqueAttempts);

        $attemptScorePerQuiz = array();

        foreach ($attemptIdPerQuiz as $quizID => $attemptIDs) {
          if (!empty($attemptIDs)) {
            $attemptScoreList = array();
            foreach ($attemptIDs as $attemptID) {
              $attemptScoreList[$attemptID] = UserAttempt::where('attempt_id', '=', $attemptID)->get()->toArray()[0];
            }
            $attemptScorePerQuiz[$quizID] = $attemptScoreList;
          }
        }

        $bestBehaviourScorePerQuiz = array();
        $maxBehaviourScoresList = array();

        $bestInterpretationScorePerQuiz = array();

        // $value is an array of UserAttempts objects, for example below
        // 1 => [ 'id' => 1, 'attempt_id' => 1, 'user_id' => 2, 'score' => 4 ],
        // 2 => [ 'id' => 2, 'attempt_id' => 2, 'user_id' => 2, 'score' => 5 ]
        foreach ($attemptScorePerQuiz as $quizID => $value) {
            // Sort the two columns descending (highest to lowest) to achieve the best attempt overall
            array_multisort(array_column($value, 'score'), SORT_DESC, array_column($value, 'interpretation_guess'), SORT_DESC, $value);
            $bestBehaviourScorePerQuiz[$quizID] = $value[0]['score'];
            $maxBehaviourScoresList[$quizID] = $value[0]['max_score'];
            $bestInterpretationScorePerQuiz[$quizID] = $value[0]['interpretation_guess'];
        }

        return view('quizzes')->with(['animals' => $animals, 'quizzes' => $quizzes,
          'attempts' => $attemptsPerQuiz, 'uniqueAttempts' => $uniqueAttempts, 'bestBehaviourScores' => $bestBehaviourScorePerQuiz,
          'bestInterpretationScores' => $bestInterpretationScorePerQuiz, 'maxBehaviourScores' => $maxBehaviourScoresList]);
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

    public function getUsers()
    {
        if (request()->user()->can('view-users-page')){
            $users = $this->uc->getAllUsers();

            return view('admin_view_all_users')->with('users', $users);
        } else {
            abort(403);
        }

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

    public function getCreateQuiz()
    {
        if (request()->user()->can('create-quizzes')) {
            
            // search the database for different animal species to populate a radio button list
            $animals = Quiz::all()->pluck('animal')->unique();

            return view('admin_create_quiz')->with('animals', $animals);
        }
        return redirect()->back();
    }

    // Edits a single quiz
    public function getEditQuizByID($id)
    {
        if (request()->user()->can('update-quizzes')) {
            $animals = Quiz::all()->pluck('animal')->unique();
            $quiz = Quiz::find($id);
            $b_options = QuizOption::where('quiz_id', '=', $quiz->id)->where('type', '=', 'behaviour')->get();
            $i_options = QuizOption::where('quiz_id', '=', $quiz->id)->where('type', '=', 'interpretation')->get();


            return view('admin_edit_quiz')->with(['animals' => $animals,
                'quiz' => $quiz, 'b_options' => $b_options, 'i_options' => $i_options]);
        } else {
            abort(403);
        }
    }

    public function getAddUser()
    {
        if (request()->user()->can('create-users')) {
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
