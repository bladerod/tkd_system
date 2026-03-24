<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Branch;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\ClassSchedule;
use App\Models\ClassStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    /**
     * Display a listing of classes.
     */
    public function index()
    {
        $classes = Classes::with(['branch', 'primaryInstructor', 'assistantInstructor', 'schedules'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate student count for each class
        foreach ($classes as $class) {
            $class->student_count = ClassStudent::where('class_id', $class->id)
                ->where('status', 'active')
                ->count();
        }
        
        $branches = Branch::where('status', 'active')->get();
        $instructors = Instructor::where('status', 'active')->get();
        
        // Fix: Use 'classes' instead of 'classes.index' since the file is directly in views folder
        return view('classes', compact('classes', 'branches', 'instructors'));
    }

    /**
     * Store a newly created class.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'class_name' => 'required|string|max:150',
            'age_group' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'max_students' => 'nullable|integer|min:0',
            'primary_instructor_id' => 'nullable|exists:instructors,id',
            'assistant_instructor_id' => 'nullable|exists:instructors,id',
            'status' => 'required|in:active,inactive,cancelled',
            'schedules' => 'nullable|array',
            'schedules.*.day' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            DB::beginTransaction();

            // Create the class
            $class = Classes::create([
                'branch_id' => $request->branch_id,
                'class_name' => $request->class_name,
                'age_group' => $request->age_group,
                'level' => $request->level,
                'max_students' => $request->max_students ?? 0,
                'primary_instructor_id' => $request->primary_instructor_id,
                'assistant_instructor_id' => $request->assistant_instructor_id,
                'status' => $request->status,
            ]);

            // Create schedules if provided
            if ($request->has('schedules') && !empty($request->schedules)) {
                foreach ($request->schedules as $schedule) {
                    // Only create schedule if day, start_time, and end_time are provided
                    if (!empty($schedule['day']) && !empty($schedule['start_time']) && !empty($schedule['end_time'])) {
                        ClassSchedule::create([
                            'class_id' => $class->id,
                            'day_of_week' => $schedule['day'],
                            'start_time' => $schedule['start_time'],
                            'end_time' => $schedule['end_time'],
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('classes.index')
                ->with('success', 'Class created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create class: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified class.
     */
    public function show($id)
    {
        $class = Classes::with([
            'branch', 
            'primaryInstructor', 
            'assistantInstructor', 
            'schedules',
            'students.student',
            'sessions'
        ])->findOrFail($id);
        
        // Calculate student count
        $class->student_count = ClassStudent::where('class_id', $id)
            ->where('status', 'active')
            ->count();
        
        // Fix: Return the partial view for modal content
        return view('partials.class-details', compact('class'));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit($id)
    {
        $class = Classes::with('schedules')->findOrFail($id);
        
        return response()->json([
            'class' => $class,
            'schedules' => $class->schedules
        ]);
    }

    /**
     * Update the specified class.
     */
    public function update(Request $request, $id)
    {
        $class = Classes::findOrFail($id);

        // First, clean the schedules data - remove empty schedules BEFORE validation
        $cleanedSchedules = [];
        if ($request->has('schedules') && is_array($request->schedules)) {
            foreach ($request->schedules as $schedule) {
                // Only keep schedules that have a day selected AND both time fields
                if (!empty($schedule['day']) && !empty($schedule['start_time']) && !empty($schedule['end_time'])) {
                    $cleanedSchedules[] = [
                        'day' => $schedule['day'],
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time']
                    ];
                }
            }
        }
        
        // Replace the schedules with cleaned ones
        $request->merge(['schedules' => $cleanedSchedules]);

        // Now validate - but only if there are schedules
        $rules = [
            'branch_id' => 'required|exists:branches,id',
            'class_name' => 'required|string|max:150',
            'age_group' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'max_students' => 'nullable|integer|min:0',
            'primary_instructor_id' => 'nullable|exists:instructors,id',
            'assistant_instructor_id' => 'nullable|exists:instructors,id',
            'status' => 'required|in:active,inactive,cancelled',
        ];
        
        // Only add schedule validation rules if there are schedules
        if (!empty($cleanedSchedules)) {
            $rules['schedules'] = 'required|array';
            $rules['schedules.*.day'] = 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday';
            $rules['schedules.*.start_time'] = 'required|date_format:H:i';
            $rules['schedules.*.end_time'] = 'required|date_format:H:i|after:schedules.*.start_time';
        } else {
            $rules['schedules'] = 'nullable|array';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            DB::beginTransaction();

            // Update class
            $class->update([
                'branch_id' => $request->branch_id,
                'class_name' => $request->class_name,
                'age_group' => $request->age_group,
                'level' => $request->level,
                'max_students' => $request->max_students ?? 0,
                'primary_instructor_id' => $request->primary_instructor_id,
                'assistant_instructor_id' => $request->assistant_instructor_id,
                'status' => $request->status,
                'updated_at' => now(),
            ]);

            // Update schedules - delete old and create new
            ClassSchedule::where('class_id', $class->id)->delete();
            
            if (!empty($cleanedSchedules)) {
                foreach ($cleanedSchedules as $schedule) {
                    ClassSchedule::create([
                        'class_id' => $class->id,
                        'day_of_week' => $schedule['day'],
                        'start_time' => $schedule['start_time'],
                        'end_time' => $schedule['end_time'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('classes.index')
                ->with('success', 'Class updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update class: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified class.
     */
    public function destroy($id)
    {
        try {
            $class = Classes::findOrFail($id);
            
            // Check if class has active students
            $activeStudents = ClassStudent::where('class_id', $id)
                ->where('status', 'active')
                ->count();
            
            if ($activeStudents > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete class with active students. Please drop students first.');
            }
            
            // Delete schedules
            ClassSchedule::where('class_id', $id)->delete();
            
            // Delete class students
            ClassStudent::where('class_id', $id)->delete();
            
            // Delete class
            $class->delete();
            
            return redirect()->route('classes.index')
                ->with('success', 'Class deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete class: ' . $e->getMessage());
        }
    }

    /**
     * Get class details for AJAX.
     */
    public function getClassDetails($id)
    {
        $class = Classes::with(['branch', 'primaryInstructor', 'assistantInstructor', 'schedules'])
            ->findOrFail($id);
        
        return response()->json([
            'class' => $class,
            'schedules' => $class->schedules
        ]);
    }

    /**
     * Get available students for a class.
     */
    public function getAvailableStudents($classId)
    {
        $class = Classes::findOrFail($classId);
        
        $enrolledStudentIds = ClassStudent::where('class_id', $classId)
            ->pluck('student_id')
            ->toArray();
        
        $availableStudents = Student::where('status', 'active')
            ->where('branch_id', $class->branch_id)
            ->whereNotIn('id', $enrolledStudentIds)
            ->get(['id', 'first_name', 'last_name', 'current_belt']);
        
        return response()->json($availableStudents);
    }

    /**
     * Enroll a student in a class.
     */
    public function enrollStudent(Request $request, $classId)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'start_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $class = Classes::findOrFail($classId);
        
        // Check if class is full
        $currentStudents = ClassStudent::where('class_id', $classId)
            ->where('status', 'active')
            ->count();
        
        if ($class->max_students > 0 && $currentStudents >= $class->max_students) {
            return response()->json(['error' => 'Class is already full.'], 400);
        }
        
        // Check if student is already enrolled
        $existing = ClassStudent::where('class_id', $classId)
            ->where('student_id', $request->student_id)
            ->where('status', 'active')
            ->first();
        
        if ($existing) {
            return response()->json(['error' => 'Student is already enrolled in this class.'], 400);
        }
        
        try {
            ClassStudent::create([
                'class_id' => $classId,
                'student_id' => $request->student_id,
                'start_date' => $request->start_date,
                'status' => 'active'
            ]);
            
            return response()->json(['success' => 'Student enrolled successfully!']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to enroll student: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove a student from a class.
     */
    public function removeStudent($classId, $studentId)
    {
        try {
            $enrollment = ClassStudent::where('class_id', $classId)
                ->where('student_id', $studentId)
                ->first();
            
            if (!$enrollment) {
                return response()->json(['error' => 'Student not found in this class.'], 404);
            }
            
            $enrollment->delete();
            
            return response()->json(['success' => 'Student removed from class successfully!']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove student: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update student enrollment status.
     */
    public function updateStudentStatus(Request $request, $classId, $studentId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,completed,dropped',
            'end_date' => 'nullable|date'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {
            $enrollment = ClassStudent::where('class_id', $classId)
                ->where('student_id', $studentId)
                ->first();
            
            if (!$enrollment) {
                return response()->json(['error' => 'Student not found in this class.'], 404);
            }
            
            $enrollment->update([
                'status' => $request->status,
                'end_date' => $request->end_date ?? ($request->status !== 'active' ? now() : null)
            ]);
            
            return response()->json(['success' => 'Student status updated successfully!']);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update student status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export classes to CSV.
     */
    public function exportCsv()
    {
        $classes = Classes::with(['branch', 'primaryInstructor', 'assistantInstructor'])
            ->get();
        
        foreach ($classes as $class) {
            $class->student_count = ClassStudent::where('class_id', $class->id)
                ->where('status', 'active')
                ->count();
        }
        
        $filename = "classes_export_" . date('Y-m-d') . ".csv";
        $handle = fopen('php://temp', 'w+');
        
        // Add CSV headers
        fputcsv($handle, [
            'ID', 'Class Name', 'Branch', 'Age Group', 'Level', 'Max Students', 
            'Primary Instructor', 'Assistant Instructor', 'Current Students', 'Status', 'Created At'
        ]);
        
        foreach ($classes as $class) {
            fputcsv($handle, [
                $class->id,
                $class->class_name,
                $class->branch->name ?? 'N/A',
                $class->age_group ?? 'N/A',
                $class->level ?? 'N/A',
                $class->max_students,
                $class->primaryInstructor ? $class->primaryInstructor->fname . ' ' . $class->primaryInstructor->lname : 'N/A',
                $class->assistantInstructor ? $class->assistantInstructor->fname . ' ' . $class->assistantInstructor->lname : 'N/A',
                $class->student_count,
                ucfirst($class->status),
                $class->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);
        
        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}