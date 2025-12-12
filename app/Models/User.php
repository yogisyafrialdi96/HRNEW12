<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Atasan\AtasanUser;
use App\Models\Employee\Karyawan;
use App\Models\Yayasan\Pengurus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function pengurus()
    {
        return $this->hasOne(Pengurus::class);
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }
}

trait HasApprovalHierarchy
{
    /**
     * Relasi ke atasan-atasan user
     */
    public function atasanList(): HasMany
    {
        return $this->hasMany(AtasanUser::class, 'user_id');
    }

    /**
     * Relasi ke atasan aktif saja
     */
    public function atasanActive(): HasMany
    {
        return $this->hasMany(AtasanUser::class, 'user_id')
                    ->active()
                    ->effective()
                    ->orderBy('level');
    }

    /**
     * Relasi sebagai atasan (orang yang jadi atasan user lain)
     */
    public function bawahanList(): HasMany
    {
        return $this->hasMany(AtasanUser::class, 'atasan_id');
    }

    /**
     * Relasi bawahan aktif
     */
    public function bawahanActive(): HasMany
    {
        return $this->hasMany(AtasanUser::class, 'atasan_id')
                    ->active()
                    ->effective();
    }

    /**
     * Relasi ke karyawan (jika ada table karyawan terpisah)
     */
    public function karyawan(): HasMany
    {
        return $this->hasOne(Karyawan::class, 'user_id');
    }

    /**
     * Get atasan by level
     */
    public function getAtasan(int $level = 1)
    {
        return $this->atasanActive()
                    ->where('level', $level)
                    ->first()?->atasan;
    }

    /**
     * Get semua atasan (User objects)
     */
    public function getAllAtasan()
    {
        return $this->atasanActive()
                    ->with('atasan')
                    ->get()
                    ->pluck('atasan')
                    ->filter();
    }

    /**
     * Get semua bawahan (User objects)
     */
    public function getAllBawahan()
    {
        return $this->bawahanActive()
                    ->with('user')
                    ->get()
                    ->pluck('user')
                    ->filter();
    }

    /**
     * Check apakah user ini atasan dari user lain
     */
    public function isAtasanOf(User $user, int $level = null): bool
    {
        $query = $user->atasanActive()->where('atasan_id', $this->id);
        
        if ($level) {
            $query->where('level', $level);
        }
        
        return $query->exists();
    }

    /**
     * Get approval chain untuk user ini
     */
    public function getApprovalChain(): array
    {
        return $this->atasanActive()
                    ->with(['atasan.karyawan.jabatan', 'atasan.karyawan.unit'])
                    ->get()
                    ->map(function ($item) {
                        $karyawan = $item->atasan->karyawan;
                        return [
                            'level' => $item->level,
                            'atasan_id' => $item->atasan_id,
                            'atasan_name' => $item->atasan->name,
                            'jabatan' => $karyawan?->jabatan?->nama_jabatan,
                            'unit' => $karyawan?->unit?->nama_unit,
                            'notes' => $item->notes,
                        ];
                    })
                    ->toArray();
    }
}

