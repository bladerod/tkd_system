<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Usage in route: middleware('permission:classes,view')
     */
    public function handle(Request $request, Closure $next, string $module, string $action = 'view'): Response
    {
        $user = $request->user();

        // Admin bypasses all permission checks
        if ($user->isAdmin()) {
            return $next($request);
        }

        $hasPermission = false;

        // Check the requested action against the User model's helper methods
        switch ($action) {
            case 'view':
                $hasPermission = $user->canViews1($module);
                break;
            case 'create':
                $hasPermission = $user->canCreate($module);
                break;
            case 'edit':
                $hasPermission = $user->canEdit($module);
                break;
            case 'delete':
                $hasPermission = $user->canDelete($module);
                break;
        }

        // If they don't have permission, block them
        if (!$hasPermission) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You do not have permission to perform this action.'], 403);
            }
            abort(403, "You do not have permission to {$action} the {$module} module.");
        }

        return $next($request);
    }
}