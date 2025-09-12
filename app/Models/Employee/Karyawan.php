<?php

namespace App\Models\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->first();
    }

    public function getStatusBadgeAttribute()
    {
        $statusConfig = [
            1 => ['text' => 'Aktif', 'class' => 'bg-green-100 text-green-800'],
            2 => ['text' => 'Resign', 'class' => 'bg-red-100 text-red-800'],
            3 => ['text' => 'Pensiun Dini', 'class' => 'bg-gray-100 text-gray-800'],
            4 => ['text' => 'LWP', 'class' => 'bg-yellow-100 text-yellow-800'],
            5 => ['text' => 'Tugas Belajar', 'class' => 'bg-blue-100 text-blue-800'],
            6 => ['text' => 'Habis Kontrak', 'class' => 'bg-orange-100 text-orange-800'],
            7 => ['text' => 'Meninggal', 'class' => 'bg-black text-white']
        ];

        return $statusConfig[$this->statuskaryawan_id] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
    }

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
