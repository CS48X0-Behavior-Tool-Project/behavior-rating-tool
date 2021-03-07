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

        // $quizOps = DB::select('select * from quiz_options where quiz_id = ?', [$id]);
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
        // $attempts = DB::select('select * from attempt_quizzes where quiz_id = ?', [$id]);
        $attempts = DB::table('attempt_quizzes')
            ->where('quiz_id', $id)
            ->get();
        return $attempts;
    }

    public function updateQuizAttempts(Request $request, $id) {
        // will perfrom insert if not exist, update if exist

        \Log::info($request);
        $attempt_id = $request->attempt_id;
        $quiz_id = $id;

        $attempt_quiz_id = -1;

        // using 'attempt_id' and 'quiz_id' check if existing in attempt_quizzes
        $attempt_quiz = DB::table('attempt_quizzes')
        ->where('attempt_id', $attempt_id)
        ->Where('quiz_id', $quiz_id)
        ->get();
        
        \Log::info($attempt_quiz);

        if(count($attempt_quiz) > 0) {
            $attempt_quiz_id = $attempt_quiz[0]->id;
        }
        else { // insert into attempt_quizzes
            $attempt_quiz = new AttemptQuiz;
            $attempt_quiz->attempt_id = $attempt_id;
            $attempt_quiz->quiz_id = $quiz_id;
            $attempt_quiz->save();
            $attempt_quiz_id = $attempt_quiz->id;
        }


        // using 'attempt_id' and 'quiz_id' check if existing in attempt_quizzes
        $attempt_answer_item = DB::table('attempt_answer_items')
        ->where('attempt_quiz_id', $attempt_quiz_id)
        ->get();

        if(count($attempt_answer_item) > 0) {
            // TODO: if exist, find and update - Mar.4 Tami
            // $attempt_quiz_id = $attempt_quiz[0]->id;
            // $attempt_answer_item->behavior_answers = json_encode($request->behavior_answers);
            // $attempt_answer_item->interpretation_answers = json_encode($request->interpretation_answers);
            // $attempt_answer_item->save();
        }
        else{
            // insert into attemp_answer_items
            $attempt_answer_item = new AttemptAnswerItem;
            $attempt_answer_item->attempt_quiz_id = $attempt_quiz_id;
            $attempt_answer_item->behavior_answers = json_encode($request->behavior_answers);
            $attempt_answer_item->interpretation_answers = json_encode($request->interpretation_answers);
            $attempt_answer_item->save();
        }


        return response()->json(['success' => true], 200);
    }

    public function createQuiz(Request $request)
    {
        // \Log::info($request);
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
                    $opt = QuizOption::findOrFail($option['id']);
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