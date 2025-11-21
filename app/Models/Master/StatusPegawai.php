<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusPegawai extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_statuspegawai';

    protected $fillable = [
        'nama_status',
        'deskripsi',
        'created_by',
        'updated_by',
    ];

    /**
     * Relationship to Creator (User)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship to Updater (User)
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get badge configuration for status display
     */
    public static function getBadgeConfig($statusId)
    {
        $configs = [
            1 => ['label' => 'Aktif', 'class' => 'bg-green-100 text-green-800'],
            2 => ['label' => 'Resign', 'class' => 'bg-red-100 text-red-800'],
            3 => ['label' => 'Pensiun', 'class' => 'bg-gray-100 text-gray-800'],
            4 => ['label' => 'Pensiun Dini', 'class' => 'bg-slate-100 text-slate-800'],
            5 => ['label' => 'LWP', 'class' => 'bg-yellow-100 text-yellow-800'],
            6 => ['label' => 'Tugas Belajar', 'class' => 'bg-blue-100 text-blue-800'],
            7 => ['label' => 'Habis Kontrak', 'class' => 'bg-orange-100 text-orange-800'],
            8 => ['label' => 'Meninggal Dunia', 'class' => 'bg-slate-900 text-white'],
        ];

        return $configs[$statusId] ?? ['label' => 'Lainnya', 'class' => 'bg-gray-100 text-gray-800'];
    }
}
