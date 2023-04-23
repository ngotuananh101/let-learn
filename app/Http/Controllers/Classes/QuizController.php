<?php

namespace App\Http\Controllers\Classes;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($classId)
    {
        try {
            $user = auth()->user();
            $class = $user->classes()->where('class_id', $classId)->firstOrFail();
            // if user is teacher then show all quiz of that class
            if ($user->role->name === 'teacher') {
                $quizzes = $class->quizzes;
            } else {
                // if user is student then show only active quiz
                $quizzes = $class->quizzes()->where('start_time', '<=', Carbon::now())->where('end_time', '>=', Carbon::now())->where('status', 'active')->get();
            }
            return response()->json([
                'quizzes' => $quizzes->map(function ($quiz) {
                    return [
                        'id' => $quiz->id,
                        'name' => $quiz->name,
                        'description' => $quiz->description,
                        'start_time' => $quiz->start_time,
                        'end_time' => $quiz->end_time,
                        'count_questions' => $quiz->questions->count(),
                        'submitted' => Answer::where('quiz_id', $quiz->id)->where('user_id', auth()->user()->id)->exists(),
                        'status' => $quiz->status,
                    ];
                })
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'class_id' => 'required|integer|exists:classes,id',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'questions' => 'required|array',
                'questions.*.question' => 'required|string',
                'questions.*.is_multiple_choice' => 'required|boolean',
                'questions.*.score' => 'required|integer',
                'questions.*.answers' => 'nullable|array',
                'questions.*.answers.*.answer' => 'nullable|string',
                'questions.*.answers.*.correct' => 'nullable|boolean',
            ]);

            $quiz = Quiz::create([
                'name' => $request->name,
                'description' => $request->description,
                'class_id' => $request->class_id,
                'start_time' => Carbon::parse($request->start_time),
                'end_time' => Carbon::parse($request->end_time),
            ]);

            foreach ($request->questions as $question) {
                $correctAnswer = array_filter($question['answers'], function ($answer) {
                    return $answer['correct'];
                });
                $correctAnswer = array_values($correctAnswer)[0];
                $answerOptions = array_map(function ($answer) {
                    return $answer['answer'];
                }, $question['answers']);
                $quiz->questions()->create([
                    'is_multiple_choice' => $question['is_multiple_choice'],
                    'question' => $question['question'],
                    'points' => $question['score'],
                    'correct_answer' => $question['is_multiple_choice'] ? $correctAnswer['answer'] : "",
                    'answer_option' => $question['is_multiple_choice'] ? json_encode($answerOptions, JSON_UNESCAPED_UNICODE) : "",
                ]);
            }

            return response()->json([
                'message' => 'Quiz created successfully',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $class_id, string $id)
    {
        try {
            $user = auth()->user();
            $class = $user->classes()->where('class_id', $class_id)->firstOrFail();
            $quiz = $class->quizzes()->where('id', $id)->firstOrFail();
            $questions = $quiz->questions;
            return response()->json([
                'quiz' => [
                    'id' => $quiz->id,
                    'name' => $quiz->name,
                    'description' => $quiz->description,
                    'start_time' => $quiz->start_time,
                    'end_time' => $quiz->end_time,
                ],
                'questions' => $questions->map(function ($question) {
                    $answers = $question->answer_option ? json_decode($question->answer_option) : [];
                    if($question->is_multiple_choice) {
                        $answers = array_map(function ($answer) use ($question) {
                            return [
                                'answer' => $answer,
                                'correct' => $answer === $question->correct_answer,
                            ];
                        }, $answers);
                    }
                    return [
                        'id' => $question->id,
                        'question' => $question->question,
                        'is_multiple_choice' => (bool)$question->is_multiple_choice,
                        'score' => $question->points,
                        'answers' => $answers,
                    ];
                }),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $class_id, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'class_id' => 'required|integer|exists:classes,id',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'questions' => 'required|array',
                'questions.*.question' => 'required|string',
                'questions.*.is_multiple_choice' => 'required|boolean',
                'questions.*.score' => 'required|integer',
                'questions.*.answers' => 'nullable|array',
                'questions.*.answers.*.answer' => 'nullable|string',
                'questions.*.answers.*.correct' => 'nullable|boolean',
                'deleted_questions' => 'nullable|array',
            ]);

            $user = auth()->user();
            $class = $user->classes()->where('class_id', $class_id)->firstOrFail();
            $quiz = $class->quizzes()->where('id', $id)->firstOrFail();
            // only update if quiz do not have any submissions and user is the teacher or quiz status is pending
            if(Answer::where('quiz_id', $quiz->id)->exists() || $user->role->name !== 'teacher' || $quiz->status !== 'pending') {
                return response()->json([
                    'message' => 'Quiz cannot be updated',
                ], 400);
            }
            // update quiz info
            $quiz->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_time' => Carbon::parse($request->start_time),
                'end_time' => Carbon::parse($request->end_time),
            ]);
            // delete questions
            if($request->deleted_questions) {
                $quiz->questions()->whereIn('id', $request->deleted_questions)->delete();
            }
            // update questions
            foreach ($request->questions as $question) {
                $correctAnswer = array_filter($question['answers'], function ($answer) {
                    return $answer['correct'];
                });
                $correctAnswer = array_values($correctAnswer)[0] ?? null;
                $answerOptions = array_map(function ($answer) {
                    return $answer['answer'];
                }, $question['answers']);
                $quiz->questions()->updateOrCreate([
                    'id' => $question['id'],
                ], [
                    'is_multiple_choice' => $question['is_multiple_choice'],
                    'question' => $question['question'],
                    'points' => $question['score'],
                    'correct_answer' => $question['is_multiple_choice'] ? $correctAnswer['answer'] : "",
                    'answer_option' => $question['is_multiple_choice'] ? json_encode($answerOptions, JSON_UNESCAPED_UNICODE) : "",
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $class_id, string $id): JsonResponse
    {
        try {
            $user = auth()->user();
            $class = $user->classes()->where('class_id', $class_id)->firstOrFail();
            $quiz = $class->quizzes()->where('id', $id)->firstOrFail();
            // only delete if user is the teacher
            if($user->role->name !== 'teacher') {
                return response()->json([
                    'message' => 'Quiz cannot be deleted',
                ], 400);
            }
            $quiz->delete();
            return response()->json([
                'message' => 'Quiz deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
