<?php

use App\Http\Controllers\Api\v1\Admin\UserController;
use App\Http\Controllers\Api\v1\Auth\AdminAuthController;
use App\Http\Controllers\Api\v1\Auth\UserAuthController;
use App\Http\Controllers\Api\v1\Admin\ClassroomController;
use App\Http\Controllers\Api\v1\Admin\CourseController;
use App\Http\Controllers\Api\v1\Admin\PaymentController;
use App\Http\Controllers\Api\v1\Admin\AttendanceController;

use App\Models\User;
use Illuminate\Support\Facades\Route;

/*///////////////////////////////////////////
*
*           PUBLIC API
*
*/ //////////////////////////////////////////

Route::post('/register', [AdminAuthController::class, 'register']);
Route::post('/login', [AdminAuthController::class, 'login']);


/*///////////////////////////////////////////
*
*           PRIVATE API
*
*/ //////////////////////////////////////////
Route::group(['middleware' => 'auth:api', 'prefix' => 'auth/v1'], function ($router) {
    Route::post('/refresh-token',[AdminAuthController::class, 'refreshToken']);
    Route::post('/logout',[AdminAuthController::class, 'logout']);

    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'updateUser']);
    Route::patch('/users/{id}/deactivate', [UserController::class, 'deactivateUser']);

    
    // classroom
    Route::post('/classrooms',[ClassroomController::class, 'store']);
    Route::get('/classrooms',[ClassroomController::class, 'getAllClassrooms']);
    Route::get('/classrooms/{id}',[ClassroomController::class, 'getClassrooms']);
    Route::put('/classrooms/{id}',[ClassroomController::class, 'updateClassroom']);

    // course
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses', [CourseController::class, 'getAllCourses']);
    Route::get('/courses/{id}', [CourseController::class, 'getCourse']);
    Route::put('/courses/{id}', [CourseController::class, 'updateCourse']);
    Route::patch('/courses/{id}/deactivate', [CourseController::class, 'deactivateCourse']);

    // payment
    
    Route::get('/payments', [PaymentController::class, 'getAllPayments']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'getPayment']);
    Route::put('/payments/{id}', [PaymentController::class, 'updatePayment']);
    Route::patch('/payments/{id}/verify', [PaymentController::class, 'verifyPayment']);

    // attendance

    Route::get('/attendances', [AttendanceController::class, 'getAllAttendances']);
    Route::post('/attendances', [AttendanceController::class, 'store']);
    Route::get('/attendances/{id}', [AttendanceController::class, 'getAttendance']);
    Route::put('/attendances/{id}', [AttendanceController::class, 'updateAttendance']);
    Route::patch('/attendances/{id}/verify', [AttendanceController::class, 'verifyAttendance']);


});
