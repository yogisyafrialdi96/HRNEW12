<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    protected $table = "kabupaten";

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function kecamatan() 
    { 
        return $this->hasMany(Kecamatan::class, 'kabupaten_id'); 
    }
}
