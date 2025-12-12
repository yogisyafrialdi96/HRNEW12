<?php

require 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('name', 'Betha Feriani')->first();
if ($user) {
    echo "User: " . $user->name . PHP_EOL;
    echo "Role: " . implode(', ', $user->roles->pluck('name')->toArray()) . PHP_EOL;
    echo "Has cuti.create: " . ($user->can('cuti.create') ? 'YES' : 'NO') . PHP_EOL;
    echo "Has cuti.view: " . ($user->can('cuti.view') ? 'YES' : 'NO') . PHP_EOL;
} else {
    echo "User Betha not found" . PHP_EOL;
}
