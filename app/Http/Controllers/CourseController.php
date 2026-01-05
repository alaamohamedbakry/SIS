<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseCollection;
use App\Models\Course;
use App\services\CourseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct(
        protected CourseService $courseservice
    )
    {}
    public function index()
    {
        return new CourseCollection($this->courseservice->all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data = $request->validate([
            'course_name' => 'required',
            'course_code' => 'required',
            'course_hours' => 'required',
        ]);

       $course = $this->courseservice->CreateForInstructor(auth()->user(),
       $data);
        return response()->json([
            'msg'=>'course created successfully',
            'course'=>$course
        ],201);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
       $data = $request->validate([
            'course_name' => 'required|string',
            'course_code' => 'required|string',
            'course_hours' => 'required|integer',
        ]);

       $course = $this->courseservice->UpdateCourse(auth()->user(),$id , $data);
        return response()->json([
            'msg'=>'course updated successfully',
            'course'=>$course
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $this->courseservice->DeleteCourse(auth()->user(),$id);
        return response()->json([
            'msg'=>'course deleted successfully',
        ],200);
    }
}
