<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show($student_id)
    {
        $student = auth()->user();
         // جلب الدرجات للفصل الدراسي الحالي
         $currentSemester = 'Fall 2024'; // يمكنك جعلها ديناميكية إذا أردت
         $grades = Grade::whereHas('enrollment', function ($query) use ($student_id) {
             $query->where('student_id', $student_id);
         })->where('semster', $currentSemester)->get();

         // حساب الـ GPA التراكمي
         $allGrades = Grade::whereHas('enrollment', function ($query) use ($student_id) {
             $query->where('student_id', $student_id);
         })->get();

         // حساب الـ GPA التراكمي
         $totalCredits = $allGrades->sum(function ($grade) {
             return $grade->enrollment->course->credits;
         });
         $totalPoints = $allGrades->sum(function ($grade) {
             return $grade->grade * $grade->enrollment->course->credits;
         });

         $gpa = $totalCredits ? $totalPoints / $totalCredits : 0;

         // إرجاع الدرجات مع الـ GPA التراكمي
         return response()->json([
             'success' => true,
             'data' => [
                 'current_semester_grades' => $grades,
                 'cumulative_gpa' => round($gpa, 2),
                 'student'=>$student
             ]
         ], 200);
     }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        //
    }
}
