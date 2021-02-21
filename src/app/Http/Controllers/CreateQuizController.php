<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
      echo 'reading video information <br>';

      $videoID = request()->input('video-id');
      $videoName = request()->input('video-name');

      print("Video id: " . $videoID);
      echo '<br>';
      print("Video name: " . $videoName);
      echo '<br><br>';

      // NOTE: videos are stored in storage\app\videos

      //reject form upload if there is no video uploaded
      if ($videoID === NULL) {
        return redirect()->route('create_quiz_route');
      }
    }

    /**
    * Read behaviours information from form.
    */
    public function readBehaviours() {
      echo 'reading behaviour options <br>';

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

      print_r($behaviours);
      print_r($checkboxes);

      // TODO: upload quiz information to database
    }

    /**
    * Read interpretations information from form.
    */
    public function readInterpretations() {
      echo '<br><br>';
      echo 'reading possible interpretations <br>';

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


      print_r($interpretations);
      print_r($radioValue);

      // TODO: upload quiz information to database
    }
}
