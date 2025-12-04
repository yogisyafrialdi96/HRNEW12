<?php

/**
 * IMPLEMENTATION EXAMPLES - Permission Management
 * 
 * File ini menunjukkan berbagai cara menggunakan permission management 
 * dalam aplikasi HR Anda.
 */

// ============================================
// 1. CONTROLLER EXAMPLES
// ============================================

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * Check permission sebelum menampilkan resource
     */
    public function index(Request $request)
    {
        // Method 1: Direct check
        if (!auth()->user()->hasPermissionTo('users.view')) {
            abort(403, 'Unauthorized');
        }

        // Method 2: Using Gate
        if (!auth()->user()->can('users.view')) {
            abort(403, 'Unauthorized');
        }

        // Method 3: Using middleware di route (recommended)
        // See routes/web.php

        return view('users.index');
    }

    /**
     * Check permission sebelum create
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('users.create')) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        // Create user logic...
    }

    /**
     * Check multiple permissions
     */
    public function bulkActions(Request $request)
    {
        $permissions = ['users.edit', 'users.delete'];

        if (PermissionHelper::userHasAllPermissions(auth()->user(), $permissions)) {
            // Allow bulk operations
        }
    }

    /**
     * Get user permissions for response
     */
    public function getUserPermissions(User $user)
    {
        return response()->json([
            'permissions' => $user->getPermissionNames(),
            'roles' => $user->getRoleNames(),
            'available_modules' => PermissionHelper::getAvailableModules(),
        ]);
    }
}

// ============================================
// 2. LIVEWIRE COMPONENT EXAMPLES
// ============================================

namespace App\Livewire\Examples;

use Livewire\Component;
use App\Helpers\PermissionHelper;

class UserManagement extends Component
{
    public function delete($userId)
    {
        // Check permission
        if (!auth()->user()->hasPermissionTo('users.delete')) {
            $this->dispatch('error', 'You do not have permission to delete users');
            return;
        }

        // Delete user...
    }

    public function export()
    {
        // Check permission
        if (!auth()->user()->hasPermissionTo('users.export')) {
            $this->dispatch('error', 'Export not allowed');
            return;
        }

        // Export logic...
    }

    public function render()
    {
        return view('livewire.users.management', [
            'canEdit' => auth()->user()->hasPermissionTo('users.edit'),
            'canDelete' => auth()->user()->hasPermissionTo('users.delete'),
            'canExport' => auth()->user()->hasPermissionTo('users.export'),
        ]);
    }
}

// ============================================
// 3. BLADE TEMPLATE EXAMPLES
// ============================================

/*
<!-- Show button only if user has permission -->
@hasPermission('users.create')
    <button class="btn btn-primary" wire:click="openCreateModal">
        <i class="fas fa-plus"></i> Add User
    </button>
@endhasPermission

<!-- Edit button with permission check -->
@hasPermission('users.edit')
    <button class="btn btn-warning" wire:click="edit({{ $user->id }})">
        <i class="fas fa-edit"></i> Edit
    </button>
@endhasPermission

<!-- Delete button with permission check -->
@hasPermission('users.delete')
    <button class="btn btn-danger" wire:click="delete({{ $user->id }})" 
            wire:confirm="Are you sure?">
        <i class="fas fa-trash"></i> Delete
    </button>
@endhasPermission

<!-- Show admin panel only for admins -->
@hasRole('admin|super_admin')
    <div class="admin-panel">
        <!-- Admin content -->
    </div>
@endhasRole

<!-- Show content if user has ANY of these permissions -->
@hasAnyPermission('users.edit', 'users.delete', 'users.export')
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle">More Actions</button>
        <div class="dropdown-menu">
            @hasPermission('users.edit')
                <a class="dropdown-item" href="#edit">Edit</a>
            @endhasPermission
            @hasPermission('users.delete')
                <a class="dropdown-item" href="#delete">Delete</a>
            @endhasPermission
            @hasPermission('users.export')
                <a class="dropdown-item" href="#export">Export</a>
            @endhasPermission
        </div>
    </div>
@endhasAnyPermission

<!-- Show content only if user has ALL these permissions -->
@hasAllPermissions('users.edit', 'users.approve')
    <button class="btn btn-success">Approve & Save</button>
@endhasAllPermissions
*/

// ============================================
// 4. ROUTE PROTECTION EXAMPLES
// ============================================

/*
// routes/web.php

// Single permission check
Route::get('/users', UserIndex::class)
    ->middleware('permission:users.view');

Route::post('/users', StoreUser::class)
    ->middleware('permission:users.create');

Route::put('/users/{user}', UpdateUser::class)
    ->middleware('permission:users.edit');

Route::delete('/users/{user}', DestroyUser::class)
    ->middleware('permission:users.delete');

// Multiple permissions (OR - any one is enough)
Route::post('/users/bulk-update', BulkUpdateUsers::class)
    ->middleware('permission:users.edit|users.export');

// Multiple permissions (AND - all required)
Route::post('/users/approve', ApproveUsers::class)
    ->middleware('permission:users.edit,users.approve');

// Role-based protection
Route::middleware('role:admin|super_admin')->group(function () {
    Route::get('/admin/settings', SettingsPage::class);
    Route::post('/admin/settings', UpdateSettings::class);
});

// Grouped routes with permission
Route::middleware('permission:employees.view')->group(function () {
    Route::get('/employees', EmployeeIndex::class)->name('employees.index');
    Route::get('/employees/{employee}', EmployeeShow::class)->name('employees.show');
});
*/

