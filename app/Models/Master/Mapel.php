<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mapel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_mapel';

    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
        'requirements',
        'tugas_pokok',
        'status',
        'created_by',
        'updated_by',
    ];
}
