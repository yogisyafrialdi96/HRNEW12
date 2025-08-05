<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Companies extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_companies';

    protected $fillable = [
        'nama_companies',
        'kode',
        'singkatan',
        'jenis_instansi',
        'npwp',
        'alamat',
        'telepon',
        'fax',
        'email',
        'website',
        'logo_path',
        'tax_id',
        'company_type',
        'established_date',
        'tgl_berdiri',
        'status',
        'keterangan',
        'created_by',
        'edited_by',
    ];

    public function departments()
    {
        return $this->hasMany(Departments::class);
    }
}
