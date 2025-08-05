<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Units extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_units';

    protected $fillable = [
        'unit',
        'department_id',
        'kode_unit',
        'deskripsi',
        'kepala_unit',
        'status',
        'created_by',
        'updated_by',
    ];
}
