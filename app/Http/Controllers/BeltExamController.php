<?php

namespace App\Http\Controllers;

use App\Models\BeltExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeltExamController extends Controller
{
    public function index()
    {
        $exams = BeltExam::all();
        return view('belt_exams.index', compact('exams'));
    }

    public function create()
    {
        return view('belt_exams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_date' => 'required|date',
            'belt_level' => 'required|string|max:50',
            'class_id' => 'required|integer',
            'chief_instructor_id' => 'required|integer',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled'
        ]);

        BeltExam::create($validated);

        return redirect()->route('belt-exams.index')
                         ->with('success', 'Belt exam scheduled successfully.');
    }

    public function show(BeltExam $beltExam)
    {
        return view('belt_exams.show', compact('beltExam'));
    }

    public function update(Request $request, BeltExam $beltExam)
    {
        $validated = $request->validate([
            'exam_date' => 'date',
            'belt_level' => 'string|max:50',
            'status' => 'in:scheduled,ongoing,completed,cancelled'
        ]);

        $beltExam->update($validated);

        return redirect()->route('belt-exams.index')
                         ->with('success', 'Belt exam updated.');
    }

    public function destroy(BeltExam $beltExam)
    {
        $beltExam->delete();
        return redirect()->route('belt-exams.index')
                         ->with('success', 'Belt exam deleted.');
    }
}