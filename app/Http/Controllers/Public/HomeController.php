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
            return response()->json([
                // get random 6 lessons
                'lessons' => $user->lessons->where('status', 'active')->take(6),
                'courses' => $user->courses->where('status', 'active')->take(6),
                'other_lesson' => Lesson::where('user_id', '!=', $user->id)
                    ->where('status', 'active')
                    ->where('password', null)
                    ->inRandomOrder()
                    ->take(6)
                    ->get(),
                'other_course' => Course::where('user_id', '!=', $user->id)
                    ->where('status', 'active')
                    ->where('password', null)
                    ->inRandomOrder()
                    ->take(6)
                    ->get(),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
