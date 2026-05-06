<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompetitionApiController extends Controller
{
    // GET /instructor/competitions
    public function instructorCompetitions(Request $request)
    {
        try {
            $instructor = Auth::user();
            $instructorRecord = DB::table('instructors')
                ->where('user_id', $instructor->id)
                ->first();

            if (!$instructorRecord) {
                return response()->json(['success' => false, 'message' => 'Instructor not found'], 404);
            }

            $competitions = DB::table('competitions as c')
                ->join('competition_entries as ce', 'c.id', '=', 'ce.competition_id')
                ->where('ce.instructor_id', $instructorRecord->id)
                ->select(
                    'c.id',
                    'c.name',
                    'c.location',
                    'c.date',
                    'c.organizer',
                    'c.level',
                    'c.status',
                    DB::raw('COUNT(ce.id) as total_entries'),
                    DB::raw("SUM(CASE WHEN ce.medal = 'gold' THEN 1 ELSE 0 END) as gold_count"),
                    DB::raw("SUM(CASE WHEN ce.medal = 'silver' THEN 1 ELSE 0 END) as silver_count"),
                    DB::raw("SUM(CASE WHEN ce.medal = 'bronze' THEN 1 ELSE 0 END) as bronze_count")
                )
                ->groupBy('c.id', 'c.name', 'c.location', 'c.date', 'c.organizer', 'c.level', 'c.status')
                ->orderBy('c.date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'competitions' => $competitions,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /instructor/competitions/{id}/entries
    public function competitionEntries($competitionId)
    {
        try {
            $competition = DB::table('competitions')->where('id', $competitionId)->first();

            if (!$competition) {
                return response()->json(['success' => false, 'message' => 'Competition not found'], 404);
            }

            $entries = DB::table('competition_entries as ce')
                ->join('students as s', 'ce.student_id', '=', 's.id')
                ->join('belt_levels as bl', 's.current_belt', '=', 'bl.id')
                ->where('ce.competition_id', $competitionId)
                ->select(
                    'ce.id',
                    'ce.student_id',
                    's.first_name',
                    's.last_name',
                    's.student_code',
                    's.photo_url',
                    'bl.name as belt_name',
                    'bl.color_code as belt_color',
                    'ce.category',
                    'ce.division',
                    'ce.result',
                    'ce.medal',
                    'ce.remarks'
                )
                ->get();

            return response()->json([
                'success' => true,
                'competition' => $competition,
                'entries' => $entries,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // POST /instructor/competitions/{id}/add-note
    public function addNote(Request $request, $entryId)
    {
        try {
            $request->validate([
                'remarks' => 'required|string',
            ]);

            DB::table('competition_entries')
                ->where('id', $entryId)
                ->update(['remarks' => $request->remarks]);

            return response()->json([
                'success' => true,
                'message' => 'Note saved successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // GET /student/competitions
    public function studentCompetitions(Request $request)
    {
        try {
            $user = Auth::user();
            $student = DB::table('students')
                ->where('user_id', $user->id)
                ->first();

            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found'], 404);
            }

            $entries = DB::table('competition_entries as ce')
                ->join('competitions as c', 'ce.competition_id', '=', 'c.id')
                ->where('ce.student_id', $student->id)
                ->select(
                    'ce.id',
                    'c.name as competition_name',
                    'c.location',
                    'c.date',
                    'c.level',
                    'ce.category',
                    'ce.division',
                    'ce.result',
                    'ce.medal',
                    'ce.remarks'
                )
                ->orderBy('c.date', 'desc')
                ->get();

            $stats = [
                'total' => $entries->count(),
                'gold' => $entries->where('medal', 'gold')->count(),
                'silver' => $entries->where('medal', 'silver')->count(),
                'bronze' => $entries->where('medal', 'bronze')->count(),
                'wins' => $entries->where('result', 'win')->count(),
            ];

            return response()->json([
                'success' => true,
                'entries' => $entries->values(),
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function parentChildCompetitions($childId)
{
    try {
        $entries = DB::table('competition_entries as ce')
            ->join('competitions as c', 'ce.competition_id', '=', 'c.id')
            ->where('ce.student_id', $childId)
            ->select(
                'ce.id',
                'c.name as competition_name',
                'c.location',
                'c.date',
                'c.level',
                'ce.category',
                'ce.division',
                'ce.result',
                'ce.medal',
                'ce.remarks'
            )
            ->orderBy('c.date', 'desc')
            ->get();

        $stats = [
            'total'  => $entries->count(),
            'gold'   => $entries->where('medal', 'gold')->count(),
            'silver' => $entries->where('medal', 'silver')->count(),
            'bronze' => $entries->where('medal', 'bronze')->count(),
            'wins'   => $entries->where('result', 'win')->count(),
        ];

        return response()->json([
            'success' => true,
            'entries' => $entries->values(),
            'stats'   => $stats,
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

}