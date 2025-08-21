<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departments extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_department';

    protected $fillable = [
        'company_id',
        'department',
        'kode_department',
        'deskripsi',
        'kepala_department',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function generateCode(): string
    {
        $prefix = 'DPT-';
        
        // Cari kode terakhir
        $lastDepartment = self::where('kode_department', 'like', $prefix . '%')
            ->orderBy('kode_department', 'desc')
            ->first();

        if (!$lastDepartment) {
            return $prefix . '001';
        }

        // Extract nomor dari kode terakhir
        $lastNumber = (int) substr($lastDepartment->kode_department, strlen($prefix));
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
            if (empty($department->kode_department)) {
                $department->kode_department = self::generateCode();
            }
        });
    }

    /**
     * Relationship to Company
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Companies::class, 'company_id');
    }

    public function unit()
    {
        return $this->hasMany(Units::class);
    }

    public function jabatan()
    {
        return $this->hasMany(Jabatans::class, 'department_id');
    }

    /**
     * Relationship to Department Head (User)
     */
    public function kepalaDepartment(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepala_department');
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
