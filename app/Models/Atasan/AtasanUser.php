<?php

namespace App\Models\Atasan;

use App\Models\Employee\Karyawan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

/**
 * Model AtasanUser
 * 
 * @property int $id
 * @property int $user_id
 * @property int $atasan_id
 * @property int $level
 * @property bool $is_active
 * @property string|null $effective_from
 * @property string|null $effective_until
 * @property string|null $notes
 */
class AtasanUser extends Model
{
    use SoftDeletes;

    protected $table = 'atasan_user';

    protected $fillable = [
        'user_id',
        'atasan_id',
        'level',
        'is_active',
        'effective_from',
        'effective_until',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_until' => 'date',
        'level' => 'integer',
    ];

    /**
     * Relasi ke User (karyawan)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User (atasan)
     */
    public function atasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    /**
     * Relasi ke Karyawan (jika ada)
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke Karyawan atasan (jika ada)
     */
    public function karyawanAtasan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'atasan_id', 'user_id');
    }

    /**
     * Relasi ke history
     */
    public function histories(): HasMany
    {
        return $this->hasMany(AtasanUserHistory::class, 'atasan_user_id');
    }

    /**
     * Relasi ke created_by user
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke updated_by user
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope: Hanya yang aktif
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by level
     */
    public function scopeLevel(Builder $query, int $level): Builder
    {
        return $query->where('level', $level);
    }

    /**
     * Scope: Yang masih berlaku (effective date)
     */
    public function scopeEffective(Builder $query, $date = null): Builder
    {
        $date = $date ?? now();
        
        return $query->where(function ($q) use ($date) {
            $q->whereNull('effective_from')
              ->orWhere('effective_from', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('effective_until')
              ->orWhere('effective_until', '>=', $date);
        });
    }

    /**
     * Check apakah approval masih berlaku
     */
    public function isEffective($date = null): bool
    {
        $date = $date ?? now();
        
        $fromCheck = !$this->effective_from || $this->effective_from <= $date;
        $untilCheck = !$this->effective_until || $this->effective_until >= $date;
        
        return $this->is_active && $fromCheck && $untilCheck;
    }

    /**
     * Boot method untuk auto-log history
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->created_by && auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (!$model->updated_by && auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        static::created(function ($model) {
            $model->logHistory('created');
        });

        static::updated(function ($model) {
            if ($model->isDirty('is_active') && !$model->is_active) {
                $model->logHistory('deactivated');
            } else {
                $model->logHistory('updated');
            }
        });

        static::deleted(function ($model) {
            $model->logHistory('deleted');
        });
    }

    /**
     * Log history perubahan
     */
    public function logHistory(string $action, string $reason = null)
    {
        AtasanUserHistory::create([
            'atasan_user_id' => $this->id,
            'user_id' => $this->user_id,
            'atasan_id' => $this->atasan_id,
            'level' => $this->level,
            'action' => $action,
            'changed_by' => auth()->id(),
            'old_data' => $action === 'created' ? null : $this->getOriginal(),
            'new_data' => $this->getAttributes(),
            'reason' => $reason,
        ]);
    }
}
