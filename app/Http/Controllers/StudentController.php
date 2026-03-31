<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $vwstudents = DB::table('students as s')
            ->leftJoin('belt_levels as b', 's.current_belt', '=', 'b.id')
            ->select(
                's.id',
                DB::raw("CONCAT(s.first_name,' ',s.last_name) as student_name"),
                'b.name as current_belt',
                's.status',
                's.emergency_contact_name as parent_name',
                DB::raw("0 as balance"),
                DB::raw("0 as attendance")
            )
            ->get();

        $beltlevels = DB::table('belt_levels')->get();
        $classes = DB::table('classes')->get();
        $users = DB::table('users')->get();

        return view('students.index', compact('vwstudents','beltlevels','classes','users'));
    }

    public function show($id)
    {
        $student = DB::table('students')->where('id', $id)->first();

        return response()->json([
            'id' => $student->id,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'birthdate' => $student->birthdate,
            'status' => $student->status,
            'emergency_contact' => $student->emergency_contact_name,

            'belt_name' => DB::table('belt_levels')
                ->where('id', $student->current_belt)
                ->value('name'),

            'invoices' => DB::table('invoices')
                ->where('student_id', $id)
                ->get(),

            'competitions' => DB::table('competition_entries as ce')
                ->join('competitions as c', 'ce.competition_id', '=', 'c.id')
                ->where('ce.student_id', $id)
                ->select('c.name', 'ce.category', 'ce.result', 'ce.medal')
                ->get(),

            'certificates' => DB::table('certificates')
                ->where('student_id', $id)
                ->get()
        ]);
    }

    public function attendance($id)
    {
        return response()->json(
            DB::table('attendance_logs')
                ->where('student_id', $id)
                ->get()
        );
    }
}