// ============================================
// 5. PERMISSION HELPER USAGE EXAMPLES
// ============================================

namespace App\Services;

use App\Helpers\PermissionHelper;
use App\Models\User;

class PermissionService
{
    /**
     * Get all permissions for a module
     */
    public function getModulePermissions($module)
    {
        return PermissionHelper::getPermissionsByModule($module);
    }

    /**
     * Check if user can perform action
     */
    public function canUserPerformAction(User $user, $action, $module)
    {
        $permission = PermissionHelper::generatePermissionName($module, $action);
        return $user->hasPermissionTo($permission);
    }

    /**
     * Get user's available actions in module
     */
    public function getUserModuleActions(User $user, $module)
    {
        $permissions = PermissionHelper::getPermissionsByModule($module);
        $actions = [];

        foreach ($permissions as $permission) {
            if ($user->hasPermissionTo($permission->name)) {
                $actions[] = $permission->name;
            }
        }

        return $actions;
    }

    /**
     * Assign permissions based on template role
     */
    public function setupNewUserPermissions(User $user, $templateRole)
    {
        $sourceRole = Role::where('name', $templateRole)->first();
        
        if ($sourceRole) {
            $user->assignRole($sourceRole);
            return true;
        }

        return false;
    }

    /**
     * Get permission statistics dashboard
     */
    public function getStatistics()
    {
        return PermissionHelper::getPermissionStatistics();
    }

    /**
     * Export all permissions for backup
     */
    public function exportPermissions()
    {
        return PermissionHelper::exportPermissions();
    }

    /**
     * Restore permissions from backup
     */
    public function importPermissions($data)
    {
        return PermissionHelper::importPermissions($data);
    }
}

// ============================================
// 6. CUSTOM MIDDLEWARE EXAMPLE
// ============================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckModulePermission extends Middleware
{
    /**
     * Check if user has permission for module
     * Usage: Route::middleware('check.module:users')?
     */
    public function handle(Request $request, Closure $next, $module)
    {
        $viewPermission = "{$module}.view";

        if (!auth()->check() || !auth()->user()->hasPermissionTo($viewPermission)) {
            abort(403, "You don't have access to {$module}");
        }

        return $next($request);
    }
}

// ============================================
// 7. SEEDING CUSTOM PERMISSIONS EXAMPLE
// ============================================

/*
// In PermissionSeeder.php or custom seeder

public function run(): void
{
    // Create custom permissions for reports module
    $reportPermissions = [
        'reports.view' => 'View reports',
        'reports.create' => 'Create custom report',
        'reports.edit' => 'Edit report',
        'reports.delete' => 'Delete report',
        'reports.export' => 'Export report to PDF/Excel',
        'reports.schedule' => 'Schedule report',
        'reports.share' => 'Share report',
    ];

    foreach ($reportPermissions as $name => $description) {
        Permission::firstOrCreate(
            ['name' => $name, 'guard_name' => 'web'],
            ['description' => $description]
        );
    }

    // Assign to roles
    $managerRole = Role::where('name', 'manager')->first();
    $managerRole->givePermissionTo([
        'reports.view',
        'reports.export',
    ]);

    $adminRole = Role::where('name', 'admin')->first();
    $adminRole->givePermissionTo($reportPermissions);
}
*/

// ============================================
// 8. API RESPONSE WITH PERMISSIONS
// ============================================

/*
// ApiController.php

public function getCurrentUser()
{
    $user = auth()->user();
    
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'roles' => $user->getRoleNames(),
        'permissions' => $user->getPermissionNames(),
        'modules_access' => [
            'users' => $user->hasPermissionTo('users.view'),
            'roles' => $user->hasPermissionTo('roles.view'),
            'employees' => $user->hasPermissionTo('employees.view'),
            'contracts' => $user->hasPermissionTo('contracts.view'),
            'reports' => $user->hasPermissionTo('reports.view'),
        ],
    ]);
}
*/

// ============================================
// 9. BULK PERMISSION OPERATIONS
// ============================================

namespace App\Jobs;

use App\Models\Role;
use App\Helpers\PermissionHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncRolePermissions implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $sourceRoleId,
        public $targetRoles
    ) {}

    public function handle()
    {
        foreach ($this->targetRoles as $targetRoleId) {
            PermissionHelper::copyPermissionsFromRole(
                $this->sourceRoleId,
                $targetRoleId
            );
        }
    }
}

// ============================================
// 10. CACHE MANAGEMENT
// ============================================

/*
// Clear permission cache when permissions change

namespace App\Observers;

use Spatie\Permission\Models\Permission;

class PermissionObserver
{
    public function saved(Permission $permission)
    {
        // Clear cache after permission update
        app()['cache']->forget('spatie.permission.cache');
    }

    public function deleted(Permission $permission)
    {
        // Clear cache after permission delete
        app()['cache']->forget('spatie.permission.cache');
    }
}

// Register observer in AppServiceProvider
Permission::observe(PermissionObserver::class);
*/
