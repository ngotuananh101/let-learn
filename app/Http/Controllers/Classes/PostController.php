<?php

namespace App\Http\Controllers\Classes;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $class_id): JsonResponse
    {
        try {
            $posts = Post::where('class_id', $class_id)->orderBy('created_at', 'desc')->paginate(10);
            $posts_data = $posts->map(function ($post) {
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
                ];
            });
            return response()->json([
                'posts' => $posts_data,
                'next_page_url' => $posts->nextPageUrl(),
                'prev_page_url' => $posts->previousPageUrl(),
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
                'title' => 'required|string',
                'content' => 'required|string',
                'class_id' => 'required|string|exists:classes,id',
            ]);
            $post = new Post();
            $post->title = $request->input('title');
            $post->content = $request->input('content');
            $post->class_id = $request->input('class_id');
            $post->user_id = auth()->user()->id;
            $post->tags = 'class';
            $post->save();
            return response()->json([
                'message' => 'Post created successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $class_id, string $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:load_comment',
            ]);
            $class = Classes::where('id', $class_id)->firstOrFail();
            $post = $class->posts()->where('id', $id)->firstOrFail();
            if ($request->type == 'load_comment') {
                $post->views = $post->views + 1;
                $post->save();
                $comments = $post->comments()->orderBy('created_at', 'desc')->paginate(10);
                $comments_data = $comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'comment' => $comment->comment,
                        'author' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'email' => $comment->user->email,
                            'avatar' => 'https://www.gravatar.com/avatar/' . md5($comment->user->email) . '?s=200&d=mm',
                        ],
                        'created_at' => Carbon::parse($comment->created_at)->diffForHumans(),
                    ];
                });
                return response()->json([
                    'comments' => $comments_data,
                    'total_page' => $comments->lastPage(),
                    'current_page' => $comments->currentPage(),
                ], 200);
            }
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
                'type' => 'required|string|in:add_comment,update_post',
            ]);
            $class = Classes::where('id', $class_id)->firstOrFail();
            $post = $class->posts()->where('id', $id)->firstOrFail();
            if ($request->type == 'add_comment') {
                $request->validate([
                    'comment' => 'required|string',
                ]);
                $post->comments()->create([
                    'user_id' => auth()->user()->id,
                    'comment' => $request->comment,
                ]);
                return response()->json([
                    'message' => 'Comment added successfully',
                ], 200);
            } else if($request->type == 'update_post'){
                // check if user is author of this post
                if($post->user_id != auth()->user()->id){
                    return response()->json([
                        'message' => 'You are not authorized to update this post',
                    ], 403);
                }
                $request->validate([
                    'title' => 'required|string',
                    'content' => 'required|string',
                ]);
                $post->title = $request->input('title');
                $post->content = $request->input('content');
                $post->save();
                return response()->json([
                    'message' => 'Post updated successfully',
                ], 200);
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
    public function destroy(Request $request, string $class_id, string $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:delete_comment,delete_post',
            ]);
            $class = Classes::where('id', $class_id)->firstOrFail();
            $post = $class->posts()->where('id', $id)->firstOrFail();
            if ($request->type == 'delete_comment') {
                $request->validate([
                    'comment_id' => 'required|string|exists:comments,id',
                ]);
                $comment = $post->comments()->where('id', $request->comment_id)->firstOrFail();
                //check if the comment is owned by the user or the user is the author of the post or the user is the admin or the user is the teacher of the class
                if ($comment->user_id != auth()->user()->id && $post->user_id != auth()->user()->id && auth()->user()->role->name != 'superadmin' && $class->member()->where('user_id', auth()->user()->id)->where('role_id', '5')->count() == 0) {
                    return response()->json([
                        'message' => 'You are not authorized to delete this comment',
                    ], 400);
                }
                $comment->delete();
                return response()->json([
                    'message' => 'Comment deleted successfully',
                ], 200);
            } else if($request->type == 'delete_post'){
                // check if the user is the author of the post or the user is the admin or the user is the teacher of the class
                if($post->user_id != auth()->user()->id && auth()->user()->role->name != 'superadmin' && $class->member()->where('user_id', auth()->user()->id)->where('role_id', '5')->count() == 0){
                    return response()->json([
                        'message' => 'You are not authorized to delete this post',
                    ], 400);
                }
                $post->delete();
                return response()->json([
                    'message' => 'Post deleted successfully',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
