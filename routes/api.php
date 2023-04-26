<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('guest')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', 'App\Http\Controllers\AuthController@login');
        Route::post('register', 'App\Http\Controllers\AuthController@register');
        Route::get('verify/{id}/{hash}', 'App\Http\Controllers\AuthController@verify')->name('verification.verify')->middleware('signed');
        Route::post('forgot-password', 'App\Http\Controllers\AuthController@forgotPassword');
        Route::get('reset-password', 'App\Http\Controllers\AuthController@resetPasswordView')->name('password.reset');
        Route::post('reset-password', 'App\Http\Controllers\AuthController@resetPassword');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', 'App\Http\Controllers\AuthController@logout');
    Route::group(['middleware' => ['permission:admin.dashboard'], 'prefix' => 'admin'], function () {
        Route::get('analytics', 'App\Http\Controllers\Admin\AnalyticsController@getAnalytics');
        Route::resource('setting', 'App\Http\Controllers\Admin\SettingController')->only(['index', 'update']);
        Route::resource('notification', 'App\Http\Controllers\Admin\NotificationController')->only(['index', 'store', 'destroy']);
        Route::resource('user', 'App\Http\Controllers\Admin\UserController')->except(['create', 'show', 'edit']);
        Route::resource('role', 'App\Http\Controllers\Admin\RoleController')->except(['create', 'edit']);
        Route::resource('school', 'App\Http\Controllers\Admin\SchoolController');
        Route::resource('class', 'App\Http\Controllers\Admin\ClassController');
        Route::resource('lesson', 'App\Http\Controllers\Admin\LessonController');
        Route::resource('course', 'App\Http\Controllers\Admin\CourseController');
    });
    Route::group(['middleware' => ['permission:manager.dashboard,admin.super'], 'prefix' => 'school'], function () {
        Route::get('dashboard', 'App\Http\Controllers\School\DashboardController@show');
        Route::resource('info', 'App\Http\Controllers\School\SchoolController')->only(['show', 'update']);
        Route::resource('users', 'App\Http\Controllers\School\UserController');
        Route::resource('lessons', 'App\Http\Controllers\School\LessonController');
        Route::resource('courses', 'App\Http\Controllers\School\CourseController');
        Route::resource('classes', 'App\Http\Controllers\School\ClassController');
    });
    Route::group(['prefix' => 'classes'], function (){
        Route::resource('{class_id}/posts', 'App\Http\Controllers\Classes\PostController');
        Route::resource('{class_id}/quizzes', 'App\Http\Controllers\Classes\QuizController');
        Route::get('{class_id}/members', 'App\Http\Controllers\Classes\MemberController@getMember');
    });
    Route::group(['prefix' => 'public'], function () {
        Route::get('home', 'App\Http\Controllers\Public\HomeController@index');
        Route::get('information', 'App\Http\Controllers\Public\UserController@index');
        Route::get('search', 'App\Http\Controllers\Public\SearchController@index');
    });
    Route::group(['prefix' => 'forum'], function () {
        Route::resource('post', 'App\Http\Controllers\Public\PostController');
        Route::resource('post/{post_id}/comment', 'App\Http\Controllers\Public\CommentController');
    });
    Route::group(['middleware' => ['permission:user.default'], 'prefix' => 'user'], function () {
        Route::resource('user', 'App\Http\Controllers\Public\UserController')->except(['index']);
        Route::resource('class', 'App\Http\Controllers\Public\ClassController');
        Route::resource('main', 'App\Http\Controllers\Public\UserController');
        Route::resource('lesson', 'App\Http\Controllers\Public\LessonController');
        Route::resource('course', 'App\Http\Controllers\Public\CourseController');
    });
    Route::group(['middleware' => ['permission:teacher.default'], 'prefix' => 'teacher'], function () {
        Route::resource('main', 'App\Http\Controllers\Public\UserController')->except(['index']);
        Route::resource('lesson', 'App\Http\Controllers\Public\LessonController');
        Route::resource('course', 'App\Http\Controllers\Public\CourseController');
        Route::resource('quiz', 'App\Http\Controllers\Public\QuizController');
    });
    Route::group(['middleware' => ['permission:student.default'], 'prefix' => 'student'], function () {
        Route::resource('main', 'App\Http\Controllers\Public\UserController')->except(['index']);
        Route::resource('lesson', 'App\Http\Controllers\Public\LessonController');
        Route::resource('course', 'App\Http\Controllers\Public\CourseController');
        Route::resource('quiz', 'App\Http\Controllers\Public\QuizController');
    });
});
