<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Services\AttendanceService;
use Exception;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar\String_;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        protected AttendanceService $attendance_service
    ) {}
    public function index($enrollmentId)
    {
        $enrollment = Enrollment::with('attendances', 'course')
            ->findOrFail($enrollmentId);
        $this->authorize('ViewAny', [Attendance::class, $enrollment]);

        return response()->json([
            'status' => 'done',
            'attendances' => $enrollment->attendances
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'status' => 'required|in:present,absent'
        ]);
        try {
            $attendance = $this->attendance_service->MarkAttendance(
                auth()->user(),
                $data['enrollment_id'],
                $data['status']
            );

            return response()->json([
                'msg' => 'attendance marked success',
                'attendance' => $attendance
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'msg' => $e->getMessage()
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance , String $id)
    {
        $data = $request->validate([
            'status'=>'required|in:present,absent',
            'enrollment_id'=>'required|exists:enrollments,id'
        ]);
        try{
            $attendance = $this->attendance_service->UpdateAttendance(auth()->user(),$id,$data);
            return response()->json([
                'msg'=>'attendance updated successfully',
                'attendance'=>$attendance
            ]);

        }catch(Exception $e){
           return response()->json([
                'status'=>'failed',
                'msg'=>$e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $this->attendance_service->DeleteAttendance(auth()->user(),$id);
        return response()->json([
            'msg'=>'attendance deleted successfully'
        ],200);
    }
}
