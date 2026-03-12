<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Models\Classes;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index()
    {
        // Get all announcements with their creator, class, and branch
        $announcements = Announcement::with(['creator', 'class', 'branch'])->get();
        
        // Get classes for dropdown
        $classes = Classes::select('id', 'class_name', 'branch_id')->get();
        
        // Get branches for dropdown
        $branches = Branch::select('id', 'name')->get();
        
        // Get users for creator dropdown
        $users = User::all();
        
        // Predefined belt levels
        $beltLevels = [
            'White Belt',
            'Yellow Belt', 
            'Orange Belt',
            'Green Belt',
            'Blue Belt',
            'Brown Belt',
            'Black Belt'
        ];
        
        return view('announcement', [
            'announcements' => $announcements,
            'classes' => $classes,
            'branches' => $branches,
            'users' => $users,
            'beltLevels' => $beltLevels
        ]);
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Log the incoming request for debugging
            Log::info('Announcement store request:', $request->all());

            // Validate the request with custom messages
            $validated = $request->validate([
                'title' => 'required|string|max:200',
                'message' => 'required|string',
                'target_type' => 'required|in:all,class,belt,branch',
                'class_id' => 'nullable|exists:classes,id',
                'belt_level' => 'nullable|string',
                'branch_id' => 'nullable|exists:branches,id',
                'channel' => 'required|array',
                'channel.*' => 'in:App,SMS,Email',
                'expire_date' => 'required|date|after_or_equal:today',
                'created_by_user_id' => 'required|exists:users,id',
            ]);

            // Additional conditional validation
            if ($request->target_type === 'class' && !$request->filled('class_id')) {
                return redirect()->back()
                    ->with('error', 'Please select a class when targeting by Class.')
                    ->withInput();
            }

            if ($request->target_type === 'branch' && !$request->filled('branch_id')) {
                return redirect()->back()
                    ->with('error', 'Please select a branch when targeting by Branch.')
                    ->withInput();
            }

            if ($request->target_type === 'belt' && !$request->filled('belt_level')) {
                return redirect()->back()
                    ->with('error', 'Please select a belt level when targeting by Belt.')
                    ->withInput();
            }

            // Convert channel array to comma-separated string
            $channelString = implode(',', $request->channel);

            // Prepare data for creation
            $data = [
                'created_by_user_id' => $request->created_by_user_id,
                'target_type' => $request->target_type,
                'title' => $request->title,
                'message' => $request->message,
                'channel' => $channelString,
                'publish_date' => Carbon::now(),
                'expire_date' => $request->expire_date,
            ];

            // Add conditional fields based on target type
            if ($request->target_type === 'class') {
                $data['class_id'] = $request->class_id;
                $data['belt_level'] = null;
                $data['branch_id'] = null;
            } elseif ($request->target_type === 'belt') {
                $data['belt_level'] = $request->belt_level;
                $data['class_id'] = null;
                $data['branch_id'] = null;
            } elseif ($request->target_type === 'branch') {
                $data['branch_id'] = $request->branch_id;
                $data['class_id'] = null;
                $data['belt_level'] = null;
            } else { // all
                $data['class_id'] = null;
                $data['belt_level'] = null;
                $data['branch_id'] = null;
            }

            // Log the data being inserted
            Log::info('Announcement data to insert:', $data);

            // Create announcement
            $announcement = Announcement::create($data);

            DB::commit();

            return redirect()->route('announcements.index')
                ->with('success', 'Announcement created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            $firstError = collect($e->errors())->flatten()->first();
            
            Log::error('Validation failed: ' . $firstError);
            
            return redirect()->back()
                ->with('error', 'Validation failed: ' . $firstError)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create announcement: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create announcement. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified announcement.
     */
    public function show($id)
    {
        try {
            $announcement = Announcement::with(['creator', 'class', 'branch'])->findOrFail($id);
            
            // Convert channel string back to array
            $channels = $announcement->channel ? explode(',', $announcement->channel) : [];
            
            return response()->json([
                'id' => $announcement->id,
                'title' => $announcement->title,
                'message' => $announcement->message,
                'target_type' => $announcement->target_type,
                'class_id' => $announcement->class_id,
                'class_name' => $announcement->class->class_name ?? null,
                'belt_level' => $announcement->belt_level,
                'branch_id' => $announcement->branch_id,
                'branch_name' => $announcement->branch->name ?? null,
                'channel' => $channels,
                'publish_date' => $announcement->publish_date ? $announcement->publish_date->format('Y-m-d') : null,
                'expire_date' => $announcement->expire_date ? $announcement->expire_date->format('Y-m-d') : null,
                'created_by_user_id' => $announcement->created_by_user_id,
                'creator_name' => $announcement->creator ? $announcement->creator->fname . ' ' . $announcement->creator->lname : 'Unknown',
                'is_active' => Carbon::now()->lte($announcement->expire_date),
                'is_expired' => Carbon::now()->gt($announcement->expire_date),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to load announcement: ' . $e->getMessage());
            return response()->json(['error' => 'Announcement not found'], 404);
        }
    }

    /**
     * Update the specified announcement.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $announcement = Announcement::findOrFail($id);

            // Log the incoming request for debugging
            Log::info('Announcement update request:', $request->all());

            $validated = $request->validate([
                'title' => 'required|string|max:200',
                'message' => 'required|string',
                'target_type' => 'required|in:all,class,belt,branch',
                'class_id' => 'nullable|exists:classes,id',
                'belt_level' => 'nullable|string',
                'branch_id' => 'nullable|exists:branches,id',
                'channel' => 'required|array',
                'channel.*' => 'in:App,SMS,Email',
                'expire_date' => 'required|date|after_or_equal:today',
            ]);

            // Additional conditional validation
            if ($request->target_type === 'class' && !$request->filled('class_id')) {
                return redirect()->back()
                    ->with('error', 'Please select a class when targeting by Class.')
                    ->withInput();
            }

            if ($request->target_type === 'branch' && !$request->filled('branch_id')) {
                return redirect()->back()
                    ->with('error', 'Please select a branch when targeting by Branch.')
                    ->withInput();
            }

            if ($request->target_type === 'belt' && !$request->filled('belt_level')) {
                return redirect()->back()
                    ->with('error', 'Please select a belt level when targeting by Belt.')
                    ->withInput();
            }

            // Convert channel array to comma-separated string
            $channelString = implode(',', $request->channel);

            // Prepare data for update
            $data = [
                'target_type' => $request->target_type,
                'title' => $request->title,
                'message' => $request->message,
                'channel' => $channelString,
                'expire_date' => $request->expire_date,
            ];

            // Add conditional fields based on target type
            if ($request->target_type === 'class') {
                $data['class_id'] = $request->class_id;
                $data['belt_level'] = null;
                $data['branch_id'] = null;
            } elseif ($request->target_type === 'belt') {
                $data['belt_level'] = $request->belt_level;
                $data['class_id'] = null;
                $data['branch_id'] = null;
            } elseif ($request->target_type === 'branch') {
                $data['branch_id'] = $request->branch_id;
                $data['class_id'] = null;
                $data['belt_level'] = null;
            } else { // all
                $data['class_id'] = null;
                $data['belt_level'] = null;
                $data['branch_id'] = null;
            }

            // Log the data being updated
            Log::info('Announcement data to update:', $data);

            // Update announcement
            $announcement->update($data);

            DB::commit();

            return redirect()->route('announcements.index')
                ->with('success', 'Announcement updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            $firstError = collect($e->errors())->flatten()->first();
            
            Log::error('Validation failed: ' . $firstError);
            
            return redirect()->back()
                ->with('error', 'Validation failed: ' . $firstError)
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update announcement: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update announcement. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $announcement = Announcement::findOrFail($id);
            $announcement->delete();

            DB::commit();

            return redirect()->route('announcements.index')
                ->with('success', 'Announcement deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete announcement: ' . $e->getMessage());
            return redirect()->route('announcements.index')
                ->with('error', 'Failed to delete announcement.');
        }
    }
}