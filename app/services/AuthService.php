<?php


namespace App\services;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data)
    {

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role' => $data['role']
            ]);

            if ($data['role'] === 'student') {
                Student::create([
                    'user_id' => $user->id,
                    'student_code' => 'STD-' . rand(1000, 9999),
                    'phone_number' => $data['phone_number'],
                    'date_of_birth' => $data['date_of_birth'],
                    'address' => $data['address'],
                    'major' => $data['major'],
                    'year_of_study' => $data['year_of_study']
                ]);
            }

            if ($data['role'] === 'instructor') {
                Teacher::create([
                    'user_id' => $user->id,
                    'specialization' => $data['specialization']
                ]);
            }

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new Exception('Invalid credentials');
        }

        // delete old tokens (optional)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token
        ];
    }
}
