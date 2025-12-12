<?php
// Test script to check Laravel logs and modal functionality

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);

// Check if logs exist
$logFile = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    echo "=== LATEST LARAVEL LOGS ===\n\n";
    $logs = file_get_contents($logFile);
    $lines = explode("\n", trim($logs));
    
    // Show last 30 lines
    $lastLines = array_slice($lines, -30);
    foreach ($lastLines as $line) {
        if (!empty($line)) {
            echo $line . "\n";
        }
    }
    
    echo "\n\n=== CHECKING FOR MODAL-RELATED LOGS ===\n";
    foreach ($lines as $line) {
        if (strpos(strtolower($line), 'detail') !== false || 
            strpos(strtolower($line), 'modal') !== false ||
            strpos(strtolower($line), 'showdetail') !== false) {
            echo $line . "\n";
        }
    }
} else {
    echo "Log file not found at: $logFile\n";
}

// Check component properties
echo "\n\n=== COMPONENT CLASS CHECK ===\n";
$componentClass = 'App\Livewire\Admin\Cuti\CutiPengajuanIndex';
if (class_exists($componentClass)) {
    echo "✓ Component class exists: $componentClass\n";
    
    // Check if methods exist
    $reflection = new ReflectionClass($componentClass);
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    
    echo "\nPublic Methods:\n";
    foreach ($methods as $method) {
        if (in_array($method->getName(), ['showDetail', 'closeDetailModal', 'render'])) {
            echo "  ✓ {$method->getName()}\n";
        }
    }
} else {
    echo "✗ Component class not found\n";
}
?>
