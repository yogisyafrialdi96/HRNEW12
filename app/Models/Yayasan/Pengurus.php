<?php

namespace App\Models\Yayasan;

use App\Models\Master\Departments;
use App\Models\Master\Jabatans;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Pengurus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengurus';

    protected $fillable = [
        'user_id',
        'department_id',
        'jabatan_id',
        'nama_pengurus',
        'inisial',
        'hp',
        'jenis_kelamin',
        'gelar_depan',
        'gelar_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'foto',
        'ttd',
        'tanggal_masuk',
        'tanggal_keluar',
        'is_active',
        'posisi',
    ];


    public function getFotoUrlAttribute()
    {
        return $this->foto ? Storage::url($this->foto) : null;
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatans::class, 'jabatan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
