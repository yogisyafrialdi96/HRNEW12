<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jabatans extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_jabatans';

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
        'status',
        'created_by',
        'updated_by',
    ];
}
