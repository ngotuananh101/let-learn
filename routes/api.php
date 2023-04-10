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
});
