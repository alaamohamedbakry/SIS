<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;

class AuthRegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function student_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Alaa Mohamed',
            'email' => 'alaa@student.com',
            'password' => 'password123',
            'role' => 'student',
            'phone_number' => '01012345678',
            'date_of_birth' => '2002-05-10',
            'address' => 'Cairo',
            'major' => 'Computer Science',
            'year_of_study' => 3
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 'registered successfully'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'alaa@student.com',
            'role' => 'student'
        ]);

        $this->assertDatabaseHas('students', [
            'major' => 'Computer Science'
        ]);
    }

    /** @test */
    public function instructor_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Dr Ahmed',
            'email' => 'ahmed@instructor.com',
            'password' => 'password123',
            'role' => 'instructor',
            'specialization' => 'Software Engineering'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'ahmed@instructor.com',
            'role' => 'instructor'
        ]);

        $this->assertDatabaseHas('teachers', [
            'specialization' => 'Software Engineering'
        ]);
    }

    /** @test */
    public function student_can_login()
    {
        $user = User::factory()->create([
            'email' => 'alaa@student.com',
            'password' => bcrypt('password123'),
            'role' => 'student'
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'alaa@student.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'token',
                     'role',
                     'user'
                 ]);
    }
}
