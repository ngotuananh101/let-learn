<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\UserLogPost;
use Illuminate\Http\Request;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:post,comment',
            ]);

            switch ($request->type) {
                case 'post':
                    $request->validate([
                        'class_id' => 'nullable|integer',
                        'title' => 'required|string|max:255',
                        'content' => 'nullable|string|max:1000',
                        'status' => 'nullable|in:active,pending,inactive',
                        'score_reporting' => 'nullable|integer',
                        'tags' => 'nullable|string',
                        'views' => 'nullable|integer',
                        'like_status' => 'nullable|in:like,unlike',
                    ]);

                    $post = new Post();
                    $post->user_id = auth()->user()->id;
                    $post->class_id = $request->input('class_id', null);
                    $post->title = $request->input('title');
                    $post->content = $request->input('content', $request->input('title'));
                    $post->status = $request->input('status', 'inactive');
                    $post->score_reporting = $request->input('score_reporting', 0);
                    $post->tags = $request->input('tags');
                    $post->views = $request->input('views', 0);
                    $post->save();
                    //create a new PostLike for this user and this post
                    $postLike = new PostLike();
                    $postLike->user_id = auth()->user()->id;
                    $postLike->post_id = $post->id;
                    $postLike->like_status = 'unlike';
                    $postLike->save();

                    return response()->json([
                        'message' => 'Post created',
                        'post' => $post,
                        'postLike' => $postLike
                    ], 200);
                    break;
                case 'comment':
                    $request->validate([
                        'post_id' => 'required|integer',
                        'comment' => 'required|string',
                        'status' => 'nullable|in:active,pending,inactive',
                        'votes' => 'nullable|integer',
                    ]);

                    $comment = new Comment();
                    //user_id is auth()->user()->id
                    $comment->user_id = auth()->user()->id;
                    $comment->post_id = $request->input('post_id');
                    $comment->comment = $request->input('comment');
                    $comment->status = $request->input('status', 'active');
                    $comment->votes = $request->input('votes', 0);
                    $comment->save();

                    //create a new CommentVote for this user and this comment
                    $commentVote = new CommentVote();
                    $commentVote->user_id = auth()->user()->id;
                    $commentVote->post_id = $comment->post_id;
                    $commentVote->comment_id = $comment->id;
                    $commentVote->vote_status = 'nonvote';
                    $commentVote->save();
                    return response()->json([
                        'message' => 'Comment created',
                        'comment' => $comment,
                        'commentVote' => $commentVote
                    ], 200);
                    break;
                default:
                    return response()->json(['message' => 'Invalid type'], 400);
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            //get post by id
            $post = Post::findOrFail($id);
            //if post not found, return 404
            if (!$post || $post->status != 'active') {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 404,
                    'message' => 'Post not found'
                ], 404);
            }
            //if post has class_id, check if user is in class, if not return 403
            if ($post->class_id) {
                if (!auth()->user()->classes->contains($post->class_id)) {
                    return response()->json([
                        'status' => 'error',
                        'status_code' => 403,
                        'message' => 'Forbidden access!'
                    ], 403);
                }
            }
            //get title, content, tags, views, created_at
            $post = $post;
            //get comments by post id
            $comments = Comment::where('post_id', $id)->get();
            //if post has class_id, get class by class_id
            if ($post->class_id) {
                $class = $post->class->only(['name']);
            } else {
                $class = null;
            }
            ////check user log is exist, if exist update accessed_at, else create new user log
            if (UserLogPost::where('user_id', auth()->user()->id)->where('post_id', $id)->exists()) {
                $userLogPost = UserLogPost::where('user_id', auth()->user()->id)->where('post_id', $id)->first();
                $userLogPost->accessed_at = now();
                $userLogPost->save();
            } else {
                $userLogPost = new UserLogPost();
                $userLogPost->user_id = auth()->user()->id;
                $userLogPost->post_id = $id;
                $userLogPost->accessed_at = now();
                $userLogPost->save();
                //update post views
                $post->views = $post->views + 1;
            }
            $data = [
                'post' => $post,
                'comments' => $comments,
                'class' => $class ?? null,
            ];

            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:post,comment',
            ]);

            switch ($request->type) {
                case 'post':
                    $request->validate([
                        'class_id' => 'nullable|integer',
                        'title' => 'nullable|string|max:255',
                        'content' => 'nullable|string|max:1000',
                        'status' => 'nullable|in:active,pending,inactive',
                        'score_reporting' => 'nullable|integer',
                        'tags' => 'nullable|string',
                        'like' => 'nullable|in:like,unlike',
                    ]);
                    $post = Post::findOrFail($id);
                    //check if class_id of post is null or not
                    //get current liked status of post
                    $postLike = PostLike::where('user_id', auth()->user()->id)->where('post_id', $post->id)->first();
                    $liked = $postLike->like_status;
                    $poststatus = $post->score_reporting;

                    if ($liked == 'like') {
                        if ($request->input('like') == 'unlike') {
                            $likestatus =  'unlike';
                            $poststatus = $post->score_reporting - 1;
                        }
                        if ($request->input('like') == 'like' || $request->input('like') == null) {
                            $likestatus = 'like';
                            $poststatus = $post->score_reporting;
                        }
                    } else if ($liked == 'unlike') {
                        if ($request->input('like') == 'unlike' || $request->input('like') == null) {
                            $likestatus = 'unlike';
                            $poststatus = $post->score_reporting;
                        }
                        if ($request->input('like') == 'like') {
                            $likestatus = 'like';
                            $poststatus = $post->score_reporting + 1;
                        }
                    }
                    //check if user_log
                    $userRole = auth()->user()->role->name;
                    if ($post->class_id) {
                        //if class_id is not null, check if user is teacher or student of the class
                        if (($userRole == 'teacher' && auth()->user()->id == $post->user_id) || ($userRole == 'student' && auth()->user()->id == $post->user_id)) {

                            $post->user_id = auth()->user()->id;
                            $post->class_id = $request->input('class_id', $post->class_id);
                            $post->title = $request->input('title', $post->title);
                            $post->content = $request->input('content', $post->content);
                            $post->status = $request->input('status', $post->status);
                            $post->score_reporting = $poststatus;
                            $post->tags = $request->input('tags');
                            $post->views = $request->input('views', $post->views);
                            $post->save();

                            //update post like status
                            $postLike->like_status = $likestatus;
                            $postLike->save();
                            return response()->json([
                                'message' => 'Post updated successfully',
                                'post' => $post,
                                'postLike' => $postLike
                            ], 200);
                        } else {
                            return response()->json(['message' => 'You are not authorized to update this post'], 403);
                        }
                    } else {
                        //for case post is not in class
                        if (auth()->user()->id == $post->user_id) {
                            $post->user_id = auth()->user()->id;
                            $post->title = $request->input('title', $post->title);
                            $post->content = $request->input('content', $post->content);
                            $post->status = $request->input('status', $post->status);
                            $post->score_reporting = $poststatus;
                            $post->tags = $request->input('tags');
                            $post->views = $request->input('views', $post->views);
                            $post->save();

                            //update post like status
                            $postLike->like_status = $likestatus;
                            $postLike->save();
                            return response()->json([
                                'message' => 'Post updated',
                                'post' => $post,
                                'postLike' => $postLike
                            ], 200);
                        } else {
                            return response()->json(['message' => 'You are not authorized to update this post'], 403);
                        }
                    }
                    break;
                case 'comment':
                    $request->validate([
                        'comment_id' => 'required|integer',
                        'comment' => 'nullable|string',
                        'status' => 'nullable|in:active,pending,inactive',
                        'votes' => 'nullable|integer',
                        'votetype' => 'nullable|in:upvote,downvote,nonvote',
                    ]);
                    $comment = Comment::findOrFail($request->input('comment_id'));
                    $votetype = $request->input('votetype');
                    //check if user update vote type
                    //check if post contains comment_id has class_id or not
                    //get current voted status of comment
                    $commentVote = CommentVote::where('user_id', auth()->user()->id)->where('comment_id', $comment->id)->first();
                    $voted = $commentVote->vote_status;
                    $votesstatus = $comment->votes;

                    if ($voted == 'upvote') {
                        if ($votetype == 'downvote') {
                            $votetypestatus = 'downvote';
                            $votesstatus = $comment->votes - 1;
                        }
                        if ($votetype == 'upvote' || $votetype == null) {
                            $votetypestatus = 'upvote';
                            $votesstatus = $comment->votes;
                        }
                        if ($votetype == 'nonvote') {
                            $votetypestatus = 'nonvote';
                            $votesstatus = $comment->votes;
                        }
                    } else if ($voted == 'downvote') {
                        if ($votetype == 'downvote' || $votetype == null) {
                            $votetypestatus = 'downvote';
                            $votesstatus = $comment->votes;
                        }
                        if ($votetype == 'upvote') {
                            $votetypestatus = 'upvote';
                            $votesstatus = $comment->votes + 1;
                        }
                        if ($votetype == 'nonvote') {
                            $votetypestatus = 'nonvote';
                            $votesstatus = $comment->votes;
                        }
                    } else if ($voted == 'nonvote') {
                        if ($votetype == 'downvote') {
                            $votetypestatus = 'downvote';
                            $votesstatus = $comment->votes - 1;
                        }
                        if ($votetype == 'upvote') {
                            $votetypestatus = 'upvote';
                            $votesstatus = $comment->votes + 1;
                        }
                        if ($votetype == 'nonvote' || $votetype == null) {
                            $votetypestatus = 'nonvote';
                            $votesstatus = $comment->votes;
                        }
                    }

                    //check if user is teacher or student of the class
                    $userRole = auth()->user()->role->name;
                    if ($comment->post->class_id) {
                        if (($userRole == 'teacher' && auth()->user()->id == $comment->user_id) || ($userRole == 'student' && auth()->user()->id == $comment->user_id)) {
                            $comment->user_id = auth()->user()->id;
                            //post_id is $id
                            $comment->post_id = $id;
                            $comment->comment = $request->input('comment', $comment->comment);
                            $comment->status = $request->input('status', $comment->status);
                            $comment->votes = $votesstatus;
                            $comment->save();

                            //update comment vote status
                            $commentVote->vote_status = $votetypestatus;
                            $commentVote->save();
                            return response()->json([
                                'message' => 'Comment updated',
                                'comment' => $comment,
                                'commentVote' => $commentVote
                            ], 200);
                        } else {
                            return response()->json(['message' => 'You are not authorized to update this comment'], 403);
                        }
                    } else {
                        if (auth()->user()->id == $comment->user_id) {
                            $comment->user_id = auth()->user()->id;
                            //post_id is $id
                            $comment->post_id = $id;
                            $comment->comment = $request->input('comment', $comment->comment);
                            $comment->status = $request->input('status', $comment->status);
                            $comment->votes = $votesstatus;
                            $comment->save();

                            //update comment vote status
                            $commentVote->vote_status = $votetypestatus;
                            $commentVote->save();
                            return response()->json([
                                'message' => 'Comment updated',
                                'comment' => $comment,
                                'commentVote' => $commentVote
                            ], 200);
                        } else {
                            return response()->json(['message' => 'You are not authorized to update this comment'], 403);
                        }
                    }
                    break;

                default:
                    return response()->json(['message' => 'Invalid type'], 400);
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:post,comment',
            ]);

            switch ($request->type) {
                case 'post':
                    $post = Post::findOrFail($id);
                    $userRole = auth()->user()->role->name;
                    if ($post->class_id) {

                        if (($userRole == 'teacher') || ($userRole == 'student' && auth()->user()->id == $post->user_id)) {
                            //delete post and comments and post likes and comments votes
                            //delete post likes
                            $postLikes = PostLike::where('post_id', $id)->get();
                            foreach ($postLikes as $postLike) {
                                $postLike->delete();
                            }
                            //delete comments
                            $comments = Comment::where('post_id', $id)->get();
                            foreach ($comments as $comment) {
                                $comment->delete();
                            }
                            //delete comment votes
                            $commentVotes = CommentVote::where('post_id', $id)->get();
                            foreach ($commentVotes as $commentVote) {
                                $commentVote->delete();
                            }
                            $post->delete();
                            return response()->json(['message' => 'Post deleted'], 200);
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 403,
                                'message' => 'You are not authorized to delete this post'
                            ], 403);
                        }
                    } else {
                        if (auth()->user()->id == $post->user_id) {
                            $postLikes = PostLike::where('post_id', $id)->get();
                            foreach ($postLikes as $postLike) {
                                $postLike->delete();
                            }
                            $commentVotes = CommentVote::where('post_id', $id)->get();
                            foreach ($commentVotes as $commentVote) {
                                $commentVote->delete();
                            }
                            $comments = Comment::where('post_id', $id)->get();
                            foreach ($comments as $comment) {
                                $comment->delete();
                            }
                            $post->delete();
                            return response()->json(['message' => 'Post deleted'], 200);
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 403,
                                'message' => 'You are not authorized to delete this post'
                            ], 403);
                        }
                    }
                    break;
                case 'comment':
                    $request->validate([
                        'comment_id' => 'required|integer',
                    ]);
                    $comment = Comment::findOrFail($request->input('comment_id'));
                    $userRole = auth()->user()->role->name;
                    if ($comment->post->class_id) { //check if post contains comment_id has class_id or not
                        if (($userRole == 'teacher') || ($userRole == 'student') && (auth()->user()->id == $comment->user_id)) {
                            //delete comment and comment votes
                            $commentVotes = CommentVote::where('comment_id', $request->input('comment_id'))->get();
                            foreach ($commentVotes as $commentVote) {
                                $commentVote->delete();
                            }
                            $comment->delete();
                            return response()->json(['message' => 'Comment deleted'], 200);
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 403,
                                'message' => 'You are not authorized to delete this comment'
                            ], 403);
                        }
                    } else {
                        if (auth()->user()->id == $comment->user_id) {
                            //delete comment and comment votes
                            $commentVotes = CommentVote::where('comment_id', $request->input('comment_id'))->get();
                            foreach ($commentVotes as $commentVote) {
                                $commentVote->delete();
                            }
                            $comment->delete();
                            return response()->json(['message' => 'Comment deleted'], 200);
                        } else {
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 403,
                                'message' => 'You are not authorized to delete this comment'
                            ], 403);
                        }
                    }
                    break;
                default:
                    return response()->json(['message' => 'Invalid type'], 400);
                    break;
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
