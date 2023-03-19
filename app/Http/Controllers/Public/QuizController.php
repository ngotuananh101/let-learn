<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function index()
    {
        try {
            $quizzes = Quiz::with('questions')->get();

            return response()->json(['data' => $quizzes]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $quiz = Quiz::with('questions')->findOrFail($id);

            return response()->json(['data' => $quiz]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:quiz,answer',
            ]);
            switch ($request->type) {
                case 'quiz':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'description' => 'required|string',
                        'is_active' => 'nullable|boolean',
                        'score_reporting' => 'nullable|boolean',
                        'questions' => 'required|array',
                        'questions.*.question' => 'required|string',
                        'questions.*.is_multiple_choice' => 'nullable|boolean',
                        'questions.*.answer_option' => 'nullable|array',
                        'questions.*.correct_answer' => 'nullable|string',
                    ]);

                    $quiz = Quiz::create([
                        'name' => $request->input('name'),
                        'description' => $request->input('description'),
                        'is_active' => $request->input('is_active', false),
                        'score_reporting' => $request->input('score_reporting', true),
                    ]);

                    //create questions collection and map the questions to the newly created quiz id
                    $questions = collect($request->input('questions'))->map(function ($question) {
                        $answer_option = json_encode($question['answer_option']);
                        return new Question([
                            'question' => $question['question'],
                            //convert value is_multiple_choice to boolean 0 or 1
                            'is_multiple_choice' => $question['is_multiple_choice'],
                            'answer_option' => $answer_option,
                            'correct_answer' => $question['correct_answer'],
                        ]);
                    });

                    $quiz->questions()->saveMany($questions);

                    return response()->json([
                        'data' => $quiz,
                        'message' => 'Create new quiz with question successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'answer':
                    $request->validate([
                        'quiz_id' => 'required|integer',
                        'answers' => 'required|array',
                        'answers.*.question_id' => 'required|integer',
                        'answers.*.answer' => 'required|string',
                        'answers.*.is_correct' => 'nullable|boolean',
                    ]);

                    $answerData = collect($request->input('answers'))->map(function ($answer) {
                        return [
                            'question_id' => $answer['question_id'],
                            'answer' => $answer['answer'], // or any other answer data you want to store
                            //compare answer with correct answer and return true or false
                            'is_correct' => $this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer']),
                    
                        ];
                    });
                    //store answerData to answer_text column as json text
                    $answerText = json_encode($answerData);

                    $answer = new Answer();
                    $answer->quiz_id = $request->input('quiz_id');
                    //get user id from auth
                    $answer->user_id = $request->user()->id;
                    $answer->answer_text = $answerText;
                    $answer->save();
                    return response()->json([
                        'data' => $answer,
                        'message' => 'Create new answer successfully',
                        'status' => 200
                    ], 200);
                    break;
                default:
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    //method to compare answer with correct answer
    public function compareAnswer($questionId, $correctAnswer, $answer)
    {

        //get question type from question table
        $questionType = Question::where('id', $questionId)->first()->is_multiple_choice;
        //if question type is multiple choice, get correct_answer from question table and compare with answer
        if ($questionType) {
            if ($correctAnswer == $answer) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'questions' => 'required|array',
                'questions.*.question' => 'required|string',
                'questions.*.is_multiple_choice' => 'nullable|boolean',
                'questions.*.answer_option' => 'nullable|array',
                'questions.*.correct_answer' => 'nullable|string',
            ]);

            //check if request is error
            if ($request->fails()) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 400,
                    'message' => $request->errors()
                ], 400);
            }

            $quiz = Quiz::with('questions')->findOrFail($id);

            $quiz->update([
                'name' => $request->input('name', $quiz->name),
                'description' => $request->input('description', $quiz->description),
            ]);

            if ($request->has('questions')) {
                $questions = collect($request->input('questions'))->map(function ($question) {
                    return new Question([
                        'question' => $question['question'],
                        'is_multiple_choice' => $question['is_multiple_choice'],
                        'answer_option' => $question['answer_option'],
                        'correct_answer' => $question['correct_answer'],
                    ]);
                });

                $quiz->questions()->delete();
                $quiz->questions()->saveMany($questions);
            }

            return response()->json([
                'data' => $quiz,
                'message' => 'Update quiz successfully',
                'status' => 200
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $quiz = Quiz::findOrFail($id);
            $quiz->delete();

            return response()->json([
                'message' => 'Delete quiz successfully',
                'status' => 200
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
