<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$permissions
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user has any of the permissions (pipe-separated or comma-separated)
        foreach ($permissions as $permission) {
            // Support both pipe and comma separation: 'users.view|users.create' or 'users.view,users.create'
            $permissionList = array_map('trim', preg_split('/[\|,]/', $permission));
            
            $hasAnyPermission = false;
            foreach ($permissionList as $perm) {
                if ($user->hasPermissionTo($perm)) {
                    $hasAnyPermission = true;
                    break;
                }
            }
            
            if ($hasAnyPermission) {
                return $next($request);
            }
        }

        // If no permission matches, abort with 403
        abort(403, 'Unauthorized. You do not have the required permission.');
    }
}
