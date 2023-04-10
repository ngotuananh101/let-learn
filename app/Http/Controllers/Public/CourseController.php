<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Font;
use PhpParser\Node\Stmt\TryCatch;

class CourseController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Course::all()->load(['user', 'school', 'class']), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
            ]);
            $course = auth()->user()->courses()->create($request->all());
            return response()->json($course->load(['user', 'school', 'class']), 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    public function show(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:info,lessons',
            ]);
            switch ($request->input('type')) {
                case 'info':
                    $course = Course::findOrFail($id);
                    return response()->json($course->load(['lessons']), 200);
                    break;
                case 'lessons':
                    $request->validate([
                        'keyword' => 'required|string',
                    ]);
                    // search lessons by keyword
                    $lessons = Lesson::where('name', 'like', '%' . $request->input('keyword') . '%')->get();
                    return response()->json($lessons, 200);
                    break;
                default:
                    return response()->json([
                        'message' => 'Invalid request',
                        'status' => 'error'
                    ], 400);
                    break;
            }
            $course = Course::findOrFail($id);
            return response()->json($course->load(['lessons']), 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'required|in:info,add_lesson,remove_lesson',
            ]);
            switch ($request->input('type')) {
                case 'info':
                    $request->validate([
                        'name' => 'required|string',
                        'description' => 'required|string',
                        'password' => 'nullable|string',
                        'status' => 'required|in:active,inactive',
                    ]);
                    $course = Course::findOrFail($id);
                    //check if user is owner of the course
                    if ($course->user_id != auth()->user()->id)
                        return response()->json([
                            'message' => 'You are not authorized to update this course',
                            'status' => 'error'
                        ], 400);

                    if ($request->has('password') && $request->input('password') != '')
                        $request->merge(['password' => bcrypt($request->input('password'))]);
                    $course->update($request->all());
                    return response()->json($course->load(['user', 'school', 'class']), 200);
                case 'add_lesson':
                    $request->validate([
                        'lesson_ids' => 'required|array',
                    ]);
                    $course = Course::findOrFail($id);
                    //check if user is owner of the course
                    if ($course->user_id != auth()->user()->id)
                        return response()->json([
                            'message' => 'You are not authorized to update this course',
                            'status' => 'error'
                        ], 400);
                    foreach ($request->input('lesson_ids') as $lesson_id) {
                        $lesson = Lesson::findOrFail($lesson_id);
                        //check if lesson is dont have password and status is active
                        if ($lesson->password != null || $lesson->status != 'active')
                            return response()->json([
                                'message' => 'Lesson ' . $lesson->name . ' is not allowed to be added to the course',
                                'status' => 'error'
                            ], 400);
                    }
                    $course->lessons()->syncWithoutDetaching($request->input('lesson_ids'));
                    return response()->json($course->lessons, 200);
                case 'remove_lesson':
                    $request->validate([
                        'lesson_ids' => 'required|array',
                    ]);
                    $course = Course::findOrFail($id);
                    if ($course->user_id != auth()->user()->id)
                        return response()->json([
                            'message' => 'You are not authorized to update this course',
                            'status' => 'error'
                        ], 400);
                    $course = Course::findOrFail($id);
                    $course->lessons()->detach($request->input('lesson_ids'));
                    return response()->json($course->lessons, 200);
                default:
                    return response()->json([
                        'message' => 'Invalid request',
                        'status' => 'error'
                    ], 400);
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $course = Course::findOrFail($id);
            if ($course->user_id != auth()->user()->id)
                return response()->json([
                    'message' => 'You are not authorized to delete this course',
                    'status' => 'error'
                ], 400);
            $course->delete();
            return response()->json([
                'message' => 'Course deleted successfully',
                'status' => 'success'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'error'
            ], 400);
        }
    }
}
