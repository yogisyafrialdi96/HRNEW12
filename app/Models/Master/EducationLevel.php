<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;

    protected $table = 'master_educationlevel';

    protected $fillable = [
        'level_code',
        'level_name',
        'level_order',
        'is_formal',
        'minimum_years',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_formal' => 'boolean',
        'is_active' => 'boolean',
        'minimum_years' => 'integer',
        'level_order' => 'integer',
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFormal(Builder $query): Builder
    {
        return $query->where('is_formal', true);
    }

    public function scopeNonFormal(Builder $query): Builder
    {
        return $query->where('is_formal', false);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('level_order');
    }

    // // Relationships
    // public function educationHistories()
    // {
    //     return $this->hasMany(EducationHistory::class);
    // }

    // Accessors
    public function getFormattedNameAttribute(): string
    {
        return $this->level_code . ' - ' . $this->level_name;
    }

    // Static methods
    public static function getHighestLevel(): ?self
    {
        return self::active()->formal()->orderBy('level_order', 'desc')->first();
    }

    public static function getLowestLevel(): ?self
    {
        return self::active()->formal()->orderBy('level_order')->first();
    }

    public static function getFormalLevels()
    {
        return self::active()->formal()->ordered()->get();
    }

    public static function getNonFormalLevels()
    {
        return self::active()->nonFormal()->ordered()->get();
    }
}
