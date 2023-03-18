<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('questions')->get();

        return response()->json(['data' => $quizzes]);
    }

    public function show($id)
    {
        $quiz = Quiz::with('questions')->findOrFail($id);

        return response()->json(['data' => $quiz]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'questions' => 'required|array',
            'questions.*.type' => 'required|in:multiple_choice,constructed_response',
            'questions.*.question' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $quiz = Quiz::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        $questions = collect($request->input('questions'))->map(function ($question) {
            return new Question([
                'type' => $question['type'],
                'question' => $question['question'],
            ]);
        });

        $quiz->questions()->saveMany($questions);

        return response()->json(['data' => $quiz], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $quiz = Quiz::with('questions')->findOrFail($id);

        $quiz->update([
            'name' => $request->input('name', $quiz->name),
            'description' => $request->input('description', $quiz->description),
        ]);

        if ($request->has('questions')) {
            $questions = collect($request->input('questions'))->map(function ($question) {
                return new Question([
                    'type' => $question['type'],
                    'question' => $question['question'],
                ]);
            });

            $quiz->questions()->delete();
            $quiz->questions()->saveMany($questions);
        }

        return response()->json(['data' => $quiz]);
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}