<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    //
    public function index(): JsonResponse
    {
        try {
            //get user
            $user = auth()->user();
            $lessons = $user->lessons->where('status', 'active')->take(6);
            $lessons = $lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'description' => $lesson->description,
                    'author' => $lesson->user->name,
                    'number_of_detail' => $lesson->details->count(),
                ];
            });

            $courses = $user->courses->where('status', 'active')->take(6);
            $courses = $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                    'author' => $course->user->name,
                    'number_of_lesson' => $course->lessons->count(),
                ];
            });

            $other_lesson = Lesson::where('user_id', '!=', $user->id)
                ->where('status', 'active')
                ->where('password', null)
                ->where('school_id', null)
                ->where('class_id', null)
                ->inRandomOrder()
                ->take(6)
                ->get();
            $other_lesson = $other_lesson->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'description' => $lesson->description,
                    'author' => $lesson->user->name,
                    'number_of_detail' => $lesson->details->count(),
                ];
            });

            $other_course = Course::where('user_id', '!=', $user->id)
                ->where('status', 'active')
                ->where('password', null)
                ->where('school_id', null)
                ->where('class_id', null)
                ->inRandomOrder()
                ->take(6)
                ->get();
            $other_course = $other_course->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                    'author' => $course->user->name,
                    'number_of_lesson' => $course->lessons->count(),
                ];
            });

            return response()->json([
                // get random 6 lessons
                'lessons' => $lessons,
                'courses' => $courses,
                'other_lesson' => $other_lesson,
                'other_course' => $other_course
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
