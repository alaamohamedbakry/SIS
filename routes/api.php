<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\StudentController;
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
Route::prefix('student')->group(function(){
Route::post('/register',[StudentController::class,'register']);
Route::post('/login',[StudentController::class,'login']);
Route::post('/logout',[StudentController::class,'logout'])->middleware('auth:sanctum');
});


Route::middleware('auth:sanctum')->group(function(){
Route::get('show/{id}',[StudentController::class,'show']);
Route::post('store',[EnrollmentController::class,'store']);
Route::get('/index',[StudentController::class,'index']);
Route::get('grades/{student_id}',[GradeController::class,'show']);
});
Route::prefix('course')->group(function(){
 Route::get('index',[CourseController::class,'index']);
 Route::post('/store',[CourseController::class,'store']);
 Route::post('update/{id}',[CourseController::class,'update']);
 Route::delete('delete/{id}',[CourseController::class,'destroy']);
});

Route::post('update/{id}',[StudentController::class,'update']);
Route::delete('delete/{id}',[StudentController::class,'destroy']);

Route::prefix('enrollment')->group(function(){
    Route::get('index',[EnrollmentController::class,'index']);
    Route::post('update/{id}',[EnrollmentController::class,'update']);
    Route::delete('delete/{id}',[EnrollmentController::class,'destroy']);



});
