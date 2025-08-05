<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = "kecamatan";

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function desa() 
    { 
        return $this->hasMany(Desa::class, 'kecamatan_id'); 
    }
}
