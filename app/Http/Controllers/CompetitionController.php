<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionEntry;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the competitions.
     */
    public function index()
    {
        $competitions = Competition::withCount('entries')
            ->with('entries')
            ->orderBy('date', 'desc')
            ->paginate(10);
        
        $students = Student::where('status', 'active')->get();
        $instructors = Instructor::where('active_flag', 1)->get();
        
        return view('competition', compact('competitions', 'students', 'instructors'));
    }

    /**
     * Show the form for creating a new competition.
     */
    public function create()
    {
        return view('competitions.create');
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
            'organizer' => 'nullable|string|max:150',
            'level' => 'required|in:local,regional,national,international',
        ]);

        Competition::create($validated);

        return redirect()->route('competitions.index')
            ->with('success', 'Competition created successfully.');
    }

    /**
     * Display the specified competition.
     */
    public function show($id)
    {
        $competition = Competition::with(['entries.student', 'entries.instructor'])
            ->findOrFail($id);
        
        return view('competitions.show', compact('competition'));
    }

    /**
     * Show the form for editing the specified competition.
     */
    public function edit($id)
    {
        $competition = Competition::findOrFail($id);
        return view('competitions.edit', compact('competition'));
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
        ]);

        $competition->update($validated);

        return redirect()->route('competitions.index')
            ->with('success', 'Competition updated successfully.');
    }

    /**
     * Remove the specified competition from storage.
     */
    public function destroy($id)
    {
        $competition = Competition::findOrFail($id);
        
        // Delete related entries first
        $competition->entries()->delete();
        $competition->delete();

        return redirect()->route('competitions.index')
            ->with('success', 'Competition deleted successfully.');
    }

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

        return redirect()->route('competitions.show', $competitionId)
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
        
        return view('competitions.edit-entry', compact('entry', 'students', 'instructors'));
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

        return redirect()->route('competitions.show', $competitionId)
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

        return redirect()->route('competitions.show', $competitionId)
            ->with('success', 'Entry deleted successfully.');
    }
}