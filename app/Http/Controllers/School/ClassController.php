<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Comment;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'slug' => 'required|string',
            ]);
            $user = auth()->user();
            // get school by slug
            $school = School::where('slug', $request->slug)->first();
            // check if user is admin or super or user is the owner of the school
            if ($user->role->name === 'admin' || $user->role->name === 'super' || $user->school_id === $school->id) {
                // get all classes
                $classes = $school->classes;
                return response()->json([
                    'classes' => $classes,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to view this resource',
                ], 403);
            }
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
                'school_slug' => 'required|string|exists:schools,slug',
                'name' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);
            $user = auth()->user();
            $school = School::where('slug', $request->school_slug)->firstOrFail();
            // check if user is admin or super or user is the owner of the school
            if ($user->role->name === 'admin' || $user->role->name === 'super' || $user->school_id === $school->id) {
                Classes::create([
                    'school_id' => $school->id,
                    'name' => $request->name,
                    'status' => 'active',
                    'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                    'end_date' => Carbon::parse($request->end_date)->format('Y-m-d'),
                ]);
                return response()->json([
                    'message' => 'Class created successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to view this resource',
                ], 403);
            }
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
        try {
            if ($this->checkPermission($id)) {
                $class = Classes::findOrFail($id);
                return response()->json([
                    'class' => $class->load(['member', 'quizzes', 'posts']),
                ], 200);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to view this resource',
                ], 403);
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
    public function edit(Request $request, string $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:quiz,post',
            ]);
            switch ($request->input('type')) {
                case 'quiz':
                    if ($this->checkPermission($id)) {
                        $request->validate([
                            'quiz_id' => 'required|string',
                        ]);
                        $class = Classes::findOrFail($id);
                        $quiz = $class->quizzes()->where('id', $request->quiz_id)->first();
                        if ($quiz) {
                            return response()->json([
                                'quiz' => $quiz->load(['questions', 'answers']),
                            ], 200);
                        } else {
                            return response()->json([
                                'message' => 'Quiz not found',
                            ], 404);
                        }
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }
                case 'post':
                    if ($this->checkPermission($id)) {
                        $request->validate([
                            'post_id' => 'required|string',
                        ]);
                        $class = Classes::findOrFail($id);
                        $post = $class->posts()->where('id', $request->post_id)->firstOrFail();
                        $post = [
                            'id' => $post->id,
                            'author' => $post->user->name,
                            'avatar' => 'https://www.gravatar.com/avatar/' . md5($post->user->email) . '?s=200&d=mm',
                            'title' => $post->title,
                            'content' => $post->content,
                            'vote' => $post->likes->count() ?? 0,
                            'upvote' => $post->likes->where('like_status', 'like')->count() ?? 0,
                            'downvote' => $post->likes->where('like_status', 'unlike')->count() ?? 0,
                            'created_at' => Carbon::parse($post->created_at)->diffForHumans(),
                        ];
                        $comments = Comment::where('post_id', $request->post_id)->paginate(10);
                        if ($post) {
                            return response()->json([
                                'post' => $post,
                                'comments' => $comments->map(function ($comment) {
                                    return [
                                        'id' => $comment->id,
                                        'author' => $comment->user->name,
                                        'avatar' => 'https://www.gravatar.com/avatar/' . md5($comment->user->email) . '?s=200&d=mm',
                                        'upvote' => $comment->commentVote->where('vote_status', 'upvote')->count() ?? 0,
                                        'downvote' => $comment->commentVote()->where('vote_status', 'downvote')->count() ?? 0,
                                        'content' => $comment->comment,
                                        'created_at' => Carbon::parse($comment->created_at)->diffForHumans(),
                                    ];
                                }),
                                'total_page' => $comments->lastPage(),
                                'current_page' => $comments->currentPage(),
                            ], 200);
                        } else {
                            return response()->json([
                                'message' => 'Post not found',
                            ], 404);
                        }
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:info,add_member,remove_member,update_quiz',
            ]);
            switch ($request->input('type')) {
                case 'info':
                    $request->validate([
                        'name' => 'required|string',
                        'status' => 'required|string|in:active,inactive',
                        'start_date' => 'required|date',
                        'end_date' => 'required|date',
                    ]);
                    if ($this->checkPermission($id)) {
                        $class = Classes::findOrFail($id);
                        $class->name = $request->name;
                        $class->status = $request->status;
                        $class->start_date = $request->start_date;
                        $class->end_date = $request->end_date;
                        $class->save();
                        return response()->json([
                            'message' => 'Class updated successfully',
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }
                case 'add_member':
                    $request->validate([
                        'emails' => 'required|array',
                        'role' => 'required|string|in:5,6',
                    ]);
                    if ($this->checkPermission($id)) {
                        $class = Classes::findOrFail($id);
                        foreach ($request->emails as $email) {
                            $user = User::where('email', $email)->first();
                            if ($user) {
                                // check if user is already a member of the class
                                if ($class->member->contains($user->id)) {
                                    return response()->json([
                                        'message' => 'User is already a member of the class',
                                    ], 400);
                                } else {
                                    $class->member()->attach($user->id);
                                }
                            } else {
                                $user = new User();
                                $username = explode('@', $email);
                                $user->username = $username[0];
                                $user->school_id = $class->school_id;
                                $user->name = $email;
                                $user->email = $email;
                                $user->password = Hash::make('123@123a');
                                $user->role_id = $request->role;
                                $user->date_of_birth = Carbon::now();
                                $user->save();
                                $class->member()->attach($user->id);
                            }
                        }
                        return response()->json([
                            'message' => 'Member added successfully',
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }
                case 'remove_member':
                    $request->validate([
                        'ids' => 'required|array',
                    ]);
                    if ($this->checkPermission($id)) {
                        $class = Classes::findOrFail($id);
                        foreach ($request->ids as $id) {
                            $class->member()->detach($id);
                        }
                        return response()->json([
                            'message' => 'Member removed successfully',
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }
                case 'update_quiz':
                    $request->validate([
                        'quiz_id' => 'required|numeric|exists:quizzes,id',
                        'status' => 'required|string|in:active,inactive',
                    ]);
                    if ($this->checkPermission($id)) {
                        $class = Classes::findOrFail($id);
                        $class->quizzes()->where('id', $request->quiz_id)->update([
                            'status' => $request->status,
                        ]);
                        return response()->json([
                            'message' => 'Quiz updated successfully',
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }

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
    public function destroy(Request $request, string $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:delete_post,delete_comment,delete_quiz,delete_class',
            ]);
            switch ($request->input('type')) {
                case 'delete_post':
                    $request->validate([
                        'post_id' => 'required|numeric|exists:posts,id',
                    ]);
                    if ($this->checkPermission($id)) {
                        $class = Classes::findOrFail($id);
                        $post = $class->posts()->where('id', $request->post_id)->first();
                        if ($post) {
                            $post->delete();
                            return response()->json([
                                'message' => 'Post deleted successfully',
                            ], 200);
                        } else {
                            return response()->json([
                                'message' => 'Post not found',
                            ], 404);
                        }
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }
                case 'delete_comment':
                    $request->validate([
                        'comment_id' => 'required|numeric|exists:comments,id',
                        'post_id' => 'required|numeric|exists:posts,id',
                    ]);
                    if ($this->checkPermission($id)) {
                        $class = Classes::findOrFail($id);
                        $post = $class->posts()->where('id', $request->post_id)->first();
                        if ($post) {
                            $comment = $post->comments()->where('id', $request->comment_id)->first();
                            if ($comment) {
                                $comment->delete();
                                return response()->json([
                                    'message' => 'Comment deleted successfully',
                                ], 200);
                            } else {
                                return response()->json([
                                    'message' => 'Comment not found',
                                ], 404);
                            }
                        } else {
                            return response()->json([
                                'message' => 'Post not found',
                            ], 404);
                        }
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }
                case 'delete_quiz':
                    $request->validate([
                        'quiz_id' => 'required|numeric|exists:quizzes,id',
                    ]);
                    if ($this->checkPermission($id)) {
                        $class = Classes::findOrFail($id);
                        $quiz = $class->quizzes()->where('id', $request->quiz_id)->first();
                        if ($quiz) {
                            $quiz->delete();
                            return response()->json([
                                'message' => 'Quiz deleted successfully',
                            ], 200);
                        } else {
                            return response()->json([
                                'message' => 'Quiz not found',
                            ], 404);
                        }
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }
                case 'delete_class':
                    if ($this->checkPermission($id)) {
                        $class = Classes::findOrFail($id);
                        $class->delete();
                        return response()->json([
                            'message' => 'Class deleted successfully',
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'You are not authorized to view this resource',
                        ], 403);
                    }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check permission for the specified resource.
     * @param string $id The class id
     */
    public function checkPermission(string $id)
    {
        $user = auth()->user();
        if ($user->role->name === 'admin' || $user->role->name === 'super') {
            return true;
        } else {
            $class = Classes::find($id);
            if ($class && $class->school_id === $user->school_id) {
                return true;
            } else {
                return false;
            }
        }
    }
}
