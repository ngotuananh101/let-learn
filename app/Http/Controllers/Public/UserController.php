<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Course;

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
                'type' => 'string|in:info,lesson,course',
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
