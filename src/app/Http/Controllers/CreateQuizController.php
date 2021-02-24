<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateQuizController extends Controller
{
    public function createQuiz() {
      $this->readVideo();
      $this->readBehaviours();
      $this->readInterpretations();

      return redirect()->route('create_quiz_route');
    }

    /**
    * Read video information from form.
    */
    public function readVideo() {
      $videoID = request()->input('video-id');
      $videoName = request()->input('video-name');

      //print("Video id: " . $videoID);
      //print("Video name: " . $videoName);

      // NOTE: videos are stored in storage\app\videos and automatically stored in the database
      //should be able to store just the video id with the quiz information and use that to find the video later

      //reject form upload if there is no video uploaded
      //make sure the ID field is not empty, and that the ID corresponds to a video that has been uploaded (record stored in db)
      if ($videoID === NULL || !DB::table('videos')->where('id',$videoID)->first()) {
        return redirect()->route('create_quiz_route')->with('video-status', 'Video With That ID Does Not Exist');
      }
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

      $checkboxes = array(
        request()->input('b-one'),
        request()->input('b-two'),
        request()->input('b-three'),
        request()->input('b-four'),
        request()->input('b-five'),
        request()->input('b-six'),
        request()->input('b-seven'),
        request()->input('b-eight'),
        request()->input('b-nine'),
        request()->input('b-ten'),
      );

      //print_r($behaviours);
      //print_r($checkboxes);

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

      // TODO: upload quiz information to database
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

      //print_r($interpretations);
      //print_r($radioValue);

      //make sure the radio button corresponds to a non null input field
      if ($interpretations[$radioValue-1] === NULL) {
        return redirect()->route('create_quiz_route')->with('int-status', 'Selected Field Must Be Filled In');
      }

      // TODO: upload quiz information to database
    }
}
