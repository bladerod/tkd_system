<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionEntry;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the competition.
     */
    public function index()
    {   
        $competitionEntry = CompetitionEntry::all();
        
        $competition = Competition::where('status', 'active')->get();
        
        $students = Student::where('status', 'active')->get();
        $instructors = Instructor::where('active_flag', 1)->get();
        
        return view('competition', compact('competition', 'students', 'instructors'));
    }

    /**
     * Show the form for creating a new competition.
     */
    public function create()
    {
        return view('competition.create');
    }

    /**
     * Store a newly created competition in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:150',
            'date' => 'required|date',
            'organizer' => 'required|string|max:150',
            'level' => 'required|in:local,regional,national,international',
        ]);

        // Set status to 'active' by default when creating
        $validated['status'] = 'active';
        
        Competition::create($validated);

        return redirect()->route('competition.index')
            ->with('success', 'Competition created successfully.');
    }

    /**
     * Display the specified competition (For the View Page).
     */
    public function show($id)
    {
        $competition = Competition::with(['entries.student', 'entries.instructor'])->findOrFail($id);
        
        // Pass students and instructors so the modals can use them in dropdowns
        $students = Student::where('status', 'active')->get();
        $instructors = Instructor::where('active_flag', 1)->get();
        
        return view('competitions.show', compact('competition', 'students', 'instructors'));
    }

    /**
     * Fetch entry data for the Edit Modal (Returns JSON).
     */
    public function getEntryJson($competitionId, $entryId)
    {
        $entry = CompetitionEntry::where('competition_id', $competitionId)
            ->findOrFail($entryId);
            
        return response()->json($entry);
    }

    public function getCompetitionJson($id)
    {
        $competition = Competition::findOrFail($id);
        
        return response()->json($competition);
    }

    /**
     * Show the form for editing the specified competition.
     */
    public function edit($id)
    {
        $competition = Competition::findOrFail($id);
        return view('competition.edit', compact('competition'));
    }

    /**
     * Update the specified competition in storage.
     */
    public function update(Request $request, $id)
    {
        $competition = Competition::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:150',
            'date' => 'required|date',
            'organizer' => 'nullable|string|max:150',
            'level' => 'required|in:local,regional,national,international',
            'status' => 'required|in:active,inactive',
        ]);

        $competition->update($validated);

        // Check if it's an AJAX request
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Competition updated successfully.',
                'competition' => $competition
            ]);
        }

        return redirect()->route('competition.index')
            ->with('success', 'Competition updated successfully.');
    }

    /**
     * Soft delete the specified competition (update status to inactive).
     */
    public function destroy($id)
    {
        try {
            $competition = Competition::findOrFail($id);
            
            $competition->update([
                'status' => 'inactive'
            ]);

            
            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Competition deleted successfully.',
                    'competition' => $competition
                ]);
            }
            
            return redirect()->route('competition.index')
                ->with('success', 'Competition deleted successfully.');
                
        } catch (\Exception $e) {
            // Handle error
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete competition: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('competition.index')
                ->with('error', 'Failed to delete competition.');
        }
    }

    /**
     * Restore a soft-deleted competition (set status back to active)
     */
    // public function restore($id)
    // {
    //     try {
    //         $competition = Competition::findOrFail($id);
            
    //         // Restore: Update status to 'active'
    //         $competition->update([
    //             'status' => 'active'
    //         ]);
            
    //         if (request()->ajax() || request()->wantsJson()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Competition restored successfully.',
    //                 'competition' => $competition
    //             ]);
    //         }
            
    //         return redirect()->route('competition.index')
    //             ->with('success', 'Competition restored successfully.');
                
    //     } catch (\Exception $e) {
    //         if (request()->ajax() || request()->wantsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Failed to restore competition: ' . $e->getMessage()
    //             ], 500);
    //         }
            
    //         return redirect()->route('competition.index')
    //             ->with('error', 'Failed to restore competition.');
    //     }
    // }

    /**
     * Show form to add entry to competition.
     */
    public function addEntryForm($competitionId)
    {
        $competition = Competition::findOrFail($competitionId);
        $students = Student::where('status', 'active')->get();
        $instructors = Instructor::where('active_flag', 1)->get();
        
        return view('competitions.add-entry', compact('competition', 'students', 'instructors'));
    }

    /**
     * Store a new competition entry.
     */
    public function storeEntry(Request $request, $competitionId)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'instructor_id' => 'required|exists:instructors,id',
            'category' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'result' => 'required|in:win,loss,draw,pending',
            'medal' => 'required|in:gold,silver,bronze,none',
            'remarks' => 'nullable|string',
        ]);

        $validated['competition_id'] = $competitionId;
        
        CompetitionEntry::create($validated);

        return redirect()->route('competition.show', $competitionId)
            ->with('success', 'Entry added successfully.');
    }

    /**
     * Edit competition entry.
     */
    public function editEntry($competitionId, $entryId)
    {
        $entry = CompetitionEntry::where('competition_id', $competitionId)
            ->findOrFail($entryId);
        $students = Student::where('status', 'active')->get();
        $instructors = Instructor::where('active_flag', 1)->get();
        
        return view('competition.edit-entry', compact('entry', 'students', 'instructors'));
    }

    /**
     * Update competition entry.
     */
    public function updateEntry(Request $request, $competitionId, $entryId)
    {
        $entry = CompetitionEntry::where('competition_id', $competitionId)
            ->findOrFail($entryId);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'instructor_id' => 'required|exists:instructors,id',
            'category' => 'required|string|max:100',
            'division' => 'required|string|max:100',
            'result' => 'required|in:win,loss,draw,pending',
            'medal' => 'required|in:gold,silver,bronze,none',
            'remarks' => 'nullable|string',
        ]);

        $entry->update($validated);

        return redirect()->route('competition.show', $competitionId)
            ->with('success', 'Entry updated successfully.');
    }

    /**
     * Delete competition entry.
     */
    public function destroyEntry($competitionId, $entryId)
    {
        $entry = CompetitionEntry::where('competition_id', $competitionId)
            ->findOrFail($entryId);
        $entry->delete();

        return redirect()->route('competition.show', $competitionId)
            ->with('success', 'Entry deleted successfully.');
    }
}