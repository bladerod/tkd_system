<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkillChecklistController extends Controller
{
    public function index()
    {
        $beltLevels = DB::table('belt_levels')->orderBy('rank_order')->get();
        $skills = DB::table('skill_checklist')->orderBy('belt_level')->orderBy('id')->get();
        return view('skill_checklist', compact('beltLevels', 'skills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'belt_level' => 'required|string',
            'skill_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        DB::table('skill_checklist')->insert([
            'belt_level'  => $request->belt_level,
            'skill_name'  => $request->skill_name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Skill added successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'belt_level' => 'required|string',
            'skill_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        DB::table('skill_checklist')->where('id', $id)->update([
            'belt_level'  => $request->belt_level,
            'skill_name'  => $request->skill_name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Skill updated successfully!');
    }

    public function destroy($id)
    {
        DB::table('skill_checklist')->where('id', $id)->delete();
        return back()->with('success', 'Skill deleted successfully!');
    }
}