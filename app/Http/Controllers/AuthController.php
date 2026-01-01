<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(

        protected AuthService $authService
    ) {}

    public function register(Request $request){
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:8',
            'role'=>'required|in:student,instructor',
            'phone_number'=>'required_if:role,student|string|max:15',
            'date_of_birth'=>'required_if:role,student|date',
            'address'=>'required_if:role,student|string|max:255',
            'major'=>'required_if:role,student|string|max:100',
            'year_of_study'=>'required_if:role,student|integer|min:1|max:6',
            'specialization'=>'required_if:role,instructor|string|max:100'
        ]);

        $user = $this->authService->register($data);

        return response()->json([
            'status'=>'registered successfully',
            'user'=>$user
        ],201);
    }
    



}
