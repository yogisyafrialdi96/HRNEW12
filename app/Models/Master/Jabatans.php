<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatans extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_jabatan';

    protected $fillable = [
        'department_id',
        'nama_jabatan',
        'kode_jabatan',
        'jenis_jabatan',
        'level_jabatan',
        'tugas_pokok',
        'requirements',
        'min_salary',
        'max_salary',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function generateCode(): string
    {
        $prefix = 'JBT-';
        
        // Cari kode terakhir
        $lastDepartment = self::where('kode_jabatan', 'like', $prefix . '%')
            ->orderBy('kode_jabatan', 'desc')
            ->first();

        if (!$lastDepartment) {
            return $prefix . '001';
        }

        // Extract nomor dari kode terakhir
        $lastNumber = (int) substr($lastDepartment->kode_jabatan, strlen($prefix));
        $newNumber = $lastNumber + 1;

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method untuk auto generate kode
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($department) {
            if (empty($department->kode_jabatan)) {
                $department->kode_jabatan = self::generateCode();
            }
        });
    }

    /**
     * Relationship to Department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

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
}
