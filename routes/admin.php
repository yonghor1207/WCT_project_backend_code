<?php

use App\Http\Controllers\Api\v1\Admin\UserController;
use App\Http\Controllers\Api\v1\Admin\ClassroomController;
use App\Http\Controllers\Api\v1\Admin\CourseController;
use App\Http\Controllers\Api\v1\Admin\PaymentController;
use App\Http\Controllers\Api\v1\Admin\AttendanceController;
use App\Http\Controllers\Api\v1\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*///////////////////////////////////////////
*
*           ADMIN PROTECTED ROUTES
*           Endpoint: /api/admin/*
*           Middleware: auth:api
*
*/ //////////////////////////////////////////

Route::group(['middleware' => 'auth:api'], function ($router) {
    
    // Dashboard Stats
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/charts', [DashboardController::class, 'getChartData']);
    
    // User Management
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'updateUser']);
    Route::patch('/users/{id}/deactivate', [UserController::class, 'deactivateUser']);
    Route::patch('/users/{id}/approve', [UserController::class, 'approveUser']); // For teacher approval
    Route::patch('/users/{id}/payment-status', [UserController::class, 'updatePaymentStatus']); // Update payment status
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete user

    // Classroom Management
    Route::post('/classrooms', [ClassroomController::class, 'store']);
    Route::get('/classrooms', [ClassroomController::class, 'getAllClassrooms']);
    Route::get('/classrooms/{id}', [ClassroomController::class, 'getClassrooms']);
    Route::put('/classrooms/{id}', [ClassroomController::class, 'updateClassroom']);
    Route::delete('/classrooms/{id}', [ClassroomController::class, 'destroy']); // Delete classroom

    // Course Management
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses', [CourseController::class, 'getAllCourses']);
    Route::get('/courses/{id}', [CourseController::class, 'getCourse']);
    Route::put('/courses/{id}', [CourseController::class, 'updateCourse']);
    Route::patch('/courses/{id}/deactivate', [CourseController::class, 'deactivateCourse']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    // Payment Management
    Route::get('/payments', [PaymentController::class, 'getAllPayments']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'getPayment']);
    Route::put('/payments/{id}', [PaymentController::class, 'updatePayment']);
    Route::patch('/payments/{id}/verify', [PaymentController::class, 'verifyPayment']);

    // Attendance Management
    Route::get('/attendances', [AttendanceController::class, 'getAllAttendances']);
    Route::post('/attendances', [AttendanceController::class, 'store']);
    Route::get('/attendances/{id}', [AttendanceController::class, 'getAttendance']);
    Route::put('/attendances/{id}', [AttendanceController::class, 'updateAttendance']);
    Route::patch('/attendances/{id}/verify', [AttendanceController::class, 'verifyAttendance']);
    Route::delete('/attendances/{id}', [AttendanceController::class, 'destroy']);
});

