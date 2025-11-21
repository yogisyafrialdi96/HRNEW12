<?php

namespace App\Livewire\Admin\Karyawan\Masakerja;

use App\Models\Employee\Karyawan;
use App\Models\Master\Units;
use App\Models\Master\StatusPegawai;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Index extends Component
{
    use WithPagination;

    // Properties for search and filter
    public $search = '';
    public $unitFilter = '';
    public $milestoneFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

    // Modal properties
    public $showModal = false;
    public $isEdit = false;



    #[Url]
    public string $query = '';

    // Set default URL param supaya reset saat refresh
    #[Url(except: 'id')]
    public string $sortField = 'id';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public bool $showDeleted = false;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUnitFilter()
    {
        $this->resetPage();
    }

    public function updatingMilestoneFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function getAvailableUnitsProperty()
    {
        return Units::whereHas('department', function ($q) {
            $q->where('department', '!=', 'Yayasan');
        })->orderBy('unit')->get();
    }

    public function getAvailableStatusesProperty()
    {
        return StatusPegawai::orderBy('nama_status')->get();
    }

    public function render()
    {
        $query = Karyawan::with([
            'user',
            'activeJabatan' => function ($q) {
                $q->with(['jabatan', 'unit']); // Load jabatan dan unit dari activeJabatan
            },
            'contracts' => function ($q) {
                // Load kontrak pertama (oldest) untuk mendapatkan tglmulai_kontrak
                $q->oldest('tglmulai_kontrak');
            }
        ]);

        // filter by unit (dari activeJabatan)
        $query->when($this->unitFilter !== '', function ($q) {
            $q->whereHas('activeJabatan', function ($sub) {
                $sub->where('unit_id', $this->unitFilter);
            });
        });

        // filter by milestone (masa kerja tahun tertentu)
        $query->when($this->milestoneFilter !== '', function ($q) {
            $milestoneYear = (int)$this->milestoneFilter;
            $q->whereHas('contracts', function ($subQuery) use ($milestoneYear) {
                // Check if any contract has the milestone year of service
                $subQuery->whereRaw("YEAR(FROM_DAYS(DATEDIFF(CURDATE(), tglmulai_kontrak))) = ?", [$milestoneYear]);
            });
        });

        // Filter by status karyawan (dari StatusPegawai)
        $query->when($this->statusFilter !== '', function ($q) {
            $q->where('statuskaryawan_id', $this->statusFilter);
        });

        // pencarian
        $query->when($this->search, function ($q) {
            $search = '%' . $this->search . '%';
            $q->where(function ($q) use ($search) {
                $q->where('full_name', 'like', $search)
                    ->orWhere('nip', 'like', $search)
                    ->orWhereHas('contracts', function ($subQuery) use ($search) {
                        $subQuery->where('tglmulai_kontrak', 'like', $search);
                    });
            });
        });

        // urutkan & paginasi
        $karyawans = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Tambahkan data milestone untuk setiap karyawan
        $karyawans->getCollection()->transform(function ($employee) {
            $employee->milestones = $employee->calculateMilestones();
            $employee->current_duration = $employee->getCurrentWorkDuration();
            $employee->upcoming_milestone = $employee->getUpcomingSoonMilestone();
            $employee->retirement_info = $employee->getRetirementInfo();
            return $employee;
        });

        return view('livewire.admin.karyawan.masakerja.index', compact('karyawans'));
    }
}
