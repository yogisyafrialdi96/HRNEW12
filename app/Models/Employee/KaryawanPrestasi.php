<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanPrestasi extends Model
{
    use HasFactory;

    protected $table = 'karyawan_prestasi';

    protected $fillable = [
        'karyawan_id',
        'nama_prestasi',
        'tingkat',
        'peringkat',
        'kategori',
        'penyelenggara',
        'tanggal',
        'lokasi',
        'keterangan',
        'document_path',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    const TINGKAT_LOMBA = [
        'lokal' => 'Lokal',
        'regional' => 'Regional',
        'nasional' => 'Nasional',
        'internasional' => 'Internasional',
    ];

    const PERINGKAT_LOMBA = [
        'juara_1' => 'Juara 1',
        'juara_2' => 'Juara 2',
        'juara_3' => 'Juara 3',
        'harapan_1' => 'Harapan 1',
        'harapan_2' => 'Harapan 2',
        'harapan_3' => 'Harapan 3',
        'partisipasi' => 'Partisipasi',
        'nominasi' => 'Nominasi',
    ];

    const KATEGORI_LOMBA = [
        'individu' => 'Individu',
        'tim' => 'Tim',
        'organisasi' => 'Organisasi',
    ];

    public function getPeringkatBadgeAttribute()
    {
        $statusConfig = [
            'juara_1' => ['text' => 'Juara 1'],
            'juara_2' => ['text' => 'Juara 2'],
            'juara_3' => ['text' => 'Juara 3'],
            'harapan_1' => ['text' => 'Harapan 1'],
            'harapan_2' => ['text' => 'Harapan 2'],
            'harapan_3' => ['text' => 'Harapan 3'],
            'partisipasi' => ['text' => 'Partisipasi'],
            'nominasi' => ['text' => 'Nominasi'],
        ];

        return $statusConfig[$this->peringkat] ?? ['text' => 'Unknown'];
    }

    public function karyawan()
    {
        return $this->belongsTo(\App\Models\Employee\Karyawan::class, 'karyawan_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }


}
