<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreateQuizController extends Controller
{
    public function createQuiz() {
      $this->readVideo();
      $this->readBehaviours();
      $this->readInterpretations();
    }

    public function readVideo() {
      //echo 'reading video information <br>';
    }

    public function readBehaviours() {
      echo 'reading behaviour options <br>';

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
    }

    public function readInterpretations() {
      //echo 'reading possible interpretations <br>';
    }
}
