<?php

namespace App\Models\Employee;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'karyawan';

    protected $fillable = [
        'user_id',
        'nip',
        'inisial',
        'full_name',
        'panggilan',
        'hp',
        'whatsapp',
        'gender',
        'gelar_depan',
        'gelar_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'pndk_akhir',
        'agama',
        'status_kawin',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_phone',
        'alamat_ktp',
        'rt_ktp',
        'rw_ktp',
        'prov_id',
        'kab_id',
        'kec_id',
        'desa_id',
        'domisili_sama_ktp',
        'alamat_dom',
        'rt_dom',
        'rw_dom',
        'provdom_id',
        'kabdom_id',
        'kecdom_id',
        'desdom_id',
        'nik',
        'nkk',
        'foto',
        'ttd',
        'statuskaryawan_id',
        'statuskawin_id',
        'golongan_id',
        'npwp',
        'tgl_masuk',
        'tgl_karyawan_tetap',
        'tgl_berhenti',
        'jenis_karyawan',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(KaryawanKontrak::class);
    }

    public function activeContract(): HasMany
    {
        return $this->hasMany(KaryawanKontrak::class)->where('status', 'active');
    }

    public function statusPegawai(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\StatusPegawai::class, 'statuskaryawan_id');
    }

    public function activeJabatan()
    {
        return $this->hasOne(KaryawanJabatan::class, 'karyawan_id')
            ->where('is_active', true)
            ->latest('tgl_mulai');
    }

    public function currentContract()
    {
        return $this->contracts()
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->first();
    }

    public function getStatusBadgeAttribute()
    {
        $statusConfig = [
            1 => ['text' => 'Aktif', 'class' => 'bg-green-100 text-green-800'],
            2 => ['text' => 'Resign', 'class' => 'bg-red-100 text-red-800'],
            3 => ['text' => 'Pensiun Dini', 'class' => 'bg-gray-100 text-gray-800'],
            4 => ['text' => 'LWP', 'class' => 'bg-yellow-100 text-yellow-800'],
            5 => ['text' => 'Tugas Belajar', 'class' => 'bg-blue-100 text-blue-800'],
            6 => ['text' => 'Habis Kontrak', 'class' => 'bg-orange-100 text-orange-800'],
            7 => ['text' => 'Meninggal', 'class' => 'bg-black text-white']
        ];

        return $statusConfig[$this->statuskaryawan_id] ?? ['text' => 'Unknown', 'class' => 'bg-gray-100 text-gray-800'];
    }

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

    /**
     * Calculate work anniversary milestones for employee
     * Milestones: 5, 10, 15, 20, 25, 30, 35 years from first contract
     *
     * @return array|null Array of milestones with dates and status, or null if no contracts
     */
    public function calculateMilestones()
    {
        // Get first contract start date
        $startDate = null;
        if ($this->contracts && $this->contracts->count() > 0) {
            $startDate = Carbon::parse($this->contracts->first()->tglmulai_kontrak);
        } else {
            return null;
        }

        $now = Carbon::now();
        $milestones = [];

        // Calculate for each milestone year: 5, 10, 15, 20, 25, 30, 35
        foreach ([5, 10, 15, 20, 25, 30, 35] as $year) {
            $milestoneDate = $startDate->copy()->addYears($year);
            $daysUntil = $now->diffInDays($milestoneDate, false);

            // Determine status: achieved (past), upcoming-soon (<30 days), future
            if ($daysUntil < 0) {
                $status = 'achieved';
            } elseif ($daysUntil <= 30) {
                $status = 'upcoming-soon';
            } else {
                $status = 'future';
            }

            $milestones[$year] = [
                'year' => $year,
                'date' => $milestoneDate,
                'status' => $status,
                'daysUntil' => $daysUntil,
                'formatted_date' => $milestoneDate->translatedFormat('d M Y'),
            ];
        }

        return $milestones;
    }

    /**
     * Get milestone badge configuration based on status
     *
     * @param string $status The milestone status (achieved, upcoming-soon, future)
     * @return array Badge configuration with class and label
     */
    public static function getMilestoneBadgeConfig($status)
    {
        $badgeConfig = [
            'achieved' => [
                'class' => 'bg-green-100 text-green-800',
                'label' => 'Tercapai',
                'icon' => '✓'
            ],
            'upcoming-soon' => [
                'class' => 'bg-red-100 text-red-800',
                'label' => 'Segera',
                'icon' => '!'
            ],
            'future' => [
                'class' => 'bg-blue-100 text-blue-800',
                'label' => 'Mendatang',
                'icon' => '→'
            ]
        ];

        return $badgeConfig[$status] ?? ['class' => 'bg-gray-100 text-gray-800', 'label' => 'N/A', 'icon' => '?'];
    }

    /**
     * Calculate current work duration (masa kerja berjalan)
     * Returns years and months from first contract start date to now
     *
     * @return array|null Array with years and months, or null if no contracts
     */
    public function getCurrentWorkDuration()
    {
        if (!$this->contracts || $this->contracts->count() === 0) {
            return null;
        }

        $startDate = Carbon::parse($this->contracts->first()->tglmulai_kontrak);
        $now = Carbon::now();

        $years = $now->diffInYears($startDate);
        $months = $now->diffInMonths($startDate) % 12;
        $days = $now->diffInDays($startDate->copy()->addYears($years)->addMonths($months));

        return [
            'years' => $years,
            'months' => $months,
            'days' => $days,
            'total_days' => $now->diffInDays($startDate),
            'formatted' => sprintf('%d Tahun %d Bulan', $years, $months),
            'short' => sprintf('%d.%d Tahun', $years, $months),
        ];
    }

    /**
     * Check if any milestone is upcoming soon (within 30 days)
     * Returns the milestone year that is coming soon, or null
     *
     * @return int|null The milestone year that is upcoming soon, or null
     */
    public function getUpcomingSoonMilestone()
    {
        $milestones = $this->calculateMilestones();
        
        if (!$milestones) {
            return null;
        }

        foreach ($milestones as $year => $milestone) {
            if ($milestone['status'] === 'upcoming-soon') {
                return $year;
            }
        }

        return null;
    }

    /**
     * Calculate retirement information based on birth date + 56 years
     *
     * @return array|null Retirement information with dates and remaining time, or null if no birth date
     */
    public function getRetirementInfo()
    {
        // Check if tanggal_lahir exists
        if (!$this->tanggal_lahir) {
            return null;
        }

        $birthDate = Carbon::parse($this->tanggal_lahir);
        $retirementDate = $birthDate->copy()->addYears(56);
        $now = Carbon::now();

        // Check if already retired
        if ($now->greaterThan($retirementDate)) {
            return [
                'status' => 'retired',
                'retirement_date' => $retirementDate,
                'formatted_retirement_date' => $retirementDate->translatedFormat('d M Y'),
                'current_age' => $now->diffInYears($birthDate),
                'message' => 'Telah pensiun',
            ];
        }

        $yearsUntilRetirement = $retirementDate->diffInYears($now);
        $monthsUntilRetirement = $retirementDate->diffInMonths($now) % 12;
        $daysUntilRetirement = $retirementDate->diffInDays($now);

        $currentAge = $now->diffInYears($birthDate);

        return [
            'status' => 'active',
            'retirement_date' => $retirementDate,
            'formatted_retirement_date' => $retirementDate->translatedFormat('d M Y'),
            'current_age' => $currentAge,
            'years_remaining' => $yearsUntilRetirement,
            'months_remaining' => $monthsUntilRetirement,
            'days_remaining' => $daysUntilRetirement,
            'formatted' => sprintf('%d Tahun %d Bulan', $yearsUntilRetirement, $monthsUntilRetirement),
            'short' => sprintf('%d.%d Tahun', $yearsUntilRetirement, $monthsUntilRetirement),
            'message' => sprintf('Pensiun dalam %d tahun %d bulan', $yearsUntilRetirement, $monthsUntilRetirement),
        ];
    }

    /**
     * Check if a milestone date occurs before employee's retirement date
     *
     * @param Carbon $milestoneDate The milestone date to check
     * @return bool True if milestone is before retirement, false otherwise
     */
    public function isMilestoneBeforeRetirement($milestoneDate)
    {
        $retirementInfo = $this->getRetirementInfo();
        
        if (!$retirementInfo) {
            return true; // Show milestone if no retirement info
        }

        $retirementDate = $retirementInfo['retirement_date'];
        
        return $milestoneDate->isBefore($retirementDate);
    }
}
