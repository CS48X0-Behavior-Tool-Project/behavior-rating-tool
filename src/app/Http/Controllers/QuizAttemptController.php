<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attempt;
use App\Models\AttemptQuiz;
use App\Models\AttemptAnswerItem;
use App\Models\UserAttempt;

use App\Models\QuizOption;

class QuizAttemptController extends Controller
{
    public function submitQuizAttempt($id) {

      $attempt = new Attempt();
      $attempt->user_id = auth()->user()->id;
      $attempt->save();

      $quiz = new AttemptQuiz();
      $quiz->attempt_id = $attempt->id;
      $quiz->quiz_id = $id;
      $quiz->save();

      $answer = new AttemptAnswerItem();
      $answer->attempt_quiz_id = $id;
      $behaviourSelections = request()->input('behaviour-check');
      $answer->behavior_answers = json_encode($behaviourSelections);
      $interpretationSelection = request()->input('interpretation-check');
      $answer->interpretation_answers = json_encode($interpretationSelection);
      $answer->save();

      $userAttempt = new UserAttempt();
      $userAttempt->user_id = auth()->user()->id;
      $userAttempt->attempt_id = $attempt->id;
      $userAttempt->score = $this->calcScore($id, $behaviourSelections);
      $userAttempt->interpretation_guess = $this->isCorrectInterpretation($id, $interpretationSelection);
      $userAttempt->save();

      return redirect()->back();
    }

    /**
    *  Calculate the score the user gets based on the marking scheme of the behaviour options.
    */
    private function calcScore($quizId, $behaviourSelection) {
      $correctBehaviours = QuizOption::where([['quiz_id','=',$quizId],['type','=','behaviour'],
        ['is_solution', '=', true]])->pluck('title')->toArray();

      $behaviourValues = QuizOption::where([['quiz_id','=',$quizId],['type','=','behaviour'],
        ['is_solution', '=', true]])->pluck('marking_scheme')->toArray();

      $score = 0;

      foreach ($behaviourSelection as $key => $value) {

        if (in_array($value, $correctBehaviours)) {
          $score += $behaviourValues[$key];
        }
        else {
          $score -= $behaviourValues[$key];
        }

      }

      if ($score < 0) {
        $score = 0;
      }

      return $score;

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
