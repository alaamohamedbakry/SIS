<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentCollection;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return new StudentCollection($students);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|string|min:8'
        ]);
        try{
            $student=Student::firstwhere('email',$request->email);
            if($student && Hash::check($request->password,$student->password)){
                return response()->json([
                    'status'=>'student logined successfully',
                    'token'=>$student->createToken('student')->plainTextToken
                ]);
            }else{
                return response()->json([
                    'status'=>'student logined failed',
                    'msg'=>'password does not match'
                ]);
            }

        }catch(Exception $e){
            return response()->json([
                'status'=>'failed',
                $e->getMessage()
            ],401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $request->validate([
            "first_name" => 'required|string|max:255',
            "last_name" => 'required|string|max:255',
            "email" => 'required|email|unique:students,email',
            "password" => 'required|string|min:8',
            "student_id" => 'required',
            "phone_number" => 'required'
        ]);
        try {
            $student = Student::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'student_id' => $request->student_id,
                'phone_number' => $request->phone_number
            ]);
            $token = $student->createToken('student')->plainTextToken;
             return response()->json([
                'status'=>'student created successfully',
                'token'=>$token
            ],201);
        } catch (Exception $e) {
            return response()->json([
                'status'=>'failed',
                $e->getMessage()
            ],401);
        }
    }
    public function logout(Request $request){
     if($request->user()->currentAccessToken()->delete()){
        return response()->json([
            'msg'=>'student deleted successfully',
        ]);
     }
     return response()->json([
        'msg'=>'some thing went'
     ]);
    }
    public function show(String $id){
        try{
            $student= Student::findorfail($id);
          return response()->json([
            'msg'=>'there is your response',
            "student"=>$student
          ]);
        }catch(Exception $e){
            return response()->json([
                'msg'=>'there is some thing wrong',
                $e->getMessage()
              ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $request->validate([
            "first_name" => 'required|string|max:255',
            "last_name" => 'required|string|max:255',
            "email" => 'required|email',
            "password" => 'required|string|min:8',
            "student_id" => 'required',
            "phone_number" => 'required'
        ]);
        try {
            $student = Student::find($id);
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->email = $request->email;
            $student->password = $request->password;
            $student->student_id = $request->student_id;
            $student->phone_number = $request->phone_number;
            if ($student->save()) {
                return response()->json([
                    'status' => 'student updated successfully',
                    'student' => $student
                ], 200);
            }
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
        try{
            $student= Student::find($id);
            $student->delete();
            return response()->json([
                'status'=>'deleted successfully'
            ]);

        }catch(Exception $e){
            return response()->json([
                'status'=>'deleted failed',
                $e->getMessage()
            ],401);
        }
    }
}
