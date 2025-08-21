<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // public static function generateCode(): string
    // {
    //     $prefix = 'UNT-';
        
    //     // Cari kode terakhir
    //     $lastMapel = self::where('kode_mapel', 'like', $prefix . '%')
    //         ->orderBy('kode_mapel', 'desc')
    //         ->first();

    //     if (!$lastMapel) {
    //         return $prefix . '001';
    //     }

    //     // Extract nomor dari kode terakhir
    //     $lastNumber = (int) substr($lastMapel->kode_mapel, strlen($prefix));
    //     $newNumber = $lastNumber + 1;

    //     return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    // }

    // /**
    //  * Boot method untuk auto generate kode
    //  */
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($mapel) {
    //         if (empty($mapel->kode_mapel)) {
    //             $mapel->kode_mapel = self::generateCode();
    //         }
    //     });
    // }

    /**
     * Relationship to Creator (User)
     */
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
