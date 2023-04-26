<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use App\Models\UserLogPost;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // get all posts do not have class_id
            $posts = Post::where('class_id', null)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
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
                    'like_count' => $post->likes->where('like_status', 'like')->count(),
                    'liked' => $post->likes->where('user_id', auth()->user()->id)->where('like_status', 'like')->count() > 0,
                    'dislike_count' => $post->likes->where('like_status', 'unlike')->count(),
                    'disliked' => $post->likes->where('user_id', auth()->user()->id)->where('like_status', 'unlike')->count() > 0,
                    'tags' => $post->tags,
                    'views' => $post->views,
                ];
            });
            return response()->json([
                'posts' => $posts_data,
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $post = Post::create([
                'user_id' => auth()->user()->id,
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'status' => 'active',
                'score_reporting' => 0,
                'views' => 0,
                'tags' => '[]',
            ]);
            return response()->json([
                'message' => 'Post created successfully',
                'post' => [
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
                    'dislike_count' => $post->likes->where('like_status', 'unlike')->count(),
                    'disliked' => $post->likes->where('user_id', auth()->user()->id)->where('like_status', 'unlike')->count() > 0,
                    'views' => $post->views,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $post = Post::findOrfail($id);
            $post->update([
                'views' => $post->views + 1,
            ]);
            return response()->json([
                'post' => [
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
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'nullable|string|in:like,unlike',
            ]);
            $post = Post::find($id);
            if ($request->input('type')) {
                $like = PostLike::where('user_id', auth()->user()->id)->where('post_id', $id)->first();
                if ($like) {
                    $like->update([
                        'like_status' => $request->input('type'),
                    ]);
                } else {
                    PostLike::create([
                        'user_id' => auth()->user()->id,
                        'post_id' => $id,
                        'like_status' => $request->input('type'),
                    ]);
                }
                return response()->json([
                    'message' => 'Post updated successfully',
                ], 200);
            } else {
                $request->validate([
                    'title' => 'nullable|string|max:255',
                    'content' => 'nullable|string',
                ]);
                $post->update([
                    'title' => $request->input('title'),
                    'content' => $request->input('content'),
                ]);

                return response()->json([
                    'message' => 'Post updated successfully',
                    'post' => [
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
                        'dislike_count' => $post->likes->where('like_status', 'unlike')->count(),
                        'disliked' => $post->likes->where('user_id', auth()->user()->id)->where('like_status', 'unlike')->count() > 0,
                        'views' => $post->views,
                    ],
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $post = Post::find($id);
            $post->delete();
            return response()->json([
                'message' => 'Post deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
