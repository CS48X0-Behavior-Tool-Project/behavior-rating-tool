<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\QuizOption;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $id = $this->createQuiz('Horse001', 'Horse', '1', 'Please indicate behaviors and interpretations');
      $this->createOption($id, 'behaviour', 'Kicking', 1, true);
      $this->createOption($id, 'behaviour', 'Dancing', 1, false);
      $this->createOption($id, 'interpretation', 'Angry', 1, true);
      $this->createOption($id, 'interpretation', 'Happy', 1, false);

      $id = $this->createQuiz('Horse002', 'Horse', '2', 'Please indicate behaviors and interpretations');
      $this->createOption($id, 'behaviour', 'Screaming', 1, true);
      $this->createOption($id, 'behaviour', 'Laughing', 1, false);
      $this->createOption($id, 'interpretation', 'Fearful', 1, true);
      $this->createOption($id, 'interpretation', 'Content', 1, false);

      $id = $this->createQuiz('Horse003', 'Horse', '3', 'Please indicate behaviors and interpretations');
      $this->createOption($id, 'behaviour', 'Going Nuts', 1, true);
      $this->createOption($id, 'behaviour', 'Singing', 1, false);
      $this->createOption($id, 'interpretation', 'Bonkers', 1, true);
      $this->createOption($id, 'interpretation', 'Joyful', 1, false);

      $id = $this->createQuiz('Cow004', 'Cow', '4', 'Please indicate behaviors and interpretations');
      $this->createOption($id, 'behaviour', 'Mooing Angrily', 1, true);
      $this->createOption($id, 'behaviour', 'Mooing Happily', 1, false);
      $this->createOption($id, 'interpretation', 'Angry', 1, true);
      $this->createOption($id, 'interpretation', 'Happy', 1, false);


    }

    private function createQuiz($code, $animal, $video, $question) {
      $quiz = new Quiz();
      $quiz->code = $code;
      $quiz->animal = $animal;
      $quiz->video = $video;
      $quiz->question = $question;
      $quiz->save();

      return $quiz->id;
    }

    private function createOption($id, $type, $title, $marking_scheme, $is_solution) {
      $opt = new QuizOption;
      $opt->quiz_id = $id;
      $opt->type = $type;
      $opt->title = $title;
      $opt->marking_scheme = $marking_scheme;
      $opt->is_solution = $is_solution;
      $opt->save();
    }
}
