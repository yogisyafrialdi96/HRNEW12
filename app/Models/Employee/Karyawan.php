<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'karyawan';

    protected $fillable = [
        'user_id',
        'nip',
        'inisial',
        'full_name',
        'panggilan',
        'hp',
        'whatsapp',
        'gender',
        'gelar_depan',
        'gelar_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'pndk_akhir',
        'agama',
        'status_kawin',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'alamat_ktp',
        'rt_ktp',
        'rw_ktp',
        'prov_id',
        'kab_id',
        'kec_id',
        'desa_id',
        'domisili_sama_ktp',
        'alamat_dom',
        'rt_dom',
        'rw_dom',
        'provdom_id',
        'kabdom_id',
        'kecdom_id',
        'desdom_id',
        'nik',
        'nkk',
        'foto',
        'ttd',
        'statuskaryawan_id',
        'statuskawin_id',
        'golongan_id',
        'npwp',
        'tgl_masuk',
        'tgl_karyawan_tetap',
        'tgl_berhenti',
        'jenis_karyawan',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function contracts(): HasMany
    {
        return $this->hasMany(KaryawanKontrak::class);
    }

    public function activeContract(): HasMany
    {
        return $this->hasMany(KaryawanKontrak::class)->where('status', 'active');
    }

    public function currentContract()
    {
        return $this->contracts()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->first();
    }
}
