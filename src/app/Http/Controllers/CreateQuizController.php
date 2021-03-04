<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Quiz;
use App\Models\QuizOption;

class CreateQuizController extends Controller
{
    public function createQuiz() {
      try {
        $videoID = $this->readVideo();
        $animal = $this->readAnimal();
        $behaviours = $this->readBehaviours();
        $interpretations = $this->readInterpretations();

        $quizCode = ucfirst($animal) . $videoID;


        \Log::info('>>> videoID: '.$videoID);
        \Log::info('>>> animal: '.$animal);

        $this->uploadQuiz($videoID, $quizCode, $animal, $behaviours, $interpretations);

        return redirect()->route('create_quiz_route')->with('quiz-status', 'Quiz ' . $quizCode . ' Created Successfully');
      }
      catch (Exception $e) {

        list($status, $message) = explode(':', $e->getMessage());
        \Log::info('>>> error status: '.$status);
        \Log::info('>>> error message: '.$message);
        return redirect()->route('create_quiz_route')->with($status, $message);
      }
    }

    /**
    * Read video information from form.
    */
    public function readVideo() {
      $videoID = request()->input('video-id');
      $videoName = request()->input('video-name');

      // NOTE: videos are stored in storage\app\videos and automatically stored in the database
      //should be able to store just the video id with the quiz information and use that to find the video later

      //reject form upload if there is no video uploaded
      //make sure the ID field is not empty, and that the ID corresponds to a video that has been uploaded (record stored in db)
      if ($videoID === NULL || !DB::table('videos')->where('id', $videoID)->first()) {
        throw new Exception("video-status:"."Video ID Mismatch");
        return redirect()->route('create_quiz_route')->with('video-status', 'Video ID Mismatch');
      }

      return $videoID;
    }

    /**
    * Read the animal species from the list.
    */
    public function readAnimal() {

      $species;

      if (isset($_POST['animal-radio'])) {

        $animal = $_POST['animal-radio'][0];

        if ($animal === "New") {
          $newanimal = request()->input('a-new');

          if ($newanimal !== NULL) {
            $species = $newanimal;
          }
          else {
            throw new Exception("animal-status:"."Animal Field Empty");
            return redirect()->route('create_quiz_route')->with('animal-status','Animal Field Empty');
          }
        }
        else {
          $species = $animal;
        }
      }
      else {
        throw new Exception("animal-status:"."No Animal Selected");
        return redirect()->route('create_quiz_route')->with('animal-status','No Animal Selected');
      }

      return $species;
    }

    /**
    * Read behaviours information from form.
    */
    public function readBehaviours() {
      //form responses and checkbox status stored in arrays, null otherwise
      $behaviours = array(
        request()->input('box-one'),
        request()->input('box-two'),
        request()->input('box-three'),
        request()->input('box-four'),
        request()->input('box-five'),
        request()->input('box-six'),
        request()->input('box-seven'),
        request()->input('box-eight'),
        request()->input('box-nine'),
        request()->input('box-ten'),
      );

      $checkboxes = array();
      for ($i=0; $i < 10; $i++) {
        $checkboxes[$i] = NULL;
      }

      if(isset($_POST['behaviour-check'])) {
        if (is_array($_POST['behaviour-check'])) {
             foreach($_POST['behaviour-check'] as $value){
                $checkboxes[$value-1] = 'on';
             }
          }
        }

      //make sure at least one checkbox and field are filled in
      if ($this->containsOnlyNull($behaviours) || $this->containsOnlyNull($checkboxes)) {
        return redirect()->route('create_quiz_route')->with('behaviour-status', 'Behaviours Incomplete');
      }


      //make sure all the checkboxes are associated with a non null input field
      foreach ($checkboxes as $key => $value) {
        if ($value === 'on' && $behaviours[$key] === NULL) {
          return redirect()->route('create_quiz_route')->with('behaviour-status', 'Checked Fields Must Be Filled In');
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
    function containsOnlyNull($input)
    {
      return empty(array_filter($input, function ($a) { return $a !== null;}));
    }

    /**
    * Read interpretations information from form.
    */
    public function readInterpretations() {
      //form responses stored in array, null otherwise
      $interpretations = array(
        request()->input('inter-one'),
        request()->input('inter-two'),
        request()->input('inter-three'),
        request()->input('inter-four'),
        request()->input('inter-five'),
      );

      //whichever radio button (1-5) was selected
      $radioValue = request()->input('interpretation-radio');

      if ($this->containsOnlyNull($interpretations) || $radioValue === NULL) {
        return redirect()->route('create_quiz_route')->with('int-status', 'Interpretations Incomplete');
      }

      //make sure the radio button corresponds to a non null input field
      if ($interpretations[$radioValue-1] === NULL) {
        return redirect()->route('create_quiz_route')->with('int-status', 'Selected Field Must Be Filled In');
      }

      // build array having options as the keys, and the radio button status as value (on or NULL)

      $interpretationsArray = array();

      foreach ($interpretations as $key => $value) {
        if ($value !== NULL) {
          if ($key == $radioValue-1) {
            $interpretationsArray[$value] = 'on';
          }
          else {
            $interpretationsArray[$value] = NULL;
          }
        }
      }

      return $interpretationsArray;
    }

    /**
    * Upload the information for the new quiz into the database.
    */
    public function uploadQuiz($videoID, $quizCode, $animal, $behaviours, $interpretations) {

      \Log::info('>>> stop 1');
      \Log::info('>>> videoID: '.$videoID);
      \Log::info('>>> animal: '.$animal);
      \Log::info('>>> quizCode: '.$quizCode);

      $quiz = new Quiz();
      $quiz->code = $quizCode;
      $quiz->animal = $animal;
      $quiz->video = $videoID;
      $quiz->question = 'Please indicate behaviors and interpretations';
      $quiz->save();

      foreach ($behaviours as $key => $value) {

        $isSolution = false;

        if ($value === 'on') {
          $isSolution = true;
        }

        $opt = new QuizOption;
        $opt->quiz_question_id = $quiz->id;
        $opt->type = 'behaviour';
        $opt->title = $key;
        $opt->marking_scheme = 1;
        $opt->is_solution = $isSolution;
        \Log::info('>>> stop 2');
        $opt->save();
      }

      foreach ($interpretations as $key => $value) {

        $isSolution = false;

        if ($value === 'on') {
          $isSolution = true;
        }

        $opt = new QuizOption;
        $opt->quiz_question_id = $quiz->id;
        $opt->type = 'interpretation';
        $opt->title = $key;
        $opt->marking_scheme = 1;
        $opt->is_solution = $isSolution;
        \Log::info('>>> stop 3');
        $opt->save();
      }
    }
}
