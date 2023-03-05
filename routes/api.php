<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Authentication as Authentication;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LessonController as AdminSetController;
use App\Http\Controllers\Admin\CourseController as AdminFolderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoleController as AdminRoleController;
use App\Http\Controllers\Admin\SchoolController as AdminSchoolController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Public\LessonController;
use App\Http\Controllers\Public\CourseController;
use App\Http\Controllers\Public\ClassController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Route for authentication.
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [Authentication::class, 'login']);
    Route::post('register', [Authentication::class, 'register']);
    Route::post('forgot-password', [Authentication::class, 'verify']);
    Route::prefix('logout')->middleware('auth:sanctum')->group(function () {
        Route::post('current', [Authentication::class, 'logout']);
        Route::post('all', [Authentication::class, 'logoutAll']);
    });
    Route::prefix('email')->group(function () {
        Route::get('verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
        Route::post('handle-verify/{id}/{hash}', [VerificationController::class, 'handleVerifyEmail'])->middleware(['auth:sanctum']);
        Route::post('resend', [VerificationController::class, 'resendVerifyEmail'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
    });
    Route::post('forgot-password', [Authentication::class, 'forgotPassword']);
    Route::post('reset-password', [Authentication::class, 'resetPassword']);
});
/**
 * Route to get meta data.
 */
Route::get('/meta', [SettingController::class, 'meta']);

/**
 * Route for authenticated user.
 */
Route::middleware('auth:sanctum')->group(function () {
    // Route for admin
    Route::prefix('admin')->group(function () {
        Route::prefix('analytics')->group(function () {
            Route::get('google', [AnalyticsController::class, 'getAnalytics']);
        });
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->middleware(['permissions:admin.settings']);
            Route::post('/{key}', [SettingController::class, 'update'])->middleware(['permissions:admin.settings.update']);
        });
        Route::resource('set', AdminSetController::class)->middleware(['permissions:admin.lessons']);
        Route::resource('folder', AdminFolderController::class)->middleware(['permissions:admin.folders']);
        Route::resource('user', AdminUserController::class)->middleware(['permissions:admin.users']);
        Route::resource('role', AdminRoleController::class)->middleware(['permissions:admin.roles']);
        Route::resource('school', AdminSchoolController::class)->middleware(['permissions:admin.schools']);
    })->middleware(['permissions:admin.dashboard']);
    // Route set
    Route::prefix('set')->group(function () {
        Route::post('/', [LessonController::class, 'store']);
        Route::get('/{id}', [LessonController::class, 'show']);
        Route::put('/{id}', [LessonController::class, 'update']);
        Route::delete('/{id}', [LessonController::class, 'destroy']);
        Route::post('/import', [LessonController::class, 'import']);
        Route::get('/export/{id}', [LessonController::class, 'export']);
        Route::get('user/{id}', [LessonController::class, 'showAllSetByUserId']);
        Route::get('folder/{id}', [LessonController::class, 'showAllSetByFolderId']);
        Route::get('progress/{id}', [LessonController::class, 'showProgressBySetId']);
    });
    // Route folder
    Route::prefix('folder')->group(function () {
        Route::post('/', [CourseController::class, 'store']);
        Route::get('/{id}', [CourseController::class, 'show']);
        Route::put('/{id}', [CourseController::class, 'update']);
        Route::delete('/{id}', [CourseController::class, 'destroy']);
        Route::get('/{id}/set', [LessonController::class, 'showAllSetByFolderId']);
        Route::post('/add/{folder_id}/{set_id}', [CourseController::class, 'addSetToFolder']);
        Route::delete('/remove/{folder_id}/{set_id}', [CourseController::class, 'removeSetFromFolder']);
    });
    // Route user
    Route::prefix('user')->group(function () {
        Route::get('folder', [CourseController::class, 'showAllFolderByUserId']);
        Route::get('set', [LessonController::class, 'showAllSetByUserId']);
    });
    Route::prefix('class')->group(function () {
        Route::middleware(['checkUserInClass:all'])->group(function () {
            Route::post('/addFolder/{id}', [ClassController::class, 'addFolder']);
            Route::get('/{id}', [ClassController::class, 'show']);
        });
        Route::middleware(['checkUserInClass:teacher'])->group(function () {
            Route::post('/addTeacher/{id}', [ClassController::class, 'addTeacher']);
            Route::post('/addStudent/{id}', [ClassController::class, 'addStudent']);
            Route::post('/addSet/{id}', [ClassController::class, 'addSet']);
            Route::delete('/{id}/teacher/{teacher_id}', [ClassController::class, 'deleteTeacher']);
            Route::delete('/{id}/student/{student_id}', [ClassController::class, 'deleteStudent']);
            Route::delete('/{id}/set/{set_id}', [ClassController::class, 'deleteSet']);
            Route::delete('/{id}/folder/{folder_id}', [ClassController::class, 'deleteFolder']);
            Route::put('/{id}', [ClassController::class, 'update']);
            Route::delete('/{id}', [ClassController::class, 'destroy']);
        });
        Route::post('/', [ClassController::class, 'store']);
        Route::get('/', [ClassController::class, 'index']);
    });
});
