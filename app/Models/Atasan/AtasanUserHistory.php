<?php

namespace App\Models\Atasan;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtasanUserHistory extends Model
{
    protected $table = 'atasan_user_history';

    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'atasan_user_id',
        'user_id',
        'atasan_id',
        'level',
        'action',
        'changed_by',
        'old_data',
        'new_data',
        'reason',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'level' => 'integer',
    ];

    public function atasanUser(): BelongsTo
    {
        return $this->belongsTo(AtasanUser::class, 'atasan_user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
