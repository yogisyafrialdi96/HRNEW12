<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = "provinsi";

    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function kabupaten() 
    { 
        return $this->hasMany(Kabupaten::class, 'provinsi_id'); 
    }
}
