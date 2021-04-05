<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Quiz;
use App\Models\QuizOption;
use App\Models\AttemptQuiz;
use App\Models\AttemptAnswerItem;

class QuizController extends Controller
{
    public function getAllQuizzes()
    {
        // Implement logic to fetch all quizzes
        $quizzes = Quiz::all();
        return $quizzes;
    }

    public function getQuiz($id)
    {
        // Implement logic to fetch quiz

        $quizOps = DB::table('quiz_options')
            ->where('quiz_id', $id)
            ->get();

        $quizInfor = Quiz::find($id);
        $quizInfor->quiz_question_options = $quizOps;
        return $quizInfor;
    }

    public function getQuizAttempts($id)
    {
        // Implement logic to fetch quiz attempts

        $attempts = DB::table('attempt_quizzes')
            ->where('quiz_id', $id)
            ->get();
        return $attempts;
    }

    public function createQuiz(Request $request)
    {
        $quiz = new Quiz;
        $quiz->code = $request->code;
        $quiz->animal = $request->animal;
        $quiz->video = $request->video;
        $quiz->question = $request->question;
        $quiz->save();

        // iterate each question_options, create quiz_question_option
        $options = $request->quiz_question_options;
        // \Log::info($options);

        foreach ($options as $option) {
            $opt = new QuizOption;
            $opt->quiz_id = $quiz->id;
            $opt->type = $option['type'];
            $opt->title = $option['title'];
            $opt->marking_scheme = $option['marking_scheme'];
            $opt->is_solution = $option['is_solution'];
            $opt->save();
        }

        return response()->json(['success' => true, 'last_insert_id' => $quiz->id], 200);
    }

    public function updateQuiz(Request $request, $id)
    {
        if (Quiz::where('id', $id)->exists()) {
            \Log::info($request);

            $quiz = Quiz::findOrFail($id);
            \Log::info($quiz);

            if ($request['quiz_question_options'] == null) {
                $quiz->update($request->all());
            }
            else {
                $code = is_null($request->code) ? $quiz->code : $request->code;
                $animal = is_null($request->animal) ? $quiz->animal : $request->animal;
                $video = is_null($request->video) ? $quiz->video : $request->video;
                $question = is_null($request->question) ? $quiz->question : $request->question;
                $options = is_null($request->options) ? $quiz->options : $request->options;
                $quiz->update(['code' => $code, 'animal' => $animal, 'video' => $video, 'question'=>  $question, 'options'=>$options]);

                $ops = $request['quiz_question_options'];
                foreach ($ops as $option) {
                    $opt = new QuizOption();//QuizOption::findOrFail($option['id']);

                    $opt->quiz_id = $id;

                    if(isset($option['type'])) {
                        $opt->type = $option['type'];
                    }

                    if(isset($option['title'])) {
                        $opt->title = $option['title'];
                    }

                    if(isset($option['marking_scheme'])){
                        $opt->marking_scheme = $option['marking_scheme'];
                    }

                    if(isset($option['is_solution'])){
                        $opt->is_solution = $option['is_solution'];
                    }

                    $opt->save();
                }

            }
            return response()->json(['success' => true, 'message' => 'updated successfully'], 200);
        }
        else {
            return response()->json(["message" => "data not found"], 404);
        }
    }

    public function deleteQuiz($id)
    {
        Quiz::find($id)->delete();
    }
}
