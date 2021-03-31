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
        $this->qc = new QuizController();
        $this->middleware('auth')->except('getLoginPage');
    }

    /**
     * These controller methods simply load up the appropriate views from the pages folder.
     */

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

        //key will be the quiz ID, values will be array of behaviour scores from each attempt
        $behaviourScoresPerQuiz = array();

        //key is the quiz ID, values will be array of interpretation scores from each attempt
        $interpretationScoresPerQuiz = array();

        foreach ($attemptIdPerQuiz as $key => $value) {
          $behaviourScoreList = array();
          $interpretationScoreList = array();
          if (!empty($value)) {
            foreach ($value as $key2 => $value2) {
              $behaviourScoreList[$value2] = UserAttempt::where('attempt_id','=',$value2)->pluck('score')->toArray()[0];
              $interpretationScoreList[$value2] = UserAttempt::where('attempt_id','=',$value2)
                ->pluck('interpretation_guess')->toArray()[0];
            }
            $behaviourScoresPerQuiz[$key] = $behaviourScoreList;
            $interpretationScoresPerQuiz[$key] = $interpretationScoreList;
          }
        }

        // TODO: currently just taking the best score from any attempt in both behaviours and interpretations...
        //       will need to determine a good way to calculate the best attempt
        //determine the attempt that resulted in the maximum score for each quiz
        //keys are quiz IDs, values are arrays having attempt ID as the key, and score as the value
        $bestBehaviourAttempts = array();     //store the best attempt # for each quiz
        $bestBehaviourScorePerQuiz = array();
        foreach ($behaviourScoresPerQuiz as $key => $value) {
          $bestBehaviourScorePerQuiz[$key] = max($value);
          $bestBehaviourAttempts[$key] = array_search($bestBehaviourScorePerQuiz[$key], $value);
        }

        $bestInterpretationAttempts = array();
        $bestInterpretationScorePerQuiz = array();
        foreach ($interpretationScoresPerQuiz as $key => $value) {
          $bestInterpretationScorePerQuiz[$key] = max($value);
          $bestInterpretationAttempts[$key] = array_search($bestInterpretationScorePerQuiz[$key], $value);
        }


        //find the max score per quiz
        $maxBehaviourScoresList = array();
        foreach ($bestBehaviourAttempts as $key => $value) {
          $maxBehaviourScore = UserAttempt::where('attempt_id','=',$value)->pluck('max_score')->toArray()[0];
          $maxBehaviourScoresList[$key] = $maxBehaviourScore;
        }

        $maxInterpretationScoresList = array();
        foreach ($bestInterpretationAttempts as $key => $value) {
          $maxInterpretationScore = UserAttempt::where('attempt_id','=',$value)->pluck('interpretation_guess')->toArray()[0];
          if ($maxInterpretationScore != 0) {
            $maxInterpretationScoresList[$key] = $maxInterpretationScore;
          }
        }

        // NOTE: printing stuff for testing, keep until we decide on a scoring method
        /*print("Behaviour scores: \n");
        print_r($behaviourScoresPerQuiz);
        print("\nInterpretation scores: \n");
        print_r($interpretationScoresPerQuiz);
        print("\nBest Behaviour scores: \n");
        print_r($bestBehaviourScorePerQuiz);
        print("\nBest Interpretation scores: \n");
        print_r($bestInterpretationScorePerQuiz);
        print("\nBest Behaviour Attempts: \n");
        print_r($bestBehaviourAttempts);
        print("\nBest Interpretation Attempts: \n");
        print_r($bestInterpretationAttempts);
        print("\nMax Behaviour Scores: \n");
        print_r($maxBehaviourScoresList);
        print("\nMax Interpretation Scores: \n");
        print_r($maxInterpretationScoresList);*/

        return view('quizzes')->with(['animals' => $animals, 'quizzes' => $quizzes,
          'attempts' => $attemptsPerQuiz, 'uniqueAttempts' => $uniqueAttempts, 'bestBehaviourScores' => $bestBehaviourScorePerQuiz,
          'bestInterpretationScores' => $bestInterpretationScorePerQuiz, 'maxBehaviourScores' => $maxBehaviourScoresList,
          'maxInterpretationScores' => $maxInterpretationScoresList]);
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
        if (request()->user()->can('view', User::class)){
            $users = $this->uc->getAllUsers();

            return view('admin_view_all_users')->with('users', $users);
        } else {
            abort(403);
        }

    }

    public function getUserById($id)
    {
        if (request()->user()->can('view', User::class)){
            $user = $this->uc->getUser($id);
            return view('single_user')->with('user', $user);
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
        if (request()->user()->can('create', Quiz::class)) {
            // search the database for different animal species to populate a radio button list
            $animals = Quiz::all()->pluck('animal')->unique();

            return view('admin_create_quiz')->with('animals', $animals);
        }
        return redirect()->back();
    }

    // Selector page for editing quizzes
    public function getEditQuiz()
    {
        $quizzes = Quiz::all();

        return view('edit_quiz_selector')->with('quizzes',$quizzes);
    }

    // Edits a single quiz
    public function getEditQuizByID($id)
    {
        if (request()->user()->can('edit', Quiz::class)) {
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
