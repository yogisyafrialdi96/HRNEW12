<?php

namespace App\Livewire\Admin\Atasan;

use App\Models\Atasan\AtasanUser;
use App\Models\Atasan\AtasanUserHistory;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AtasanUserIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public ?int $filterLevel = null;
    public bool $showModal = false;
    public bool $isEdit = false;
    public ?AtasanUser $editingModel = null;
    public array $selectedIds = [];
    public bool $selectAll = false;
    public bool $confirmingDelete = false;
    public ?AtasanUser $modelToDelete = null;
    public array $selectedIdsToDelete = [];

    // History Modal
    public bool $showHistoryModal = false;
    public ?AtasanUser $historyModel = null;
    public array $histories = [];

    // Form fields
    public ?int $user_id = null;
    public ?int $atasan_id = null;
    public ?int $level = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public bool $is_active = true;
    public ?string $notes = null;

    public function rules()
    {
        $userId = $this->isEdit && $this->editingModel ? $this->editingModel->id : 'NULL';
        
        return [
            'user_id' => 'required|exists:users,id|unique:atasan_user,user_id,' . $userId . ',id,level,' . $this->level,
            'atasan_id' => 'required|exists:users,id|different:user_id',
            'level' => 'required|integer|between:1,4',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
        ];
    }

    #[\Livewire\Attributes\Computed]
    public function atasanUsers()
    {
        return AtasanUser::with(['user', 'atasan'])
            ->when($this->search, fn($q) => $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orWhereHas('atasan', fn($q) => $q->where('name', 'like', "%{$this->search}%")))
            ->when($this->filterLevel, fn($q) => $q->where('level', $this->filterLevel))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->filterLevel = null;
        $this->resetPage();
    }

    public function create()
    {
        $this->authorize('users.create');
        
        $this->resetForm();
        $this->isEdit = false;
        $this->editingModel = null;
        $this->showModal = true;
    }

    public function edit(AtasanUser $model)
    {
        $this->authorize('users.edit');
        
        $this->editingModel = $model;
        $this->isEdit = true;
        $this->user_id = $model->user_id;
        $this->atasan_id = $model->atasan_id;
        $this->level = $model->level;
        $this->start_date = $model->start_date;
        $this->end_date = $model->end_date;
        $this->is_active = $model->is_active;
        $this->notes = $model->notes;
        $this->showModal = true;
    }

    public function save()
    {
        $this->authorize($this->isEdit ? 'users.edit' : 'users.create');

        try {
            $validated = $this->validate();

            if ($this->isEdit && $this->editingModel) {
                // Store old data
                $oldData = $this->editingModel->toArray();

                // Update
                $this->editingModel->update($validated);

                // Log to history
                AtasanUserHistory::create([
                    'atasan_user_id' => $this->editingModel->id,
                    'user_id' => $this->editingModel->user_id,
                    'atasan_id' => $this->editingModel->atasan_id,
                    'level' => $this->editingModel->level,
                    'action' => 'updated',
                    'changed_by' => auth()->id(),
                    'old_data' => json_encode($oldData),
                    'new_data' => json_encode($validated),
                    'reason' => 'Updated via modal',
                ]);

                $this->dispatch('toast', type: 'success', message: 'Atasan user berhasil diperbarui');
            } else {
                // Create
                $model = AtasanUser::create($validated);

                // Log to history
                AtasanUserHistory::create([
                    'atasan_user_id' => $model->id,
                    'user_id' => $model->user_id,
                    'atasan_id' => $model->atasan_id,
                    'level' => $model->level,
                    'action' => 'created',
                    'changed_by' => auth()->id(),
                    'new_data' => json_encode($model->toArray()),
                    'reason' => 'Created via modal',
                ]);

                $this->dispatch('toast', type: 'success', message: 'Atasan user berhasil ditambahkan');
            }

            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->user_id = null;
        $this->atasan_id = null;
        $this->level = null;
        $this->start_date = null;
        $this->end_date = null;
        $this->is_active = true;
        $this->notes = null;
        $this->resetValidation();
    }

    public function delete(AtasanUser $model)
    {
        $this->authorize('users.delete');
        $this->modelToDelete = $model;
        $this->confirmingDelete = true;
    }

    public function confirmDelete()
    {
        if ($this->modelToDelete) {
            // Log to history
            AtasanUserHistory::create([
                'atasan_user_id' => $this->modelToDelete->id,
                'user_id' => $this->modelToDelete->user_id,
                'atasan_id' => $this->modelToDelete->atasan_id,
                'level' => $this->modelToDelete->level,
                'action' => 'deleted',
                'changed_by' => auth()->id(),
                'old_data' => json_encode($this->modelToDelete->toArray()),
                'reason' => 'Soft delete via UI',
            ]);

            $this->modelToDelete->delete();
            $this->confirmingDelete = false;
            $this->modelToDelete = null;
            $this->dispatch('toast', type: 'success', message: 'Hirarki atasan berhasil dihapus');
        }
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->modelToDelete = null;
    }

    public function toggleActive(AtasanUser $model)
    {
        $this->authorize('users.edit');

        $oldData = $model->toArray();
        $model->update(['is_active' => !$model->is_active]);

        AtasanUserHistory::create([
            'atasan_user_id' => $model->id,
            'user_id' => $model->user_id,
            'atasan_id' => $model->atasan_id,
            'level' => $model->level,
            'action' => $model->is_active ? 'updated' : 'deactivated',
            'changed_by' => auth()->id(),
            'old_data' => json_encode($oldData),
            'new_data' => json_encode(['is_active' => $model->is_active]),
            'reason' => $model->is_active ? 'Aktivasi' : 'Nonaktifkan',
        ]);

        $this->dispatch('toast', type: 'success', message: $model->is_active ? 'Diaktifkan' : 'Dinonaktifkan');
    }

    public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedIds = $this->atasanUsers->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    public function toggleSelected($id)
    {
        if (in_array((string) $id, $this->selectedIds)) {
            $this->selectedIds = array_filter($this->selectedIds, fn($item) => $item !== (string) $id);
        } else {
            $this->selectedIds[] = (string) $id;
        }

        if (count($this->selectedIds) === 0) {
            $this->selectAll = false;
        }
    }

    public function massDelete()
    {
        $this->authorize('users.delete');

        if (empty($this->selectedIds)) {
            $this->dispatch('toast', type: 'error', message: 'Pilih minimal satu item');
            return;
        }

        $this->selectedIdsToDelete = $this->selectedIds;
        $this->confirmingDelete = true;
    }

    public function confirmMassDelete()
    {
        $deleted = 0;
        foreach ($this->selectedIdsToDelete as $id) {
            $model = AtasanUser::find($id);
            if ($model) {
                $model->delete();
                $deleted++;
            }
        }

        $this->selectedIds = [];
        $this->selectAll = false;
        $this->selectedIdsToDelete = [];
        $this->confirmingDelete = false;
        $this->dispatch('toast', type: 'success', message: "$deleted data berhasil dihapus");
    }

    public function cancelMassDelete()
    {
        $this->confirmingDelete = false;
        $this->selectedIdsToDelete = [];
    }

    public function resetDeleteModal()
    {
        $this->confirmingDelete = false;
        $this->modelToDelete = null;
        $this->selectedIdsToDelete = [];
    }

    public function viewHistory(AtasanUser $model)
    {
        $this->historyModel = $model;
        $this->histories = $model->histories()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($history) => [
                'id' => $history->id,
                'action' => $history->action,
                'changed_by' => $history->changedBy?->name ?? 'System',
                'created_at' => $history->created_at,
                'reason' => $history->reason,
                'old_data' => is_array($history->old_data) ? $history->old_data : [],
                'new_data' => is_array($history->new_data) ? $history->new_data : [],
                'level' => $history->level,
            ])
            ->toArray();
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->historyModel = null;
        $this->histories = [];
    }

    public function render()
    {
        return view('livewire.admin.atasan.atasan-user-index', [
            'atasanUsers' => $this->atasanUsers,
            'users' => User::orderBy('name')->get(),
        ]);
    }
}
