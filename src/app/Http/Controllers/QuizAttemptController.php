<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QuizOption;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\QuizController;

class QuizAttemptController extends Controller
{
    private $uc;
    private $qc;

    public function __construct() {
        $this->uc = new UserController();
        $this->qc = new QuizController();
    }

    public function submitQuizAttempt($id) {

      $behaviourSelections = request()->input('behaviour-check');
      $interpretationSelection = request()->input('interpretation-check');

      $scores = $this->calcScore($id, $behaviourSelections);
      $interpretationGuess = $this->isCorrectInterpretation($id, $interpretationSelection);

      $request = new Request([
        'user_id'   => auth()->user()->id,
        'quiz_id' => $id,
        'behavior_answers' => $behaviourSelections,
        'interpretation_answers' => $interpretationSelection,
        'score' => $scores[0],
        'interpretation_guess' => $interpretationGuess,
      ]);

      $this->uc->upsertUserAttempts($request);

      // TODO: store time taken to submit answers
      // TODO: make sure the user fills in the quiz correctly (no empty answers)

      $quiz = $this->qc->getQuiz($id);

      $scoreMessage = 'You got ' . $scores[0] . '/' . $scores[1] . ' on ' . $quiz->code . '.';

      $interpretationMessage;

      if ($interpretationGuess) {
        $interpretationMessage = '  You have chosen the correct interpretation.';
      }
      else {
        $interpretationMessage = '  You have chosen the incorrect interpretation.';
      }

      $scoreMessage .= $interpretationMessage;

      return redirect()->route('quizzes_route')->with('score-message', $scoreMessage);
    }

    /**
    *  Calculate the score the user gets based on the marking scheme of the behaviour options.
    */
    private function calcScore($quizId, $behaviourSelection) {
      //array of all possible behaviours
      $behaviours = QuizOption::where([['quiz_id','=',$quizId],['type','=','behaviour']])
        ->pluck('title')->toArray();

      //array showing whether each behaviour is true or false (1 or 0)
      $behaviourSolutions = QuizOption::where([['quiz_id','=',$quizId],['type','=','behaviour']])
        ->pluck('is_solution')->toArray();

      //array holding marking scheme of each potential answer
      $behaviourValues = QuizOption::where([['quiz_id','=',$quizId],['type','=','behaviour']])
        ->pluck('marking_scheme')->toArray();

      $score = 0;
      $maxScore = 0;

      $answerKey = array();

      //create the answer key, with behaviours as keys and truth/false as the values
      foreach ($behaviours as $key => $value) {
        $answerKey[$value] = $behaviourSolutions[$key];

        if ($answerKey[$value] === 1) {
          $maxScore += $behaviourValues[$key];
        }
      }

      foreach ($behaviourSelection as $key => $value) {

        if($answerKey[$value] === 1) {
          $score += $behaviourValues[$key];
        }
        else {
          $score -= $behaviourValues[$key];
        }

      }

      if ($score < 0) {
        $score = 0;
      }

      return [$score,$maxScore];

    }

    /**
    *  Determine whether the user chose the correct interpretation.
    */
    private function isCorrectInterpretation($quizId, $interpretationSelection) {
      $correctInterpretation = QuizOption::where([['quiz_id','=',$quizId],['type','=','interpretation'],
        ['is_solution', '=', true]])->pluck('title')->toArray();

      $interpretationGuess = false;

      if (in_array($interpretationSelection, $correctInterpretation)) {
        $interpretationGuess = true;
      }

      return $interpretationGuess;
    }
}
