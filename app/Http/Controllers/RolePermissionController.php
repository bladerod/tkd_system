<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    /**
     * Display the roles and permissions page
     */
    public function index()
    {
        // Define all modules in the system
        $modules = $this->getAllModules();
        
        // Define all roles (only admin and staff for system access)
        $roles = ['admin', 'staff'];
        
        // Get existing permissions
        $permissions = [];
        foreach ($roles as $role) {
            foreach ($modules as $module) {
                $perm = RolePermission::where('role', $role)
                    ->where('module', $module)
                    ->first();
                
                if ($perm) {
                    $permissions[$role][$module] = [
                        'can_view' => $perm->can_view,
                        'can_create' => $perm->can_create,
                        'can_edit' => $perm->can_edit,
                        'can_delete' => $perm->can_delete,
                    ];
                } else {
                    // Default permissions based on role
                    if ($role === 'admin') {
                        $permissions[$role][$module] = [
                            'can_view' => true,
                            'can_create' => true,
                            'can_edit' => true,
                            'can_delete' => true,
                        ];
                    } else {
                        $permissions[$role][$module] = [
                            'can_view' => false,
                            'can_create' => false,
                            'can_edit' => false,
                            'can_delete' => false,
                        ];
                    }
                }
            }
        }
        
        return view('rolespermission', compact('modules', 'roles', 'permissions'));
    }
    
    /**
     * Update roles and permissions
     */
    public function update(Request $request)
    {
        $request->validate([
            'permissions' => 'required|array',
        ]);
        
        $permissions = $request->input('permissions');
        
        DB::beginTransaction();
        
        try {
            foreach ($permissions as $role => $modules) {
                foreach ($modules as $module => $actions) {
                    RolePermission::updateOrCreate(
                        ['role' => $role, 'module' => $module],
                        [
                            'can_view' => isset($actions['can_view']) ? 1 : 0,
                            'can_create' => isset($actions['can_create']) ? 1 : 0,
                            'can_edit' => isset($actions['can_edit']) ? 1 : 0,
                            'can_delete' => isset($actions['can_delete']) ? 1 : 0,
                        ]
                    );
                }
            }
            
            DB::commit();
            
            return redirect()->route('settings.roles-permissions.index')
                ->with('success', 'Permissions updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update permissions: ' . $e->getMessage());
        }
    }
    
    /**
     * Reset permissions to default
     */
    public function reset()
    {
        DB::beginTransaction();
        
        try {
            // Delete all existing permissions
            RolePermission::where('role', 'staff')->delete();
            
            // Staff gets limited access
            $staffPermissions = $this->getStaffDefaultPermissions();
            
            foreach ($staffPermissions as $module => $perms) {
                RolePermission::updateOrCreate(
                    ['role' => 'staff', 'module' => $module],
                    [
                        'can_view' => $perms['can_view'],
                        'can_create' => $perms['can_create'],
                        'can_edit' => $perms['can_edit'],
                        'can_delete' => $perms['can_delete'],
                    ]
                );
            }
            
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Permissions reset to default successfully!']);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Get all modules in the system
     */
    private function getAllModules()
    {
        return [
            'dashboard',
            'students',
            'parents',
            'instructors',
            'classes',
            'branches',
            'attendance',
            'billing',
            'certificates',
            'competitions',
            'announcements',
            'chat',
            'reports',
            'users',
            'settings',
        ];
    }
    
    /**
     * Get default permissions for staff role
     */
    private function getStaffDefaultPermissions()
    {
        return [
            'dashboard' => ['can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            'students' => ['can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            'parents' => ['can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            'instructors' => ['can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            'classes' => ['can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            'branches' => ['can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            'attendance' => ['can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            'billing' => ['can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            'certificates' => ['can_view' => 1, 'can_create' => 1, 'can_edit' => 0, 'can_delete' => 0],
            'competitions' => ['can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            'announcements' => ['can_view' => 1, 'can_create' => 1, 'can_edit' => 1, 'can_delete' => 0],
            'chat' => ['can_view' => 1, 'can_create' => 1, 'can_edit' => 0, 'can_delete' => 0],
            'reports' => ['can_view' => 1, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            'users' => ['can_view' => 0, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
            'settings' => ['can_view' => 0, 'can_create' => 0, 'can_edit' => 0, 'can_delete' => 0],
        ];
    }
}