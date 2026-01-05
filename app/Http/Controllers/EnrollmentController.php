<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Services\EnrollService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $enrollService;

    public function __construct(EnrollService $enrollService)
    {
        $this->enrollService = $enrollService;
    }

    /**
     * تسجيل الطالب في كورس
     */

    public function index()
    {
        $enrollment = Enrollment::all();
        return response()->json([
            'status' => 'done',
            'enrollment' => $enrollment
        ]);
    }

    public function show(Request $request) {}




    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $request->validate([
            'semster'    => 'required|string',
            'course_id'   => 'nullable|exists:courses,id|required_without:course_code',
            'course_code' => 'nullable|string|required_without:course_id'
        ]);

        try {
            $enrollment = $this->enrollService->enrollStudent(Auth::user(), $request->only('semster', 'course_id', 'course_code'));
            return response()->json([
                'message' => 'Enrollment successful',
                'enrollment' => $enrollment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'msg' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
{
    $data = $request->validate([
        'semster' => 'sometimes|string',
        'status'   => 'sometimes|in:enrolled,dropped,completed',
    ]);

    try {
        $enrollment = $this->enrollService->updateEnrollment(auth()->user(), $id, $data);

        return response()->json([
            'status' => 'done',
            'enrollment' => $enrollment
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'status' => 'failed',
            'msg' => $e->getMessage()
        ], $e->getCode() ?: 400);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            $user = Auth()->user();
            if ($user->role != 'student') {
                return response()->json([
                    'status' => 'failed',
                    'msg' => 'only students can enroll in courses'
                ], 403);
            }

            if (!$student = $user->student) {
                return response()->json([
                    'message' => 'student profile not found'
                ], 404);
            }

            $student = $user->student;
            $enrollment = Enrollment::findOrFail($id);
            if ($enrollment->student_id !== $student->id) {
                return response()->json([
                    'message' => 'You can only delete your own enrollment'
                ], 403);
            }
            $enrollment->delete();
            return response()->json([
                'status' => 'done',
                'enrollment' => $enrollment
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                $e->getMessage()
            ]);
        }
    }
}
