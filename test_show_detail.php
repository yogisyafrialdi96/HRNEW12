<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Livewire\Admin\Cuti\CutiPengajuanIndex;
use App\Models\IzinCuti\CutiPengajuan;

// Create component instance
$component = new CutiPengajuanIndex();

// Simulate calling showDetail
try {
    $component->showDetail(2);
    
    echo "✓ showDetail called successfully\n";
    echo "✓ showDetailModal: " . ($component->showDetailModal ? 'true' : 'false') . "\n";
    echo "✓ detailModel: " . ($component->detailModel ? 'Model loaded' : 'null') . "\n";
    
    if($component->detailModel) {
        echo "✓ Model ID: " . $component->detailModel->id . "\n";
        echo "✓ Model nomor_cuti: " . $component->detailModel->nomor_cuti . "\n";
        echo "✓ Relations loaded:\n";
        echo "  - approval: " . $component->detailModel->approval->count() . " items\n";
        echo "  - approvalHistories: " . $component->detailModel->approvalHistories->count() . " items\n";
        echo "  - user: " . ($component->detailModel->user ? 'loaded' : 'null') . "\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "✗ File: " . $e->getFile() . "\n";
    echo "✗ Line: " . $e->getLine() . "\n";
}
