<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseCollection;
use App\Models\Course;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        return new CourseCollection($courses);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required',
            'course_code' => 'required',
            'course_hours' => 'required',
        ]);

        try {
            $user = Auth::user();
            if ($user->role != 'instructor') {
                return response()->json([
                    'status' => 'failed',
                    'msg' => 'only instructors can create courses'
                ], 403);



                if (!$teacher) {
                    return response()->json([
                        'message' => 'instructor profile not found'
                    ], 404);
                }
            }
            $teacher = $user->teacher;
            $course = Course::create([
                'course_name' => $request->course_name,
                'course_code' => $request->course_code,
                'course_hours' => $request->course_hours,
                'teacher_id'   => $teacher->id,
            ]);
            return response()->json([
                'status' => 'course created successfully',
                'course' => $course
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'course created failed',
                $e->getMessage()
            ], 401);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $request->validate([
            'course_name' => 'required|string',
            'course_code' => 'required|string',
            'course_hours' => 'required|integer',
        ]);

        try {

             $user = Auth::user();
            if ($user->role != 'instructor') {
                return response()->json([
                    'status' => 'failed',
                    'msg' => 'only instructors can create courses'
                ], 403);



                if (!$teacher) {
                    return response()->json([
                        'message' => 'instructor profile not found'
                    ], 404);
                }
            }
            $teacher = $user->teacher;


            $course = Course::findOrFail($id);
            if($course->teacher_id !== $teacher->id){
                return response()->json([
                    'status'=>'failed',
                    'msg'=>'you can only update yor own courses'
                ],403);
            }
            $course->update([
                'course_name' => $request->course_name,
                'course_code' => $request->course_code,
                'course_hours' => $request->course_hours,
            ]);

                return response()->json([
                    'status' => 'course updated successfully',
                    'course' => $course
                ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'faild',
                $e->getMessage()
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            $course = Course::find($id);
            $course->delete();
            return response()->json([
                'status' => 'done'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                $e->getMessage()
            ], 401);
        }
    }
}
