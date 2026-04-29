<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EvaluationApiController extends Controller
{
    // GET /instructor/evaluation/students
    // Returns all students in the instructor's classes
    public function getStudents(Request $request)
    {
        try {
            $instructor = Auth::user();
            $instructorRecord = DB::table('instructors')
                ->where('user_id', $instructor->id)
                ->first();

            if (!$instructorRecord) {
                return response()->json(['success' => false, 'message' => 'Instructor not found'], 404);
            }

            $students = DB::table('students as s')
                ->join('class_students as cs', 's.id', '=', 'cs.student_id')
                ->join('classes as c', 'cs.class_id', '=', 'c.id')
                ->join('belt_levels as bl', 's.current_belt', '=', 'bl.id')
                ->where('c.primary_instructor_id', $instructorRecord->id)
                ->where('s.status', 'active')
                ->select(
                    's.id',
                    's.first_name',
                    's.last_name',
                    's.current_belt as belt_level_id',
                    'bl.name as belt_name',
                    'bl.color_code as belt_color',
                    's.photo_url',
                    's.student_code'
                )
                ->distinct('s.id')
                ->get()
                ->unique('id')
                ->values();

            return response()->json([
                'success' => true,
                'students' => $students
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /instructor/evaluation/skills/{belt}
    // Returns skills checklist for a given belt level
    public function getSkillsByBelt($belt)
    {
        try {
            $skills = DB::table('skill_checklist')
                ->where('belt_level', $belt)
                ->select('id', 'skill_name', 'description')
                ->get();

            return response()->json([
                'success' => true,
                'belt_level' => $belt,
                'skills' => $skills
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // POST /instructor/evaluation/save
    // Saves evaluation scores + skill progress
    public function saveEvaluation(Request $request)
    {
        try {
            $instructor = Auth::user();
            $instructorRecord = DB::table('instructors')
                ->where('user_id', $instructor->id)
                ->first();

            if (!$instructorRecord) {
                return response()->json(['success' => false, 'message' => 'Instructor not found'], 404);
            }

            // Validate request
            $request->validate([
                'student_id' => 'required|integer',
                'class_id' => 'nullable|integer',
                'technique_score' => 'required|integer|min:0|max:10',
                'discipline_score' => 'required|integer|min:0|max:10',
                'fitness_score' => 'required|integer|min:0|max:10',
                'sparring_score' => 'nullable|integer|min:0|max:10',
                'notes' => 'nullable|string',
                'belt_ready_flag' => 'required|boolean',
                'skill_results' => 'required|array',
                'skill_results.*.skill_id' => 'required|integer',
                'skill_results.*.is_passed' => 'required|boolean',
            ]);

            // Insert main evaluation record
            $evaluationId = DB::table('student_evaluations')->insertGetId([
                'student_id' => $request->student_id,
                'instructor_id' => $instructorRecord->id,
                'class_id' => $request->class_id,
                'evaluation_date' => now()->toDateString(),
                'technique_score' => $request->technique_score,
                'discipline_score' => $request->discipline_score,
                'fitness_score' => $request->fitness_score,
                'sparring_score' => $request->sparring_score ?? 0,
                'notes' => $request->notes,
                'belt_ready_flag' => $request->belt_ready_flag ? 1 : 0,
            ]);

            // Insert skill progress records
            foreach ($request->skill_results as $skill) {
                $status = $skill['is_passed'] ? 'completed' : 'in_progress';

                // Check if existing record para i-update
                $existing = DB::table('student_skill_progress')
                    ->where('student_id', $request->student_id)
                    ->where('skill_id', $skill['skill_id'])
                    ->first();

                if ($existing) {
                    DB::table('student_skill_progress')
                        ->where('id', $existing->id)
                        ->update([
                            'status' => $status,
                            'instructor_id' => $instructorRecord->id,
                            'checked_at' => now(),
                        ]);
                } else {
                    DB::table('student_skill_progress')->insert([
                        'student_id' => $request->student_id,
                        'skill_id' => $skill['skill_id'],
                        'instructor_id' => $instructorRecord->id,
                        'status' => $status,
                        'checked_at' => now(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Evaluation saved successfully',
                'evaluation_id' => $evaluationId
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /instructor/evaluation/history/{studentId}
    // Returns past evaluations of a student
    public function getHistory($studentId)
    {
        try {
            $evaluations = DB::table('student_evaluations as se')
                ->join('instructors as i', 'se.instructor_id', '=', 'i.id')
                ->join('users as u', 'i.user_id', '=', 'u.id')
                ->where('se.student_id', $studentId)
                ->orderBy('se.evaluation_date', 'desc')
                ->select(
                    'se.id',
                    'se.evaluation_date',
                    'se.technique_score',
                    'se.discipline_score',
                    'se.fitness_score',
                    'se.sparring_score',
                    'se.notes',
                    'se.belt_ready_flag',
                    DB::raw("CONCAT(u.fname, ' ', u.lname) as instructor_name")
                )
                ->get();

            // Per evaluation, i-attach yung skill results
            foreach ($evaluations as $eval) {
                $eval->skill_results = DB::table('student_skill_progress as ssp')
                    ->join('skill_checklist as sc', 'ssp.skill_id', '=', 'sc.id')
                    ->where('ssp.student_id', $studentId)
                    ->where('ssp.instructor_id', DB::table('student_evaluations')
                        ->where('id', $eval->id)
                        ->value('instructor_id'))
                    ->select(
                        'sc.skill_name',
                        'sc.belt_level',
                        'ssp.status',
                        'ssp.checked_at'
                    )
                    ->get();
            }

            return response()->json([
                'success' => true,
                'student_id' => $studentId,
                'evaluations' => $evaluations
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /student/progress
    public function studentProgress(Request $request)
    {
        try {
            $user = Auth::user();
            $student = DB::table('students')
                ->where('user_id', $user->id)
                ->first();

            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found'], 404);
            }

            // Latest evaluation
            $latestEval = DB::table('student_evaluations')
                ->where('student_id', $student->id)
                ->when($student->last_promoted_at, function ($query) use ($student) {
                    return $query->where('evaluated_date', '>', $student->last_promoted_at);
                })
                ->orderBy('evaluation_date', 'desc')
                ->first();

            // Skill progress
            $skillProgress = DB::table('student_skill_progress as ssp')
                ->join('skill_checklist as sc', 'ssp.skill_id', '=', 'sc.id')
                ->where('ssp.student_id', $student->id)
                ->select('sc.skill_name', 'sc.belt_level', 'ssp.status', 'ssp.checked_at')
                ->orderBy('ssp.checked_at', 'desc')
                ->get();

            // Belt info
            $belt = DB::table('belt_levels')
                ->where('id', $student->current_belt)
                ->first();

            // Compute belt readiness score
            $totalEvals = DB::table('student_evaluations')
                ->where('student_id', $student->id)
                ->count();

            $readyCount = DB::table('student_evaluations')
                ->where('student_id', $student->id)
                ->where('belt_ready_flag', 1)
                ->count();


            // Skill completion rate per belt level
            $totalSkills = DB::table('skill_checklist')
                ->where('belt_level', $belt->name ?? '')
                ->count();

            $completedSkills = DB::table('student_skill_progress as ssp')
                ->join('skill_checklist as sc', 'ssp.skill_id', '=', 'sc.id')
                ->where('ssp.student_id', $student->id)
                ->where('sc.belt_level', $belt->name ?? '')
                ->where('ssp.status', 'completed')
                ->count();

            $skillCompletionRate = $totalSkills > 0
                ? round(($completedSkills / $totalSkills) * 100)
                : 0;

            $readinessScore = 0;
            if ($latestEval) {
                if ($latestEval->belt_ready_flag) {
                    $readinessScore = 100;
                } else {
                    $readinessScore = $skillCompletionRate;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'student' => [
                        'id' => $student->id,
                        'name' => $student->first_name . ' ' . $student->last_name,
                        'current_belt' => $belt->name ?? 'Unknown',
                        'belt_color' => $belt->color_code ?? '#CCCCCC',
                    ],
                    'latest_evaluation' => $latestEval ? [
                        'date' => $latestEval->evaluation_date,
                        'technique_score' => $latestEval->technique_score,
                        'discipline_score' => $latestEval->discipline_score,
                        'fitness_score' => $latestEval->fitness_score,
                        'sparring_score' => $latestEval->sparring_score,
                        'notes' => $latestEval->notes,
                        'belt_ready_flag' => (bool) $latestEval->belt_ready_flag,
                    ] : null,
                    'skill_progress' => $skillProgress,
                    'readiness_score' => $readinessScore,
                    'skill_completion' => $skillCompletionRate,
                    'completed_skills' => $completedSkills,
                    'total_skills' => $totalSkills,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getLatestEvaluation($studentId)
    {
        try {
            $student = DB::table('students')->where('id', $studentId)->first();

            $query = DB::table('student_evaluations')
                ->where('student_id', $studentId);

            // Filter evaluations after last promotion
            if ($student && $student->last_promoted_at) {
                $query->where(
                    'evaluation_date',
                    '>',
                    \Carbon\Carbon::parse($student->last_promoted_at)->toDateString()
                );
            }

            $evaluation = $query->orderBy('evaluation_date', 'desc')->first();

            if (!$evaluation) {
                return response()->json([
                    'success' => true,
                    'evaluation' => null
                ]);
            }

            $skillResults = DB::table('student_skill_progress')
                ->where('student_id', $studentId)
                ->get()
                ->keyBy('skill_id');

            return response()->json([
                'success' => true,
                'evaluation' => [
                    'technique_score' => $evaluation->technique_score,
                    'discipline_score' => $evaluation->discipline_score,
                    'fitness_score' => $evaluation->fitness_score,
                    'sparring_score' => $evaluation->sparring_score,
                    'notes' => $evaluation->notes,
                    'belt_ready_flag' => (bool) $evaluation->belt_ready_flag,
                    'skill_results' => $skillResults,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getBeltPromotionCandidates(Request $request)
    {
        try {
            $instructor = Auth::user();
            $instructorRecord = DB::table('instructors')
                ->where('user_id', $instructor->id)
                ->first();

            if (!$instructorRecord) {
                return response()->json(['success' => false, 'message' => 'Instructor not found'], 404);
            }

            // Get students na may latest evaluation na belt_ready_flag = 1
            $students = DB::table('students as s')
                ->join('class_students as cs', 's.id', '=', 'cs.student_id')
                ->join('classes as c', 'cs.class_id', '=', 'c.id')
                ->join('belt_levels as bl', 's.current_belt', '=', 'bl.id')
                ->where('c.primary_instructor_id', $instructorRecord->id)
                ->where('s.status', 'active')
                ->select(
                    's.id',
                    's.first_name',
                    's.last_name',
                    's.current_belt',
                    's.student_code',
                    's.photo_url',
                    'bl.name as belt_name',
                    'bl.color_code as belt_color',
                    'bl.rank_order'
                )
                ->distinct('s.id')
                ->get()
                ->unique('id')
                ->values();

            $candidates = [];

            foreach ($students as $student) {
                // Get latest evaluation
                $latestEval = DB::table('student_evaluations')
                    ->where('student_id', $student->id)
                    ->orderBy('evaluation_date', 'desc')
                    ->first();

                if (!$latestEval || !$latestEval->belt_ready_flag)
                    continue;

                // Get skill completion
                $totalSkills = DB::table('skill_checklist')
                    ->where('belt_level', $student->belt_name)
                    ->count();

                $completedSkills = DB::table('student_skill_progress as ssp')
                    ->join('skill_checklist as sc', 'ssp.skill_id', '=', 'sc.id')
                    ->where('ssp.student_id', $student->id)
                    ->where('sc.belt_level', $student->belt_name)
                    ->where('ssp.status', 'completed')
                    ->count();

                $skillCompletion = $totalSkills > 0
                    ? round(($completedSkills / $totalSkills) * 100)
                    : 0;

                // Get next belt
                $nextBelt = DB::table('belt_levels')
                    ->where('rank_order', $student->rank_order + 1)
                    ->first();

                $candidates[] = [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'student_code' => $student->student_code,
                    'photo_url' => $student->photo_url,
                    'current_belt' => $student->belt_name,
                    'belt_color' => $student->belt_color,
                    'next_belt' => $nextBelt?->name ?? 'Black Belt',
                    'next_belt_color' => $nextBelt?->color_code ?? '#000000',
                    'technique_score' => $latestEval->technique_score,
                    'discipline_score' => $latestEval->discipline_score,
                    'fitness_score' => $latestEval->fitness_score,
                    'sparring_score' => $latestEval->sparring_score,
                    'skill_completion' => $skillCompletion,
                    'evaluation_date' => $latestEval->evaluation_date,
                    'notes' => $latestEval->notes,
                ];
            }

            return response()->json([
                'success' => true,
                'candidates' => $candidates,
                'total' => count($candidates),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // POST /instructor/belt-promotion/approve
    public function approvePromotion(Request $request)
    {
        try {
            $request->validate([
                'student_ids' => 'required|array',
            ]);

            foreach ($request->student_ids as $studentId) {
                $student = DB::table('students')->where('id', $studentId)->first();
                if (!$student)
                    continue;

                // Get next belt
                $currentBelt = DB::table('belt_levels')
                    ->where('id', $student->current_belt)
                    ->first();

                $nextBelt = DB::table('belt_levels')
                    ->where('rank_order', $currentBelt->rank_order + 1)
                    ->first();

                if (!$nextBelt)
                    continue;

                // Update student belt
                DB::table('students')
                    ->where('id', $studentId)
                    ->update([
                        'current_belt' => $nextBelt->id,
                        'last_promoted_at' => now()
                    ]);


                // Reset belt_ready_flag ng latest evaluation
                $latestEval = DB::table('student_evaluations')
                    ->where('student_id', $studentId)
                    ->orderBy('evaluation_date', 'desc')
                    ->first();

                if ($latestEval) {
                    DB::table('student_evaluations')
                        ->where('id', $latestEval->id)
                        ->update(['belt_ready_flag' => 0]);
                }

            }

            // Reset din yung student_skill_progress para sa bagong belt
            DB::table('student_skill_progress')
                ->where('student_id', $studentId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Promotion approved successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}