<?php

namespace App\Livewire\Admin;

use App\Models\Employee\Karyawan;
use App\Models\Employee\KaryawanKontrak;
use App\Models\Employee\KaryawanJabatan;
use App\Models\Employee\KaryawanPendidikan;
use App\Models\Master\Kontrak;
use App\Models\Master\StatusPegawai;
use App\Models\Master\EducationLevel;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function getEmployeeStats()
    {
        $totalEmployees = Karyawan::count();
        $activeEmployees = Karyawan::where('statuskaryawan_id', 1)->count();
        $inactiveEmployees = Karyawan::where('statuskaryawan_id', '!=', 1)->count();
        $pegawaiCount = Karyawan::where('jenis_karyawan', 'pegawai')->count();
        $guruCount = Karyawan::where('jenis_karyawan', 'guru')->count();
        
        // Calculate average age
        $averageAge = 0;
        $employees = Karyawan::whereNotNull('tanggal_lahir')->get();
        if ($employees->count() > 0) {
            $totalAge = $employees->sum(function ($employee) {
                return Carbon::parse($employee->tanggal_lahir)->age;
            });
            $averageAge = round($totalAge / $employees->count(), 1);
        }

        return [
            'total_employees' => $totalEmployees,
            'active_employees' => $activeEmployees,
            'inactive_employees' => $inactiveEmployees,
            'pegawai_count' => $pegawaiCount,
            'guru_count' => $guruCount,
            'average_age' => $averageAge,
        ];
    }

    public function getActiveEmployeesByGender()
    {
        $genderStats = Karyawan::where('statuskaryawan_id', 1)
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get()
            ->map(function ($item) {
                return [
                    'gender' => $item->gender === 'L' ? 'laki-laki' : 'perempuan',
                    'count' => $item->total
                ];
            })
            ->toArray();

        return $genderStats;
    }

    public function getActiveEmployeesByContractType()
    {
        // Get all contract types
        $allContracts = Kontrak::orderBy('nama_kontrak')->get();
        
        // Get active contracts with their counts
        $activeContracts = KaryawanKontrak::where('status', 'aktif')
            ->select('kontrak_id', DB::raw('count(*) as total'))
            ->groupBy('kontrak_id')
            ->get()
            ->keyBy('kontrak_id')
            ->toArray();
        
        // Map all contracts with their active counts
        $contractStats = $allContracts->map(function ($kontrak) use ($activeContracts) {
            $count = isset($activeContracts[$kontrak->id]) ? $activeContracts[$kontrak->id]['total'] : 0;
            return [
                'name' => $kontrak->nama_kontrak,
                'total' => $count
            ];
        })->toArray();

        return $contractStats;
    }

    public function getEmployeesByStatus()
    {
        // Get all status types
        $allStatus = StatusPegawai::orderBy('id')->get();
        
        // Get employee counts per status
        $employeeByStatus = Karyawan::select('statuskaryawan_id', DB::raw('count(*) as total'))
            ->groupBy('statuskaryawan_id')
            ->get()
            ->keyBy('statuskaryawan_id')
            ->toArray();
        
        // Map all status with their employee counts and badge config
        $statusStats = $allStatus->map(function ($status) use ($employeeByStatus) {
            $count = isset($employeeByStatus[$status->id]) ? $employeeByStatus[$status->id]['total'] : 0;
            $badgeConfig = StatusPegawai::getBadgeConfig($status->id);
            return [
                'id' => $status->id,
                'name' => $badgeConfig['label'],
                'total' => $count,
                'color' => $badgeConfig['class']
            ];
        })->toArray();

        return $statusStats;
    }

    public function getEmployeesByDepartment()
    {
        $departmentStats = KaryawanJabatan::select('department_id', DB::raw('count(distinct karyawan_id) as total'))
            ->where('is_active', true)
            ->whereNotNull('department_id')
            ->whereHas('karyawan', function ($query) {
                $query->where('statuskaryawan_id', 1);
            })
            ->groupBy('department_id')
            ->with('department')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->department_id,
                    'name' => $item->department?->department ?? 'Unknown',
                    'total' => $item->total
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->toArray();

        return $departmentStats;
    }

    public function getEmployeesByUnit()
    {
        $unitStats = KaryawanJabatan::select('unit_id', DB::raw('count(distinct karyawan_id) as total'))
            ->where('is_active', true)
            ->whereNotNull('unit_id')
            ->whereHas('karyawan', function ($query) {
                $query->where('statuskaryawan_id', 1);
            })
            ->groupBy('unit_id')
            ->with('unit')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->unit_id,
                    'name' => $item->unit?->unit ?? 'Unknown',
                    'total' => $item->total
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->toArray();

        return $unitStats;
    }

    public function getEmployeesByEducation()
    {
        $educationStats = KaryawanPendidikan::select('education_level_id', DB::raw('count(distinct karyawan_id) as total'))
            ->whereHas('karyawan', function ($query) {
                $query->where('statuskaryawan_id', 1);
            })
            ->where('status', 'completed')
            ->groupBy('education_level_id')
            ->with('educationLevel')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->education_level_id,
                    'name' => $item->educationLevel?->level_name ?? 'Unknown',
                    'total' => $item->total
                ];
            })
            ->sortBy('name')
            ->values()
            ->toArray();

        return $educationStats;
    }

    public function getEmployeesByWorkRelationship()
    {
        $workRelationshipStats = KaryawanJabatan::select('hub_kerja', DB::raw('count(distinct karyawan_id) as total'))
            ->where('is_active', true)
            ->whereNotNull('hub_kerja')
            ->whereHas('karyawan', function ($query) {
                $query->where('statuskaryawan_id', 1);
            })
            ->groupBy('hub_kerja')
            ->get()
            ->map(function ($item) {
                // Map hub_kerja values to readable names
                $hubKerjaMap = [
                    'TETAP' => 'Tetap',
                    'KONTRAK' => 'Kontrak',
                    'PJS' => 'Pejabat Sementara',
                    'MAGANG' => 'Magang',
                    'HONORER' => 'Honorer',
                    'OUTSOURCING' => 'Outsourcing',
                ];
                
                return [
                    'id' => $item->hub_kerja,
                    'name' => $hubKerjaMap[$item->hub_kerja] ?? $item->hub_kerja,
                    'total' => $item->total
                ];
            })
            ->sortByDesc('total')
            ->values()
            ->toArray();

        return $workRelationshipStats;
    }

    public function render()
    {
        $stats = $this->getEmployeeStats();
        $genderStats = $this->getActiveEmployeesByGender();
        $contractStats = $this->getActiveEmployeesByContractType();
        $statusStats = $this->getEmployeesByStatus();
        $departmentStats = $this->getEmployeesByDepartment();
        $unitStats = $this->getEmployeesByUnit();
        $educationStats = $this->getEmployeesByEducation();
        $workRelationshipStats = $this->getEmployeesByWorkRelationship();
        
        // Prepare chart data for contract types
        $contractLabels = array_column($contractStats, 'name');
        $contractData = array_column($contractStats, 'total');
        
        // Prepare chart data for status
        $statusLabels = array_column($statusStats, 'name');
        $statusData = array_column($statusStats, 'total');
        $totalEmployees = array_sum($statusData);
        $statusPercentages = array_map(function ($count) use ($totalEmployees) {
            return $totalEmployees > 0 ? round(($count / $totalEmployees) * 100, 1) : 0;
        }, $statusData);
        
        // Prepare chart data for departments
        $departmentLabels = array_column($departmentStats, 'name');
        $departmentData = array_column($departmentStats, 'total');
        
        // Prepare chart data for units
        $unitLabels = array_column($unitStats, 'name');
        $unitData = array_column($unitStats, 'total');
        
        // Prepare chart data for education
        $educationLabels = array_column($educationStats, 'name');
        $educationData = array_column($educationStats, 'total');
        
        // Prepare chart data for work relationship
        $workRelationshipLabels = array_column($workRelationshipStats, 'name');
        $workRelationshipData = array_column($workRelationshipStats, 'total');
        
        // Prepare chart colors
        $contractColors = [
            '#3B82F6', // blue
            '#10B981', // green
            '#F59E0B', // amber
            '#EF4444', // red
            '#8B5CF6', // purple
            '#EC4899', // pink
            '#14B8A6', // teal
            '#F97316', // orange
        ];
        
        $statusColors = [
            '#10B981', // green - Aktif
            '#EF4444', // red - Resign
            '#9CA3AF', // gray - Pensiun
            '#64748B', // slate - Pensiun Dini
            '#FBBF24', // amber - LWP
            '#3B82F6', // blue - Tugas Belajar
            '#F97316', // orange - Habis Kontrak
            '#1F2937', // dark gray - Meninggal
        ];
        
        $departmentColors = array_slice($contractColors, 0, count($departmentLabels));
        $unitColors = array_slice($contractColors, 0, count($unitLabels));
        $educationColors = array_slice($contractColors, 0, count($educationLabels));
        $workRelationshipColors = array_slice($contractColors, 0, count($workRelationshipLabels));
        
        $contractChartColors = array_slice($contractColors, 0, count($contractLabels));
        $statusChartColors = array_slice($statusColors, 0, count($statusLabels));
        
        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'genderStats' => $genderStats,
            'contractStats' => $contractStats,
            'statusStats' => $statusStats,
            'departmentStats' => $departmentStats,
            'unitStats' => $unitStats,
            'educationStats' => $educationStats,
            'workRelationshipStats' => $workRelationshipStats,
            'contractLabels' => json_encode($contractLabels),
            'contractData' => json_encode($contractData),
            'contractColors' => json_encode($contractChartColors),
            'statusLabels' => json_encode($statusLabels),
            'statusData' => json_encode($statusData),
            'statusPercentages' => json_encode($statusPercentages),
            'statusColors' => json_encode($statusChartColors),
            'departmentLabels' => json_encode($departmentLabels),
            'departmentData' => json_encode($departmentData),
            'departmentColors' => json_encode($departmentColors),
            'unitLabels' => json_encode($unitLabels),
            'unitData' => json_encode($unitData),
            'unitColors' => json_encode($unitColors),
            'educationLabels' => json_encode($educationLabels),
            'educationData' => json_encode($educationData),
            'educationColors' => json_encode($educationColors),
            'workRelationshipLabels' => json_encode($workRelationshipLabels),
            'workRelationshipData' => json_encode($workRelationshipData),
            'workRelationshipColors' => json_encode($workRelationshipColors),
        ]);
    }
}
