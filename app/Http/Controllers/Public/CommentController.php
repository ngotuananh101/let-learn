<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $post_id): JsonResponse
    {
        try {
            $post = Post::findOrFail($post_id);
            $comments = $post->comments()->orderBy('created_at', 'desc')->paginate(10);
            $current_page = $comments->currentPage();
            if ($current_page == 1) {
                $post->views = $post->views + 1;
                $post->save();
            }
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
                'next_page_url' => $comments->nextPageUrl(),
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
    public function store(Request $request, string $post_id)
    {
        try {
            $post = Post::findOrFail($post_id);
            $request->validate([
                'comment' => 'required|string',
            ]);
            $comment = $post->comments()->create([
                'user_id' => auth()->user()->id,
                'comment' => $request->input('comment'),
            ]);
            return response()->json([
                'message' => 'Comment created successfully',
                'comment' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'author' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'email' => $comment->user->email,
                        'avatar' => 'https://www.gravatar.com/avatar/' . md5($comment->user->email) . '?s=200&d=mm',
                    ],
                    'created_at' => Carbon::parse($comment->created_at)->diffForHumans(),
                ],
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
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $post_id, string $id): JsonResponse
    {
        try {
            $post = Post::findOrFail($post_id);
            $comment = $post->comments()->findOrFail($id);
            $comment->delete();
            return response()->json([
                'message' => 'Comment deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
