<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Master\Jabatans;
use App\Models\Master\Units;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AtasanUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Get users - try multiple email patterns
        $admin = User::role('super_admin')->first() 
            ?? User::where('email', 'admin@example.com')->first()
            ?? User::where('email', 'like', '%admin%')->first();
            
        $dewinta = User::where('email', 'dewinta@example.com')->first()
            ?? User::where('email', 'like', '%dewinta%')->first()
            ?? User::role('approval_manager')->first();
            
        $betha = User::where('email', 'betha@example.com')->first()
            ?? User::where('email', 'like', '%betha%')->first();
            
        $murni = User::where('email', 'murni@example.com')->first()
            ?? User::where('email', 'like', '%murni%')->first();

        if (!$admin) {
            // Fallback: get any super_admin or user with admin role
            $admin = User::role('super_admin')->orWhere('email', 'like', '%@%')->first();
        }

        if (!$dewinta) {
            // Fallback: get user with approval_manager role or any other user
            $dewinta = User::role('approval_manager')->first() ?? User::skip(1)->first();
        }

        if (!$admin || !$dewinta) {
            $this->command->warn('❌ Admin atau Dewinta user tidak ditemukan. Minimal harus ada 2 user.');
            $this->command->line('Available users:');
            User::select('id', 'name', 'email')->get()->each(function($user) {
                $this->command->line("   - {$user->id}: {$user->name} ({$user->email})");
            });
            return;
        }

        // ========================================
        // 1. SEED: atason_user (2-Level Approval)
        // ========================================
        $this->command->info('📍 Seeding atasan_user (2-Level Approval)...');

        $atasanData = [];

        // Betha: Level 1 → Admin, Level 2 → Dewinta
        if ($betha) {
            $atasanData[] = [
                'user_id' => $betha->id,
                'atasan_id' => $admin->id,
                'level' => 1,
                'is_active' => true,
                'effective_from' => now()->startOfYear(),
                'effective_until' => null,
                'notes' => 'Level 1 approver: Admin',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $atasanData[] = [
                'user_id' => $betha->id,
                'atasan_id' => $dewinta->id,
                'level' => 2,
                'is_active' => true,
                'effective_from' => now()->startOfYear(),
                'effective_until' => null,
                'notes' => 'Level 2 approver: Dewinta (HR Manager)',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $this->command->line('   ✅ Betha: Level 1 → Admin, Level 2 → Dewinta');
        }

        // Murni: Level 1 → Admin, Level 2 → Dewinta
        if ($murni) {
            $atasanData[] = [
                'user_id' => $murni->id,
                'atasan_id' => $admin->id,
                'level' => 1,
                'is_active' => true,
                'effective_from' => now()->startOfYear(),
                'effective_until' => null,
                'notes' => 'Level 1 approver: Admin',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $atasanData[] = [
                'user_id' => $murni->id,
                'atasan_id' => $dewinta->id,
                'level' => 2,
                'is_active' => true,
                'effective_from' => now()->startOfYear(),
                'effective_until' => null,
                'notes' => 'Level 2 approver: Dewinta (HR Manager)',
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $this->command->line('   ✅ Murni: Level 1 → Admin, Level 2 → Dewinta');
        }

        if (!empty($atasanData)) {
            DB::table('atasan_user')->insert($atasanData);
            $this->command->line('   ✅ Total: ' . count($atasanData) . ' records di atasan_user');
        }

        // ========================================
        // 2. SEED: atasan_user_history (Audit Trail)
        // ========================================
        $this->command->info('📍 Seeding atasan_user_history...');

        $historyData = [];
        $atasanUsers = DB::table('atasan_user')->get();

        foreach ($atasanUsers as $au) {
            $historyData[] = [
                'atasan_user_id' => $au->id,
                'user_id' => $au->user_id,
                'atasan_id' => $au->atasan_id,
                'level' => $au->level,
                'action' => 'created',
                'changed_by' => $admin->id,
                'old_data' => null,
                'new_data' => json_encode([
                    'level' => $au->level,
                    'is_active' => $au->is_active,
                ]),
                'reason' => 'Initial setup - 2 level approval',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($historyData)) {
            DB::table('atasan_user_history')->insert($historyData);
            $this->command->line('   ✅ ' . count($historyData) . ' records di atasan_user_history');
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('');
        $this->command->info('✅ AtasanUserSeeder selesai!');
        $this->command->info('');
        $this->command->line('📊 Summary:');
        $this->command->line('   • Approval Type: Multi-Level via AtasanUser');
        $this->command->line('   • atasan_user: ' . DB::table('atasan_user')->count() . ' records');
        $this->command->line('   • atasan_user_history: ' . DB::table('atasan_user_history')->count() . ' records');
    }
}
