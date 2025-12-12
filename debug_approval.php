<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;
use App\Models\Atasan\AtasanUser;
use App\Models\IzinCuti\CutiPengajuan;
use App\Models\IzinCuti\CutiApproval;

echo "=== USERS ===\n";
$users = User::select('id', 'name', 'email')->limit(10)->get();
foreach ($users as $u) {
    echo "{$u->id}: {$u->name} ({$u->email})\n";
}

echo "\n=== ATASAN_USER ===\n";
$atasanUsers = AtasanUser::with('user', 'atasan')->get();
foreach ($atasanUsers as $au) {
    echo "ID: {$au->id}, User: {$au->user->name} (ID: {$au->user_id}), Atasan: {$au->atasan->name} (ID: {$au->atasan_id}), Level: {$au->level}, Active: {$au->is_active}\n";
}

echo "\n=== CUTI_PENGAJUAN ===\n";
$cutis = CutiPengajuan::with('user')->get();
foreach ($cutis as $cuti) {
    echo "ID: {$cuti->id}, User: {$cuti->user->name} (ID: {$cuti->user_id}), Status: {$cuti->status}, Nomor: {$cuti->nomor_cuti}\n";
}

echo "\n=== CUTI_APPROVAL ===\n";
$approvalCount = CutiApproval::count();
echo "Total: {$approvalCount}\n";

if ($approvalCount > 0) {
    $approvals = CutiApproval::with('atasanUser.user', 'cutiPengajuan')->limit(5)->get();
    foreach ($approvals as $a) {
        echo "Pengajuan ID: {$a->cuti_pengajuan_id}, Level: {$a->level}, Status: {$a->status}, Atasan: {$a->atasanUser->user->name}\n";
    }
} else {
    echo "NO APPROVAL RECORDS! This means submit() was never called or failed silently.\n";
    
    // Check if there's any error in the database
    echo "\nChecking last cuti_pengajuan status:\n";
    $lastCuti = CutiPengajuan::latest()->first();
    if ($lastCuti) {
        echo "Last pengajuan status: {$lastCuti->status}\n";
        echo "Created by: {$lastCuti->created_by}\n";
        $creator = User::find($lastCuti->created_by);
        echo "Creator: {$creator->name} (ID: {$creator->id})\n";
        
        // Check if this user has atasan
        $atasanForCreator = AtasanUser::where('user_id', $lastCuti->created_by)->get();
        echo "Atasan count for creator: {$atasanForCreator->count()}\n";
        foreach ($atasanForCreator as $au) {
            echo "  - Level {$au->level}: Active={$au->is_active}\n";
        }
    }
}

