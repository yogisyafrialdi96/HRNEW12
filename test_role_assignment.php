<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

echo "=== Role Assignment Test ===\n\n";

// Check user
$user = User::first();
if ($user) {
    echo "User: " . $user->name . "\n";
    echo "User ID: " . $user->id . "\n";
    echo "User Model Class: " . get_class($user) . "\n";
    echo "Current Roles Count: " . $user->roles()->count() . "\n";
    echo "Model-has-roles count: " . DB::table('model_has_roles')->where('model_id', $user->id)->count() . "\n\n";

    // Check all roles
    echo "Available Roles:\n";
    Role::all()->each(function($role) {
        echo "- " . $role->id . ": " . $role->name . "\n";
    });

    // Try to assign a role
    echo "\nTrying to assign role...\n";
    $role = Role::first();
    if ($role) {
        echo "Assigning role: " . $role->name . "\n";
        try {
            $user->assignRole($role->name);
            echo "✓ Role assigned successfully\n";
            echo "User roles after assignment: " . $user->roles()->count() . "\n";
            echo "Model-has-roles count: " . DB::table('model_has_roles')->where('model_id', $user->id)->count() . "\n";
            
            // Show the actual record
            $record = DB::table('model_has_roles')->where('model_id', $user->id)->first();
            if ($record) {
                echo "\nRecord in model_has_roles:\n";
                echo json_encode($record, JSON_PRETTY_PRINT) . "\n";
            }
        } catch (\Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "No user found\n";
}
