<?php

namespace App\Console\Commands;

use App\Models\Employee\Karyawan;
use Illuminate\Console\Command;

class UpdateRetiredEmployeesStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employees:update-retired-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update employee status to "Pensiun" if they have reached retirement age (56 years)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting employee retirement status update...');
        $this->newLine();

        try {
            // Call the static method to update all retired employees
            $stats = Karyawan::updateAllRetiredEmployees();

            // Display results
            $this->info('✓ Update Complete!');
            $this->newLine();
            $this->line('Results:');
            $this->info('  • Updated to Pensiun: ' . $stats['count_updated']);
            $this->info('  • Already Pensiun: ' . $stats['count_already_retired']);
            $this->warn('  • Errors: ' . $stats['count_errors']);
            $this->newLine();

            if ($stats['count_updated'] > 0) {
                $this->info('Successfully updated ' . $stats['count_updated'] . ' employee(s) to Pensiun status.');
            } else {
                $this->comment('No employees needed status update.');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
