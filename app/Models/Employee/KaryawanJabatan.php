<?php

namespace App\Models\Employee;

use App\Models\Master\Departments;
use App\Models\Master\Jabatans;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\Units;
use App\Models\User;

class KaryawanJabatan extends Model
{
    use SoftDeletes;

    protected $table = 'karyawan_jabatan';
    
    protected $fillable = [
        'karyawan_id',
        'department_id',
        'unit_id',
        'jabatan_id',
        'hub_kerja',
        'tgl_mulai',
        'tgl_selesai',
        'keterangan',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function karyawan()
    {
        return $this->belongsTo(\App\Models\Employee\Karyawan::class, 'karyawan_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function unit()
    {
        return $this->belongsTo(Units::class, 'unit_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatans::class, 'jabatan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByHubunganKerja($query, $hubKerja)
    {
        return $query->where('hub_kerja', $hubKerja);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_active', true)
                     ->whereNull('tgl_selesai');
    }

    // Accessor
    public function getIsPjsAttribute()
    {
        return $this->hub_kerja === 'PJS';
    }

    public function getDurationAttribute()
    {
        if (!$this->tgl_mulai) {
            return null;
        }

        $endDate = $this->tgl_selesai ?? now();
        return $this->tgl_mulai->diffInDays($endDate);
    }

    public function getDurationInYearsAttribute()
    {
        if (!$this->tgl_mulai) {
            return null;
        }

        $endDate = $this->tgl_selesai ?? now();
        return $this->tgl_mulai->diffInYears($endDate);
    }
    
}