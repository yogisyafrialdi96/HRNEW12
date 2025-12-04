<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Add description column after 'guard_name' if it doesn't exist
            if (!Schema::hasColumn('permissions', 'description')) {
                $table->text('description')->nullable()->after('guard_name');
            }
        });

        Schema::table('roles', function (Blueprint $table) {
            // Add description column after 'guard_name' if it doesn't exist
            if (!Schema::hasColumn('roles', 'description')) {
                $table->text('description')->nullable()->after('guard_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'description')) {
                $table->dropColumn('description');
            }
        });

        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
