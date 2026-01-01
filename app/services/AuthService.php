<?php


namespace App\services;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function register(array $data){

        DB::beginTransaction();
        try{
            $user = User::create([
                'name' => $data['name'],
                'email'=>$data['email'],
                'password'=>bcrypt($data['password']),
                'role'=>$data['role']
            ]);

            if($data['role'] === 'student'){
                Student::create([
                    'user_id'=>$user->id,
                    'student_code'=> 'STD-'. rand(1000,9999),
                    'phone_number'=>$data['phone_number'],
                    'date_of_birth'=>$data['date_of_birth'],
                    'address'=>$data['address'],
                    'major'=>$data['major'],
                    'year_of_study'=>$data['year_of_study']
                ]);
            }

            if($data['role'] === 'instructor'){
                Teacher::create([
                    'user_id'=>$user->id,
                    'specialization'=>$data['specialization']
                ]);
            }

            DB::commit();
            return $user;

        }catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
}

