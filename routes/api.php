<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);




Route::middleware(['auth:sanctum', 'role:student'])->group(function () {


    Route::post('/enrollments', [EnrollmentController::class, 'store']);
    Route::put('/enrollments/{id}',[EnrollmentController::class,'update']);
    Route::delete('/enrollments/{course}', [EnrollmentController::class, 'destroy']);

    Route::get('/my-courses', [EnrollmentController::class, 'myCourses']);
    Route::get('/my-grades', [GradeController::class, 'myGrades']);
});


Route::middleware(['auth:sanctum', 'role:instructor'])->group(function () {


    Route::apiResource('courses', CourseController::class);

    Route::post('/grades', [GradeController::class, 'store']);   // add grades
    Route::get('/courses/{course}/students', [EnrollmentController::class, 'students']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {

    Route::apiResource('students', StudentController::class);
    Route::apiResource('teachers', TeacherController::class);
});
