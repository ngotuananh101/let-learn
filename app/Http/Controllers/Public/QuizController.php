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
                        'questions.*.points' => 'nullable|integer|',
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
                            'is_multiple_choice' => $question['is_multiple_choice'],
                            'answer_option' => $answer_option,
                            'correct_answer' => $question['correct_answer'],
                            //set points to 1 if points is null
                            'points' => $question['points'] ?? 1,
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
                        'answers.*.points' => 'nullable|integer|',
                    ]);

                    $answerData = collect($request->input('answers'))->map(function ($answer) {
                        //if question is multiple choice, is_correct is true set points to points from question table
                        //if question is multiple choice, is_correct is false set points to 0
                        //if answer is not multiple choice, is_correct will be false set points to 0
                        // if($answer['is_correct'] == true && Question::where('id', $answer['question_id'])->first()->is_multiple_choice == 1){
                        //     $points = Question::where('id', $answer['question_id'])->first()->points;
                        //     dd($points);
                        // }else{
                        //     $points = 0;
                        // }
                        return [
                            'question_id' => $answer['question_id'],
                            'answer' => $answer['answer'],
                            'is_correct' => ($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer'])),
                            //get points from question table
                            //if is_correct is true and isMultipleChoice is true, set points to points from question table else set points to 0
                            'points' => (($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer']) && Question::where('id', $answer['question_id'])->first()->is_multiple_choice == 1)
                                ? Question::where('id', $answer['question_id'])->first()->points : 0),
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

    //method check if question is multiple choice
    public function isMultipleChoice($questionId)
    {
        $questionType = Question::where('id', $questionId)->first()->is_multiple_choice;
        if ($questionType) {
            return true;
        }
        return false;
    }
    public function update(Request $request, $id)
    {
        try {

            $request->validate([
                'type' => 'required|string|in:quiz,updateQuestions,updateAnswers,grade,addQuestion,removeQuestion,addAnswer,removeAnswer',
            ]);
            switch ($request->type) {
                case 'quiz':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'description' => 'required|string',
                        'is_active' => 'nullable|boolean',
                        'score_reporting' => 'nullable|boolean',
                    ]);

                    $quiz = Quiz::with('questions')->findOrFail($id);

                    $quiz->update([
                        'name' => $request->input('name', $quiz->name),
                        'description' => $request->input('description', $quiz->description),
                        'is_active' => $request->input('is_active', $quiz->is_active),
                        'score_reporting' => $request->input('score_reporting', $quiz->score_reporting),
                    ]);

                    return response()->json([
                        'data' => $quiz,
                        'message' => 'Update quiz successfully',
                        'status' => 200
                    ], 200);
                    break;
                case 'updateQuestions':
                    $request->validate([
                        'questions' => 'required|array',
                        'questions.*.question_id' => 'required|integer',
                        'questions.*.question' => 'nullable|string',
                        'questions.*.is_multiple_choice' => 'nullable|boolean',
                        'questions.*.answer_option' => 'nullable|array',
                        'questions.*.correct_answer' => 'nullable|string',
                        'questions.*.points' => 'nullable|integer|',
                    ]);
                    $questions = Question::where('quiz_id', $id)->get();
                    //loop through questions and update the questions by data from request following the question_id
                    foreach ($questions as $question) {
                        foreach ($request->input('questions') as $questionData) {
                            if ($question->id == $questionData['question_id']) {
                                $answer_option = json_encode($questionData['answer_option']);
                                $question->update([
                                    'question' => $questionData['question'] ?? $question->question,
                                    'is_multiple_choice' => $questionData['is_multiple_choice'] ?? $question->is_multiple_choice,
                                    'answer_option' => $answer_option ?? $question->answer_option,
                                    'correct_answer' => $questionData['correct_answer'] ?? $question->correct_answer,
                                    'points' => $questionData['points'] ?? $question->points,
                                ]);
                            }
                        }
                    }
                    return response()->json([
                        'data' => $questions,
                        'message' => 'Update questions successfully',
                        'status' => 200
                    ], 200);
                    break;
                case 'addQuestion':
                    $request->validate([
                        'questions' => 'required|array',
                        'questions.*.question' => 'required|string',
                        'questions.*.is_multiple_choice' => 'required|boolean',
                        'questions.*.answer_option' => 'nullable|array',
                        'questions.*.correct_answer' => 'nullable|string',
                        'questions.*.points' => 'nullable|integer|',
                    ]);
                    $quiz_id = Quiz::where('id', $id)->first();
                    //add new question to question table by quiz_id using map function
                    $questions = collect($request->input('questions'))->map(function ($question) use ($quiz_id) {
                        $answer_option = json_encode($question['answer_option']);
                        return new Question([
                            'question' => $question['question'],
                            'is_multiple_choice' => $question['is_multiple_choice'],
                            'answer_option' => $answer_option,
                            'correct_answer' => $question['correct_answer'],
                            'points' => $question['points'] ?? 1,
                        ]);
                    });
                    //save questions to question table
                    $quiz_id->questions()->saveMany($questions);
                    return response()->json([
                        'data' => $questions,
                        'message' => 'Add new questions successfully',
                        'status' => 200
                    ], 200);
                    break;
                case 'addAnswer':
                    $request->validate([
                        'answers' => 'required|array',
                        'answers.*.question_id' => 'required|integer',
                        'answers.*.answer' => 'required|string',
                        'answers.*.is_correct' => 'nullable|boolean',
                        'answers.*.points' => 'nullable|integer|',
                    ]);
                    $quiz_id = Quiz::where('id', $id)->first();
                    $answers = collect($request->input('answers'))->map(function ($answer) use ($quiz_id) {
                        return new Answer([
                            'quiz_id' => $quiz_id,
                            'question_id' => $answer['question_id'],
                            'answer' => $answer['answer'],
                            //check if answer is correct or not
                            'is_correct' => ($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer'])),
                            'points' => ($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer'])) ? Question::where('id', $answer['question_id'])->first()->points : 0,
                        ]);
                    });
                    $quiz_id->answers()->saveMany($answers);
                    return response()->json([
                        'data' => $answers,
                        'message' => 'Add new answers successfully',
                        'status' => 200
                    ], 200);
                    break;
                case 'removeAnswer':
                    $request->validate([
                        'answers' => 'required|array',
                        'answers.*.answer_id' => 'required|integer',
                    ]);
                    $answers = $request->input('answers');
                    foreach ($answers as $answer) {
                        $answer = Answer::findOrFail($answer['answer_id']);
                        $answer->delete();
                    }
                    break;
                case 'removeQuestion':
                    $request->validate([
                        'questions' => 'required|array',
                        'questions.*.question_id' => 'required|integer',
                    ]);
                    $questions = $request->input('questions');
                    foreach ($questions as $question) {
                        $question = Question::findOrFail($question['question_id']);
                        $question->delete();
                    }
                    break;

                case 'grade': //grade only not multiple choice question 
                    $request->validate([
                        'user_id' => 'required|integer',
                        'answers' => 'required|array',
                        'answers.*.question_id' => 'required|integer',
                        'answers.*.answer' => 'nullable|string',
                        'answers.*.is_correct' => 'nullable|boolean',
                        'answers.*.points' => 'required|integer|',
                    ]);
                    $quiz_id = Quiz::where('id', $id)->first();
                    // Get the existing answer text for the given quiz and user
                    $answer = Answer::where('quiz_id', $quiz_id->id)
                        ->where('user_id', $request->user_id)
                        ->firstOrFail();

                    // Decode the answer text from JSON to a PHP array
                    $answers = json_decode($answer->answer_text, true);

                    // Loop through the answers array and update the points value for each matching question ID
                    foreach ($request->answers as $a) {
                        foreach ($answers as &$ans) {
                            if ($ans['question_id'] == $a['question_id']) {
                                $ans['points'] = $a['points'] ?? 0;
                                break;
                            }
                        }
                    }
                    // Encode the updated array back to JSON format and save it as the new answer text
                    $answer->answer_text = json_encode($answers);
                    dd($answer);
                    $answer->save();
                    return response()->json([
                        'data' => $answer,
                        'message' => 'Update answers successfully',
                        'status' => 200
                    ], 200);

                    // case 'updateAnswers':
                    //     $request->validate([                                              
                    //         'answers' => 'required|array',
                    //         'answers.*.question_id' => 'required|integer',
                    //         'answers.*.answer' => 'required|string',
                    //         'answers.*.is_correct' => 'required|boolean',
                    //         'answers.*.points' => 'nullable|integer|',
                    //     ]);
                    //     $quiz_id = Quiz::where('id', $id)->first();
                    //     $answerData = collect($request->input('answers'))->map(function ($answer) {
                    //         return [
                    //             'question_id' => $answer['question_id'],
                    //             'answer' => $answer['answer'], // or any other answer data you want to store
                    //             //get type of question, if question is not multiple choice then is_correct is input from user, else is_correct is same with case store answer
                    //             'is_correct' => ((Question::where('id', $answer['question_id'])->first()->is_multiple_choice) ? ($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer'])) : $answer['is_correct']),
                    //             'points' => ((Question::where('id', $answer['question_id'])->first()->is_multiple_choice) ? ($this->compareAnswer($answer['question_id'], Question::where('id', $answer['question_id'])->first()->correct_answer, $answer['answer'])) ? Question::where('id', $answer['question_id'])->first()->points : 0 : $answer['points']),
                    //         ];
                    //     });
                    //     // //store answerData to answer_text column as json text
                    //     $answerText = json_encode($answerData);
                    //     $answer = Answer::findOrFail($id);
                    //     $answer->quiz_id = $request->input('quiz_id', $answer->quiz_id);
                    //     //get user id from auth
                    //     $answer->user_id = $request->user()->id;
                    //     $answer->answer_text = $answerText;
                    //     $answer->save();
                    //     return response()->json([
                    //         'data' => $answer,
                    //         'message' => 'Update answer successfully',
                    //         'status' => 200
                    //     ], 200);
                    //     break;

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
