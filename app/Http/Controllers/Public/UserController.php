<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Course;
use App\Models\Learn;
use App\Models\LessonDetail;
use App\Models\School;
use App\Models\UserLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use PhpParser\Builder\Class_;

use function PHPSTORM_META\type;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            //get info of auth user
            $user = auth()->user();

            $lessons = Lesson::where('user_id', auth()->user()->id)->where('status', 'active')->paginate(6);
            $lessons = $lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'description' => $lesson->description,
                    'author' => $lesson->user->name,
                    'number_of_detail' => $lesson->details->count(),
                ];
            });

            $courses = $user->courses->where('status', 'active')->take(6);
            $courses = $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                    'author' => $course->user->name,
                    'number_of_lesson' => $course->lessons->count(),
                ];
            });

            $other_lesson = Lesson::where('user_id', '!=', $user->id)
                ->where('status', 'active')
                ->where('password', null)
                ->where('school_id', null)
                ->where('class_id', null)
                ->inRandomOrder()
                ->take(6)
                ->get();
            $other_lesson = $other_lesson->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'description' => $lesson->description,
                    'author' => $lesson->user->name,
                    'number_of_detail' => $lesson->details->count(),
                ];
            });

            $other_course = Course::where('user_id', '!=', $user->id)
                ->where('status', 'active')
                ->where('password', null)
                ->where('school_id', null)
                ->where('class_id', null)
                ->inRandomOrder()
                ->take(6)
                ->get();
            $other_course = $other_course->map(function ($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                    'author' => $course->user->name,
                    'number_of_lesson' => $course->lessons->count(),
                ];
            });
            //get all school of user
            if ($user->role->name == 'student' || $user->role->name == 'teacher') {
                //get school_id of user
                $school_id = $user->school_id;
                //check user belong to class by relationship member in class model
                $schools = School::where('id', $school_id)->get();
                $classes = Classes::select('classes.*')
                    ->join('class_members', 'classes.id', '=', 'class_members.class_id')
                    ->where('class_members.user_id', $user->id)
                    ->get();
                //join table class_members and classes to get all member of class by class_id
                $class_id = $classes->pluck('id');
                $member = Classes::select('classes.*', 'class_members.user_id')
                    ->join('class_members', 'classes.id', '=', 'class_members.class_id')
                    ->whereIn('class_members.class_id', $class_id)
                    ->get();

                $count = 0;
                //for each member of class count +1
                foreach ($member as $key => $value) {
                    $count++;
                }
                //get all member of class
                $member = $member->map(function ($user_mem) {
                    return [
                        'id' => $user_mem->user_id,
                        'name' => User::find($user_mem->user_id)->name,
                        'role' => User::find($user_mem->user_id)->role->name,
                    ];
                });
            } else {
                $schools = null;
                $classes = null;
                $member = null;
                $count = null;
            }
            return response()->json([
                // get random 6 lessons
                'schools' => $schools,
                'classes' => $classes,
                'member' => $member,
                'number_of_member' => $count,
                'lessons' => $lessons,
                'courses' => $courses,
                'other_lesson' => $other_lesson,
                'other_course' => $other_course
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 400);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'string|in:info,lesson,course,search,detail',
            ]);
            // $user = User::findOrFail($id);
            // check user is user login
            if (!$request->user()) {
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
                    if ($user->status != 'active') {
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
                    //get pagination for lesson
                    $lessons = Lesson::where('user_id', $id)->where('status', 'active')->where('password', null)->paginate(6);
                    //check page is exist
                    if ($request->page) {
                        $page = $request->page;
                    }

                    $lessons = $lessons->map(function ($lessons) {
                        return [
                            'id' => $lessons->id,
                            'name' => $lessons->name,
                            'detail_count' => count($lessons->details),
                            'username' => $lessons->user->username,
                        ];
                    });

                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'data' => $lessons
                    ], 200);
                    break;

                case 'course':
                    //get pagination for course
                    $courses = Course::where('user_id', $id)->where('status', 'active')->where('password', null)->paginate(6);

                    $courses = $courses->map(function ($courses) {
                        return [
                            'id' => $courses->id,
                            'name' => $courses->name,
                            'lesson_count' => count($courses->lessons),
                            'username' => $courses->user->username,
                        ];
                    });
                    return response()->json([
                        'status' => 'success',
                        'status_code' => 200,
                        'message' => 'Get all course by user id successfully',
                        'data' => $courses
                    ], 200);
                    break;
                case 'search': //search lesson, course, school by name of lesson
                    $request->validate([
                        'name' => 'required|string|max:255',
                    ]);
                    $name = $request->name;
                    if ($name == '') {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 404,
                            'message' => 'Not found'
                        ], 404);
                    }

                    //check if lesson, course, school name is exist
                    $lessons = Lesson::where('name', 'like', '%' . $name . '%')->get();
                    //get only active and null password lesson
                    $lessons = $lessons->filter(function ($lesson) {
                        return $lesson->status == 'active' && $lesson->password == null;
                    });
                    $courses = Course::where('name', 'like', '%' . $name . '%')->get();
                    //get only active and null password course
                    $courses = $courses->filter(function ($course) {
                        return $course->status == 'active' && $course->password == null;
                    });
                    $schools = School::where('name', 'like', '%' . $name . '%')->get();
                    //get only active and null password school
                    $schools = $schools->filter(function ($school) {
                        return $school->status == 'active';
                    });
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

                case 'detail':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                    ]);
                    ///show lesson detail by lesson id and record user log
                    $lesson = Lesson::findOrFail($request->lesson_id);
                    // check user can view lesson (user is owner of lesson or lesson is public and active)
                    if ($lesson->user_id != auth()->user()->id && ($lesson->status != 'active' || $lesson->password != null)) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You do not have permission to access this lesson'
                        ], 403);
                    }

                    $lessonDetail = $lesson->details()->get();
                    //map to get only name and content of lesson detail
                    $lessonDetail = $lessonDetail->map(function ($lessonDetail) {
                        return [
                            'id' => $lessonDetail->id,
                            'term' => $lessonDetail->term,
                            'definition' => $lessonDetail->definition,
                        ];
                    });
                    //check user log is exist, if exist update accessed_at, else create new user log
                    if (UserLog::where('user_id', auth()->user()->id)->where('lesson_id', $request->lesson_id)->exists()) {
                        $user_log = UserLog::where('user_id', $request->user()->id)->where('lesson_id', $request->lesson_id)->first();
                        $user_log->accessed_at = now();
                        $user_log->save();
                    } else {
                        $user_log = new UserLog();
                        $user_log->user_id = auth()->user()->id;
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
                            'lessonDetail' => $lessonDetail,
                        ]
                    ], 200);
                    break;
                    // case 'learn':
                    //     $request->validate([
                    //         'lesson_id' => 'required|integer',
                    //     ]);
                    //     $lesson_id = $request->lesson_id;
                    //     //call to learn method from LessonController
                    //     $lessonController = new LessonController();
                    //     return $lessonController->learn($request, $lesson_id);
                    //     break;
                    // case 'test':
                    //     //same with learn but get from method learnForImport
                    //     $request->validate([
                    //         'lesson_id' => 'required|integer',
                    //         'quantity' => 'nullable|integer',
                    //     ]);
                    //     $lesson_id = $request->lesson_id;
                    //     $lessonController = new LessonController();
                    //     $quantity = $request->quantity ? $request->quantity : 10;
                    //     $data = $lessonController->learnForImport($request, $lesson_id)->getData();
                    //     return response()->json([
                    //         'status' => 'success',
                    //         'status_code' => 200,
                    //         'message' => 'Get lesson successfully!',
                    //         'data' => $data
                    //     ], 200);
                    //     break;
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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'string|in:learn,test,recent,detail_split',
            ]);
            //check if user is not login
            if (!$request->user()) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 401,
                    'message' => 'You are not logged in'
                ], 401);
            }
            switch ($request->type) {
                case 'learn':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                    ]);
                    $lesson_id = $request->lesson_id;
                    //call to learn method from LessonController
                    $lessonController = new LessonController();
                    return $lessonController->learn($request, $lesson_id);
                    break;
                case 'test':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                    ]);
                    $lesson_id = $request->lesson_id;
                    $lessonController = new LessonController();
                    return $lessonController->learnForImport($request, $lesson_id);
                    // return response()->json([
                    //     'status' => 'success',
                    //     'status_code' => 200,
                    //     'message' => 'Get lesson successfully!',
                    //     'data' => $data
                    // ], 200);
                    break;
                case 'recent':
                    $user_log = UserLog::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();
                    //if dont have any lesson log
                    if (count($user_log) == 0) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 404,
                            'message' => 'You have not learn any lesson yet!'
                        ], 404);
                    }
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
                case 'detail_split':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                    ]);
                    ///show lesson detail by lesson id and record user log
                    $lesson = Lesson::findOrFail($request->lesson_id);
                    // check auth user can view lesson (user is owner of lesson or lesson is public and active)
                    if ($lesson->user_id != auth()->user()->id && ($lesson->status != 'active' || $lesson->password != null)) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You do not have permission to access this lesson'
                        ], 403);
                    }

                    //get lesson id from lesson
                    $lesson_id = $lesson->id;
                    //get learned lesson details
                    $learn = Learn::where('user_id', auth()->user()->id)->whereIn('lesson_id', [$lesson_id])->first();
                    //get id of learned lesson details
                    $learned = $learn ? explode(',', $learn->learned) : [];
                    //get id of relearn lesson details
                    $relearn = $learn ? explode(',', $learn->relearn) : [];
                    // $lessonDetails = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn);
                    $lessonDetails = [];
                    //get relearn lesson details
                    if ($relearn) {
                        $lessonDetails = LessonDetail::whereIn('id', $relearn)->get();
                        $lessonDetails = $lessonDetails->map(function ($lessonDetails) {
                            return [
                                'id' => $lessonDetails->id,
                                'term' => $lessonDetails->term,
                                'definition' => $lessonDetails->definition,
                            ];
                        });
                    }
                    $notLearn = LessonDetail::where('lesson_id', $lesson_id)->whereNotIn('id', $learned)->whereNotIn('id', $relearn)->get();
                    //map to get only name and content of not learned lesson detail
                    $notLearn = $notLearn->map(function ($notLearn) {
                        return [
                            'id' => $notLearn->id,
                            'term' => $notLearn->term,
                            'definition' => $notLearn->definition,
                        ];
                    });
                    $learned = LessonDetail::where('lesson_id', $lesson_id)->whereIn('id', $learned)->get();
                    $lessonDetail = $lesson->details()->get();
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
                            'learned' => $learned,
                            'relearn' => $lessonDetails,
                            'notLearn' => $notLearn,
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                'type' => 'string|in:username,password,learned,info,done',
            ]);
            switch ($request->type) {
                case 'username':
                    $request->validate([
                        'username' => 'required|string|unique:users',
                    ]);
                    //check auth user is owner user id
                    if (auth()->user()->id != $id) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You do not have permission to update this user'
                        ], 403);
                    }
                    //update username of user
                    $user = auth()->user();
                    $user->username = $request->username;
                    $user->save();
                    return response()->json([
                        'user' => $user,
                        'message' => 'Update username of user successfully',
                        'status' => 200
                    ], 200);
                    break;

                case 'password':
                    $request->validate([
                        'old_password' => 'required|string|min:6',
                        'password' => 'required|string|min:6|confirmed',
                    ]);
                    //check auth user is owner user id
                    if (auth()->user()->id != $id) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You do not have permission to update this user'
                        ], 403);
                    }
                    //check old password is correct
                    if (!Hash::check($request->old_password, auth()->user()->password)) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'Old password is incorrect'
                        ], 403);
                    }
                    //check if old password is same new password
                    if (Hash::check($request->password, auth()->user()->password)) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'New password is same old password'
                        ], 403);
                    }
                    //update password of user
                    $user = auth()->user();
                    $user->password = bcrypt($request->password);
                    $user->save();
                    return response()->json([
                        'user' => $user,
                        'message' => 'Update password of user successfully',
                        'status' => 200
                    ], 200);
                    break;
                case 'learned':
                    $request->validate([
                        'lesson_id' => 'required|integer',
                        'learned' => 'nullable|array',
                        'relearn' => 'nullable|array',
                    ]);
                    //update learned and relearn of user
                    $user = $request->user();
                    $learn = Learn::where('user_id', auth()->user()->id)->where('lesson_id', $request->lesson_id)->first();
                    if ($learn) {
                        if ($request->learned == null && $request->relearn == null) {   //done learn lesson
                            //return congratulation message
                            return response()->json([
                                'status' => 'success',
                                'status_code' => 200,
                                'message' => 'Congratulation! You have done this lesson!',
                            ], 200);
                        } else if ($request->learned == null && $request->relearn != null) { //update only relearn of user
                            //get id of relearn lesson details
                            $relearn = $learn ? explode(',', $learn->relearn) : [];
                            //add new relearn lesson detail to relearn
                            $relearn = array_merge($relearn, $request->relearn);
                            //remove duplicate relearn
                            $relearn = array_unique($relearn);
                            //convert array to string
                            $relearn = implode(',', $relearn);
                            //update relearn of user
                            $learn->relearn = $relearn;
                            $learn->save();
                        } else if ($request->learned != null && $request->relearn == null) { //update only learned of user
                            //get id of learned lesson details
                            $learned = $learn ? explode(',', $learn->learned) : [];
                            $relearn = $learn ? explode(',', $learn->relearn) : [];
                            //add new learned lesson detail to learned
                            $learned = array_merge($learned, $request->learned);
                            $relearn = array_diff($relearn, $request->learned);
                            //remove duplicate learned
                            $learned = array_unique($learned);
                            //convert array to string
                            $learned = implode(',', $learned);
                            //update learned of user
                            $learn->learned = $learned;
                            $learn->relearn = $relearn;
                            $learn->save();
                        } else {
                            //update learned and relearn of user
                            //get id of learned lesson details
                            $learned = $learn ? explode(',', $learn->learned) : [];
                            //get id of relearn lesson details
                            $relearn = $learn ? explode(',', $learn->relearn) : [];
                            //add new learned lesson detail to learned
                            $learned = array_merge($learned, $request->learned);
                            //remove learned lesson detail from relearn
                            $relearn = array_diff($relearn, $request->learned);
                            //add new relearn lesson detail to relearn
                            $relearn = array_merge($relearn, $request->relearn);
                            //remove duplicate learned and relearn
                            $learned = array_unique($learned);
                            $relearn = array_unique($relearn);
                            //convert array to string
                            $learned = implode(',', $learned);
                            $relearn = implode(',', $relearn);
                            //update learned and relearn of user
                            $learn->learned = $learned;
                            $learn->relearn = $relearn;
                            $learn->save();
                        }
                    } else {
                        //remove duplicate learned and relearn
                        $learned_arr = array_unique($request->learned);
                        $relearn_arr = array_unique($request->relearn);
                        //convert array to string
                        $learned_str = implode(',', $learned_arr);
                        $relearn_str = implode(',', $relearn_arr);
                        $learn = new Learn();
                        $learn->user_id = auth()->user()->id;
                        $learn->lesson_id = $request->lesson_id;
                        $learn->learned = $learned_str;
                        $learn->relearn = $relearn_str;

                        $learn->save();
                    }
                    //count learned from learn
                    return response()->json([
                        'progress' => [
                            'learned' => count(explode(',', $learn->learned)),
                            'total' => LessonDetail::where('lesson_id', $request->lesson_id)->count(),
                        ],
                        'message' => 'Update learn progress successfully',
                        'status' => 200
                    ], 200);
                    break;
                case 'info':
                    $request->validate([
                        'name' => 'nullable|string',
                        //'email' => 'required|string|email|unique:users',
                        'date_of_birth' => 'nullable|date',
                    ]);
                    //check auth user is owner user id
                    if ($request->user()->id != $id) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You do not have permission to update this user'
                        ], 403);
                    }
                    //update info of user
                    $user = auth()->user();
                    $user->name = $request->name ? $request->name : $user->name;
                    //$user->email = $request->email ? $request->email : $user->email;
                    //date of birth have to more than 6 years old
                    if ($request->date_of_birth) {
                        $date_of_birth = Carbon::parse($request->date_of_birth);
                        $now = Carbon::now();
                        $diff = $now->diffInYears($date_of_birth);
                        if ($diff < 6) {
                            return response()->json([
                                'status' => 'error',
                                'status_code' => 400,
                                'message' => 'Date of birth have to more than 6 years old'
                            ], 400);
                        }
                    }
                    $user->date_of_birth = $request->date_of_birth ? $request->date_of_birth : $user->date_of_birth;
                    $user->save();
                    return response()->json([
                        'user' => $user,
                        'message' => 'Update info of user successfully',
                        'status' => 200
                    ], 200);
                    break;
                case 'done':
                    //request validate choice
                    $request->validate([
                        'choice' => 'nullable|in:relearnall',
                        'lesson_id' => 'required|integer',
                    ]);
                    //check auth user is owner user id
                    if ($request->user()->id != $id) {
                        return response()->json([
                            'status' => 'error',
                            'status_code' => 403,
                            'message' => 'You do not have permission to update this user'
                        ], 403);
                    }
                    switch ($request->choice) {
                        case 'relearnall':
                            //delete learn of user
                            $learn = Learn::where('user_id', auth()->user()->id)->where('lesson_id', $request->lesson_id)->first();
                            if ($learn) {
                                $learn->delete();
                            }
                            return response()->json([
                                'message' => 'Relearn all lesson detail successfully',
                                'status' => 200
                            ], 200);
                            break;
                        default:
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Nothing to do',
                            ], 400);
                            break;
                    }
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //change status of auth user to inactive
            $user = User::find($id);
            //auth user is owner user id
            if ($user->id != auth()->user()->id) {
                return response()->json([
                    'status' => 'error',
                    'status_code' => 403,
                    'message' => 'You do not have permission to delete this user'
                ], 403);
            }
            if ($user->status == 'inactive') {
                //delete user
                $user->delete();
                return response()->json([
                    'message' => 'Thank you for using our service. We hope to see you again soon.',
                    'status' => 200
                ], 200);
            } else {
                $user->status = 'inactive';
                $user->save();
                return response()->json([
                    'message' => 'Update status of user successfully',
                    'status' => 200
                ], 200);
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
