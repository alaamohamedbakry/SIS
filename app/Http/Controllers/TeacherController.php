<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
       $request->validate([
        'email'=>'required|email',
        'password'=>'required|string|min:8'
       ]);
       try{
        $teacher = User::firstwhere('email',$request->email);
        if($teacher && Hash::check($request->password , $teacher->password)){
            return response()->json([
                'status'=>'teacher logined successfully',
                'token'=> $teacher->createToken('user')->plainTextToken
            ]);
        }else{
            return response()->json([
                'status'=>'teacher logined failed',
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
    }
}
