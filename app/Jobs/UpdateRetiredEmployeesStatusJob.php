<?php

namespace App\Jobs;

use App\Models\Employee\Karyawan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateRetiredEmployeesStatusJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Starting UpdateRetiredEmployeesStatusJob...');

            $stats = Karyawan::updateAllRetiredEmployees();

            Log::info('UpdateRetiredEmployeesStatusJob completed', [
                'updated' => $stats['count_updated'],
                'already_retired' => $stats['count_already_retired'],
                'errors' => $stats['count_errors'],
            ]);
        } catch (\Exception $e) {
            Log::error('UpdateRetiredEmployeesStatusJob failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
