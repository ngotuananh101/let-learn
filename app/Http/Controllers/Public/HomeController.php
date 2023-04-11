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
    public function index()
    {
        try {
            //get user
            $user = auth()->user();
            //if user have no lessons or not enough 6 lesson, load current user lessons and remaining random active lessons
            if ($user->lessons->count() < 6 ) {
                $lessons = $user->lessons->merge(Lesson::where('status', 'active')->inRandomOrder()->limit(6 - ($user->lessons->count()))->get());
            }
            $lessons = $lessons->map(function ($lesson) {
                return $lesson->makeHidden('password');
            });
            //if user have no courses or not enough 6 courses, load current user courses and remaining random active courses
            if ($user->courses->count() < 6) {
                $courses = $user->courses->merge(Course::where('status', 'active')->inRandomOrder()->limit(6 - ($user->courses->count()))->get());
            }
            $courses = $courses->map(function ($course) {
                return $course->makeHidden('password');
            });
            return response()->json([
                //dont load lessons and courses
                'user' => $user,
                'lessons' => $lessons,
                'courses' => $courses,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
