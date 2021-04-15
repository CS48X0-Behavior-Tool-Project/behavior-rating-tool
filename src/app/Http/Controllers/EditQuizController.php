<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Api\QuizController;
use App\Models\QuizOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditQuizController extends Controller
{
  private $qc;

  public function __construct()
  {
    $this->qc = new QuizController();
  }

  /**
   * @param mixed $id
   * @return RedirectResponse|void
   * @throws BindingResolutionException
   * @throws HttpException
   * @throws NotFoundHttpException
   */
  public function updateQuiz($id)
  {
    if (request()->user()->can('update-quizzes')) {
      try {
        $videoID = request()->input('video-id');
        $quizCode = request()->input('video-name');

        if ($quizCode == NULL) {
					return redirect()->route('edit_quiz_id_route', ['id' => $id])->with('name-status', 'Name field cannot be empty');
				}

        $animal = $this->readAnimal($id);

        $behaviours = $this->readBehaviours($id);

        $interpretations = $this->readInterpretations($id);

        $this->editQuiz($id, $videoID, $quizCode, $animal, $behaviours, $interpretations);

        return redirect()->route('quizzes_route')->with('edit-status', 'Successfully Edited Quiz ' . $quizCode);
      } catch (Exception $e) {
        list($status, $message) = explode(':', $e->getMessage());
        Log::info('>>> error status: ' . $status);
        Log::info('>>> error message: ' . $message);
        return redirect()->route('edit_quiz_id_route', ['id' => $id])->with($status, $message);
      }
    }
    return abort(403);
  }

  /**
   * Read the animal species from the list.
   */
  private function readAnimal($id)
  {

    if (request()->input('animal-radio') != null) {

      $animal = request()->input('animal-radio')[0];

      if ($animal === "New") {
        $newanimal = request()->input('a-new');

        if ($newanimal !== NULL) {
          $species = $newanimal;
        } else {
          return redirect()->route('edit_quiz_id_route', ['id' => $id])->with('animal-status', 'Animal Field Empty');
        }
      } else {
        $species = $animal;
      }
    } else {
      return redirect()->route('edit_quiz_id_route', ['id' => $id])->with('animal-status', 'No Animal Selected');
    }

    return $species;
  }

  /**
   * Read behaviours information from form.  Slightly modified from the function of the same name
   * in CreateQuizController.
   */
  private function readBehaviours($id)
  {
    $max_behaviours = 30;       //maximum number of behaviours you wish to have in your quizzes

    //form responses and checkbox status stored in arrays, null otherwise
    $behaviours = array();
		for ($i = 0; $i < $max_behaviours; $i++) {
			array_push($behaviours, request()->input('box-' . $i));
		}

    $checkboxes = array();
    for ($i = 0; $i < $max_behaviours; $i++) {
      $checkboxes[$i] = NULL;
    }

    $behaviourCheck = request()->input('behaviour-check');
    if ($behaviourCheck != null) {
      if (is_array($behaviourCheck)) {
        foreach ($behaviourCheck as $value) {
          $checkboxes[$value] = 'on';
        }
      }
    }

    //make sure at least one checkbox and field are filled in
    if ($this->containsOnlyNull($behaviours) || $this->containsOnlyNull($checkboxes)) {
      return redirect()->route('edit_quiz_id_route', ['id' => $id])->with('behaviour-status', 'Behaviours Incomplete');
    }


    //make sure all the checkboxes are associated with a non null input field
    foreach ($checkboxes as $key => $value) {
      if ($value === 'on' && $behaviours[$key] === NULL) {
        return redirect()->route('edit_quiz_id_route', ['id' => $id])->with('behaviour-status', 'Checked Fields Must Be Filled In');
      }
    }

    // build array having options as the keys, and the checkbutton status as value (on or NULL)
    $behavioursArray = array();

    foreach ($behaviours as $key => $value) {
      if ($value !== NULL) {
        $behavioursArray[$value] = $checkboxes[$key];
      }
    }

    return $behavioursArray;
  }

  /**
   * Checks whether every element is null, ensuring the user filled out the form.
   */
  private function containsOnlyNull($input)
  {
    return empty(array_filter($input, function ($a) {
      return $a !== null;
    }));
  }

  /**
   * Read interpretations information from form. Slightly modified from the function of the same name
   * in CreateQuizController.
   */
  private function readInterpretations($id)
  {
    $max_interpretations = 30;       //maximum number of interpretations you wish to have in your quizzes

    //form responses stored in array, null otherwise
    $interpretations = array();
		for ($i = 0; $i < $max_interpretations; $i++) {
			array_push($interpretations, request()->input('inter-' . $i));
		}

    //whichever radio button (1-5) was selected
    $radioValue = request()->input('interpretation-radio');


    if ($this->containsOnlyNull($interpretations) || $radioValue === NULL) {
      return redirect()->route('edit_quiz_id_route', ['id' => $id])->with('int-status', 'Interpretations Incomplete');
    }

    //make sure the radio button corresponds to a non null input field
    if ($interpretations[$radioValue] === NULL) {
      return redirect()->route('edit_quiz_id_route', ['id' => $id])->with('int-status', 'Selected Field Must Be Filled In');
    }

    // build array having options as the keys, and the radio button status as value (on or NULL)

    $interpretationsArray = array();

    foreach ($interpretations as $key => $value) {
      if ($value !== NULL) {
        if ($key == $radioValue) {
          $interpretationsArray[$value] = 'on';
        } else {
          $interpretationsArray[$value] = NULL;
        }
      }
    }

    return $interpretationsArray;
  }

  private function editQuiz($id, $videoID, $quizCode, $animal, $behaviours, $interpretations)
  {

    /*$old_behaviours = QuizOption::where([['quiz_id','=',$id], ['type','=', 'behaviour']])
        ->pluck('title')->toArray();
      $old_interpretations = QuizOption::where([['quiz_id','=',$id], ['type','=', 'interpretation']])
        ->pluck('title')->toArray();

      print_r($old_behaviours);*/

    //delete all the old options associated with this quiz and just remake them
    QuizOption::where('quiz_id', '=', $id)->delete();

    $quizOptions = array();
    $optKey = 0;

    foreach ($behaviours as $key => $value) {
      //$opt_id = QuizOption::where([['quiz_id','=',$id], ['title','=',$key]])->pluck('id')->toArray()[0];

      $isSolution = false;

      if ($value === 'on') {
        $isSolution = true;
      }

      $quizOptions[$optKey] = array(
        //'id' => $opt_id,
        'type' => 'behaviour',
        'title' => $key,
        'marking_scheme' => 1,
        'is_solution' => $isSolution,
      );

      $optKey++;
    }

    foreach ($interpretations as $key => $value) {
      //$opt_id = QuizOption::where([['quiz_id','=',$id], ['title','=',$key]])->pluck('id')->toArray()[0];

      $isSolution = false;

      if ($value === 'on') {
        $isSolution = true;
      }

      $quizOptions[$optKey] = array(
        //'id' => $opt_id,
        'type' => 'interpretation',
        'title' => $key,
        'marking_scheme' => 1,
        'is_solution' => $isSolution,
      );

      $optKey++;
    }

    $request = new Request([
      'code'   => $quizCode,
      'animal' => $animal,
      'video' => $videoID,
      'quiz_question_options' => $quizOptions,
    ]);

    $this->qc->updateQuiz($request, $id);
  }

  /**
  * Delete the selected quiz.
  */
  public function deleteQuiz($id) {
    //delete all the options associated with this quiz
    QuizOption::where('quiz_id', '=', $id)->delete();
    //then delete the quiz
    $quizName = Quiz::where('id', '=', $id)->pluck('code')[0];
    Quiz::where('id', '=', $id)->delete();

    return redirect()->route('quizzes_route')->with('delete-message', 'Quiz ' . $quizName . ' has been deleted.');
  }
}
