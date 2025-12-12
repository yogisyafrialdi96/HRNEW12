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
        Schema::create('atasan_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('atasan_id');
            $table->tinyInteger('level')->comment('Level approval: 1, 2, 3, dst');
            $table->boolean('is_active')->default(true)->comment('Status aktif untuk soft delete');
            $table->date('effective_from')->nullable()->comment('Tanggal mulai berlaku');
            $table->date('effective_until')->nullable()->comment('Tanggal selesai berlaku (untuk temporary)');
            $table->text('notes')->nullable()->comment('Catatan tambahan, misal: delegasi karena cuti');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('atasan_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('created_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->foreign('updated_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Indexes untuk performa query
            $table->index(['user_id', 'is_active']);
            $table->index(['atasan_id', 'is_active']);
            $table->index(['user_id', 'level', 'is_active']);
        });

        // Tambahan: Table untuk log perubahan approval (audit trail)
        Schema::create('atasan_user_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atasan_user_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('atasan_id');
            $table->tinyInteger('level');
            $table->enum('action', ['created', 'updated', 'deactivated', 'deleted']);
            $table->unsignedBigInteger('changed_by')->nullable()->comment('User ID yang melakukan perubahan');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->foreign('changed_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            $table->index('user_id');
            $table->index('atasan_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atasan_user_history');
        Schema::dropIfExists('atasan_user');
    }
};