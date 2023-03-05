<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\School;
use App\Models\UserLog;

use function PHPSTORM_META\type;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'string|in:info,lesson,course,search,recent,detail',
            ]);
            // $user = User::findOrFail($id);
            // check user is user login
            if ($request->user()->id != $id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You do not have permission to access this user'
                ], 403);
            }
            switch ($request->type) {
                case 'info':
                    //show all information of user 
                    $user = $request->user();
                    //check user is active
                    if ($user->status == 0) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 401,
                            'message' => 'User is not active'
                        ], 401);
                    }
                    return response()->json([
                        'user' => $user,
                        'message' => 'Show information of user successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'lesson':
                    //show all lesson by user id
                    $lessons = Lesson::where('user_id', $id)->get();
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Get all lesson by user id successfully',
                        'data' => $lessons
                    ], 200);
                    break;

                case 'course':
                    //show all course by user id
                    $courses = Course::where('user_id', $id)->get();
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Get all course by user id successfully',
                        'data' => $courses
                    ], 200);
                    break;
                case 'search': //search lesson, course, school by name of set
                    $request->validate([
                        'name' => 'required|string',
                    ]);
                    $name = $request->name;
                    if ($name == '') {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 404,
                            'message' => 'Not found'
                        ], 404);
                    }

                    //check if set, course, school name is exist
                    $lessons = Lesson::where('name', 'like', '%' . $name . '%')->get();
                    $courses = Course::where('name', 'like', '%' . $name . '%')->get();
                    $schools = School::where('name', 'like', '%' . $name . '%')->get();
                    if (count($lessons) == 0 && count($courses) == 0 && count($schools) == 0) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 404,
                            'message' => 'Not found'
                        ], 404);
                    }
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Search result',
                        'data' => [
                            'lessons' => $lessons,
                            'courses' => $courses,
                            'schools' => $schools
                        ]
                    ], 200);
                    break;
                case 'recent':
                    $user_log = UserLog::where('user_id', $id)->orderBy('created_at', 'desc')->get();
                    $recent_lessons = [];
                    foreach ($user_log as $log) {
                        $lesson = Lesson::find($log->lesson_id);
                        if ($lesson) {
                            $recent_lessons[] = $lesson;
                        }
                    }
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Recent lessons',
                        'data' => $recent_lessons
                    ], 200);
                    break;
                case 'detail':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                    ]);
                    ///show lesson detail by lesson id and record user log
                    $lesson = Lesson::findOrFail($request->lesson_id);
                    // check user can view lesson (user is owner of lesson or lesson is public and active)
                    if ($lesson->user_id != $request->user()->id && ($lesson->status == 0 || $lesson->is_public == 0)) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You do not have permission to access this lesson'
                        ], 403);
                    }
                    $lessonDetail = $lesson->lessonDetail()->get();
                    //check user log is exist, if exist update accessed_at, else create new user log
                    if (UserLog::where('user_id', $request->user()->id)->where('lesson_id', $request->lesson_id)->exists()) {
                        $user_log = UserLog::where('user_id', $request->user()->id)->where('lesson_id', $request->lesson_id)->first();
                        $user_log->accessed_at = now();
                        $user_log->save();
                    } else {
                        $user_log = new UserLog();
                        $user_log->user_id = $request->user()->id;
                        $user_log->lesson_id = $request->lesson_id;
                        $user_log->accessed_at = now();
                        $user_log->save();
                    }
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Get lesson successfully!',
                        'data' => [
                            'lesson' => $lesson,
                            'detail' => $lessonDetail
                        ]
                    ], 200);
                    break;
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Type is not valid',
                    ], 400);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'string|in:username,password',
            ]);
            switch ($request->type) {
                case 'username':
                    $request->validate([
                        'username' => 'required|string|unique:users',
                    ]);
                    //update username of user
                    $user = $request->user();
                    $user->username = $request->username;
                    $user->save();
                    return response()->json([
                        'user' => $user,
                        'message' => 'Update username of user successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'password';
                    $request->validate([
                        'password' => 'required|string|min:6|confirmed',
                    ]);
                    //update password of user
                    $user = $request->user();
                    $user->password = $request->password;
                    $user->save();
                    return response()->json([
                        'user' => $user,
                        'message' => 'Update password of user successfully',
                        'status' => 200
                    ], 200);
                    break;

                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Type is not valid',
                    ], 400);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //change status of user to inactive
            $user = User::findOrFail($id);
            $user->status = 0;
            $user->save();
            return response()->json([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Delete user successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'status_code' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
