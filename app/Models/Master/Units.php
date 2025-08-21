<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Units extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'master_unit';

    protected $fillable = [
        'unit',
        'department_id',
        'kode_unit',
        'deskripsi',
        'kepala_unit',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function generateCode(): string
    {
        $prefix = 'UNT-';
        
        // Cari kode terakhir
        $lastUnit = self::where('kode_unit', 'like', $prefix . '%')
            ->orderBy('kode_unit', 'desc')
            ->first();

        if (!$lastUnit) {
            return $prefix . '001';
        }

        // Extract nomor dari kode terakhir
        $lastNumber = (int) substr($lastUnit->kode_unit, strlen($prefix));
        $newNumber = $lastNumber + 1;

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method untuk auto generate kode
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($unit) {
            if (empty($unit->kode_unit)) {
                $unit->kode_unit = self::generateCode();
            }
        });
    }

    /**
     * Relationship to Department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Departments::class, 'department_id');
    }

    /**
     * Relationship to Unit Head (User)
     */
    public function kepalaUnit(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepala_unit');
    }

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
