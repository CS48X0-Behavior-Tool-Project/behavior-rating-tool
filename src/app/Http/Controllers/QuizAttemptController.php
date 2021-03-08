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
      $scores = $this->calcScore($id, $behaviourSelections);
      $userAttempt->score = $scores[0];
      $userAttempt->interpretation_guess = $this->isCorrectInterpretation($id, $interpretationSelection);
      $userAttempt->save();

      // TODO: store time taken to submit answers
      // TODO: make sure the user fills in the quiz correctly (no empty answers)

      $scoreMessage = $scores[0] . '/' . $scores[1];

      // TODO: display message showing the user's score after submitting quiz

      //echo $scoreMessage;

      return redirect()->back()->with('score_message', $scoreMessage);
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
