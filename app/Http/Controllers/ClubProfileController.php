<?php
// app/Http/Controllers/ClubProfileController.php

namespace App\Http\Controllers;

use App\Models\ClubProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClubProfileController extends Controller
{
    public function index()
    {
        $clubProfile = ClubProfile::getClubProfile();
        return view('clubprofile', compact('clubProfile'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'club_name' => 'required|string|max:255',
            'club_acronym' => 'nullable|string|max:50',
            'founded_year' => 'nullable|string|size:4',
            'club_description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'club_address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $clubProfile = ClubProfile::first();
        
        if (!$clubProfile) {
            $clubProfile = new ClubProfile();
        }

        $clubProfile->club_name = $request->club_name;
        $clubProfile->club_acronym = $request->club_acronym;
        $clubProfile->founded_year = $request->founded_year;
        $clubProfile->club_description = $request->club_description;
        $clubProfile->email = $request->email;
        $clubProfile->contact_number = $request->contact_number;
        $clubProfile->club_address = $request->club_address;
        
        $clubProfile->save();

        return redirect()->route('settings.club-profile')
            ->with('success', 'Club profile updated successfully!');
    }
}