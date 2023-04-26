<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = $request->input('query');
            // search lesson by name and get first 10 results
            $lessons = Lesson::where('status', 'active')->where('school_id', null)->where('name', 'like', "%$query%")->take(10)->get();
            // search course by name and get first 10 results
            $courses = Course::where('status', 'active')->where('school_id', null)->where('name', 'like', "%$query%")->take(10)->get();
            // search post by title and get first 10 results
            $posts = Post::where('status', 'active')->where('class_id', null)->where('title', 'like', "%$query%")->take(10)->get();
            return response()->json([
                'lessons' => $lessons->map(function ($lesson) {
                    return [
                        'id' => $lesson->id,
                        'name' => $lesson->name,
                        'detail_count' => $lesson->details->count(),
                        'author' => [
                            'id' => $lesson->user->id,
                            'name' => $lesson->user->name,
                            'avatar' => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($lesson->user->email))) . '?s=200',
                        ]
                    ];
                }),
                'courses' => $courses->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'name' => $course->name,
                        'lesson_count' => $course->lessons->count(),
                        'author' => [
                            'id' => $course->user->id,
                            'name' => $course->user->name,
                            'avatar' => 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($course->user->email))) . '?s=200',
                        ]
                    ];
                }),
                'posts' => $posts->map(function ($post){
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'content' => $post->content,
                        'author' => [
                            'id' => $post->user->id,
                            'name' => $post->user->name,
                            'email' => $post->user->email,
                            'avatar' => 'https://www.gravatar.com/avatar/' . md5($post->user->email) . '?s=200&d=mm',
                        ],
                        'comment_count' => $post->comments->count(),
                        'created_at' => Carbon::parse($post->created_at)->diffForHumans(),
                        'like_count' => $post->likes->where('like_status', 'like')->count(),
                        'liked' => $post->likes->where('user_id', auth()->user()->id)->where('like_status', 'like')->count() > 0,
                        'views' => $post->views,
                    ];
                }),
                'query' => $query,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
