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
            // get user
            $user = auth()->user()->load(['lessons', 'courses']);
            return response()->json([
                'user' => $user,
                // 'lessons' => $lessons,
                // 'courses' => $courses,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
