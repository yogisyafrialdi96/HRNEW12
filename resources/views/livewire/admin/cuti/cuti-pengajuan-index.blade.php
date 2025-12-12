<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pengajuan Cuti</h1>
            <p class="text-gray-600 dark:text-gray-400">Lihat semua pengajuan cuti karyawan</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <input type="text" wire:model.live="search" placeholder="Cari pengajuan atau nama karyawan..." 
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            
            <select wire:model.live="filterStatus" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                <option value="">-- Semua Status --</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <select wire:model.live="filterJenisCuti" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                <option value="">-- Semua Jenis --</option>
                <option value="tahunan">Tahunan</option>
                <option value="melahirkan">Melahirkan</option>
            </select>

            <button wire:click="clearFilters" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300">
                Reset Filter
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">No. Cuti</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Karyawan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Jenis Cuti</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Hari</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Approval</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                @forelse($this->cutiPengajuan as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $item->nomor_cuti ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $item->user?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 text-sm font-medium rounded-full 
                                {{ $item->jenis_cuti === 'tahunan' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ ucfirst($item->jenis_cuti) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $item->tanggal_mulai->format('d/m/Y') }} - {{ $item->tanggal_selesai->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $item->jumlah_hari }} hari
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 text-xs font-medium rounded-full 
                                @if($item->status === 'draft') bg-yellow-100 text-yellow-800
                                @elseif($item->status === 'pending') bg-blue-100 text-blue-800
                                @elseif($item->status === 'approved') bg-green-100 text-green-800
                                @elseif($item->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $approvals = $item->approval ?? collect();
                                $lastApproval = $approvals->where('status', '!=', 'pending')->last();
                                
                                if ($lastApproval) {
                                    $status = $lastApproval->status;
                                    $approverName = $lastApproval->approvedBy?->name ?? 'Unknown';
                                    if ($status === 'approved') {
                                        $color = 'bg-green-100 text-green-800';
                                        $label = "✓ Disetujui";
                                    } else {
                                        $color = 'bg-red-100 text-red-800';
                                        $label = "✗ Ditolak";
                                    }
                                } else {
                                    $pendingCount = $approvals->where('status', 'pending')->count();
                                    if ($pendingCount > 0) {
                                        $color = 'bg-yellow-100 text-yellow-800';
                                        $label = "⏳ Menunggu ($pendingCount)";
                                    } else {
                                        $color = 'bg-gray-100 text-gray-800';
                                        $label = "-";
                                    }
                                }
                            @endphp
                            <span class="inline-block px-3 py-1 text-xs font-medium rounded-full {{ $color }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <button wire:click="showDetail({{ $item->id }})" class="text-green-600 hover:text-green-800">Lihat Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada pengajuan cuti
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($this->cutiPengajuan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $this->cutiPengajuan->links() }}
            </div>
        @endif

        <!-- Modal Detail Approval - Pure Livewire -->
        @if($showDetailModal && $detailModel)
    <div class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4 overflow-y-auto" wire:click.self="closeDetailModal">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl my-8">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between sticky top-0 bg-white dark:bg-gray-800 rounded-t-lg">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail Pengajuan & Approval</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $detailModel->nomor_cuti }} - {{ $detailModel->user?->name ?? '-' }}</p>
                </div>
                <button wire:click="closeDetailModal" type="button" class="text-gray-400 hover:text-gray-500 p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="px-6 py-4 space-y-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                <!-- Info Pengajuan -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Informasi Pengajuan</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-3 italic">Status pengajuan (dari tabel cuti_pengajuan)</p>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Jenis Cuti</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($detailModel->jenis_cuti) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Status Pengajuan</p>
                            <p class="font-medium">
                                @if($detailModel->status === 'draft')
                                    <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">Draft</span>
                                @elseif($detailModel->status === 'pending')
                                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Menunggu Approval</span>
                                @elseif($detailModel->status === 'approved')
                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">✓ Disetujui</span>
                                @elseif($detailModel->status === 'rejected')
                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">✗ Ditolak Atasan</span>
                                @elseif($detailModel->status === 'cancelled')
                                    <span class="inline-block px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">⊘ Dibatalkan</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Tanggal Mulai</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $detailModel->tanggal_mulai->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Tanggal Selesai</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $detailModel->tanggal_selesai->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Jumlah Hari</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $detailModel->jumlah_hari }} hari</p>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Tanggal Pengajuan</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $detailModel->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if($detailModel->alasan)
                        <div class="mt-4 pt-4 border-t border-blue-200 dark:border-blue-800">
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Alasan</p>
                            <p class="text-gray-900 dark:text-white text-sm">{{ $detailModel->alasan }}</p>
                        </div>
                    @endif
                </div>

                <!-- Approval History -->
                @if($detailModel->approvalHistories->isNotEmpty())
                    <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Riwayat Approval</h3>
                        <div class="space-y-3">
                            @foreach($detailModel->approvalHistories->sortByDesc('created_at') as $history)
                                <div class="bg-white dark:bg-gray-800 rounded p-3 text-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $history->user?->name ?? 'Unknown User' }}</p>
                                        <span class="inline-block px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">
                                            {{ ucfirst($history->action) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $history->created_at->format('d/m/Y H:i') }}</p>
                                    @if($history->keterangan)
                                        <div class="mt-2">
                                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Keterangan:</p>
                                            <p class="text-xs text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 p-2 rounded">{{ $history->keterangan }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Approval Comments -->
                @if($detailModel->approval && $detailModel->approval->count() > 0)
                    @php
                        $approvalsWithComments = $detailModel->approval->filter(fn($app) => $app->komentar);
                    @endphp
                    @if($approvalsWithComments->count() > 0)
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Komentar & Status Approval</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-4 italic">Status per level approval (dari tabel cuti_approval)</p>
                            <div class="space-y-3">
                                @foreach($approvalsWithComments as $approval)
                                    <div class="bg-white dark:bg-gray-800 rounded p-3 text-sm">
                                        <div class="flex justify-between items-start mb-2">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $approval->atasanUser?->user?->name ?? 'Unknown' }}</p>
                                            <span class="inline-block px-2 py-1 text-xs font-medium rounded
                                                @if($approval->status === 'approved')
                                                    bg-green-100 text-green-800
                                                @elseif($approval->status === 'rejected')
                                                    bg-red-100 text-red-800
                                                @else
                                                    bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($approval->status) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 p-2 rounded">{{ $approval->komentar }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2 bg-gray-50 dark:bg-gray-700 rounded-b-lg sticky bottom-0">
                <button wire:click="closeDetailModal" type="button"
                    class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endif
    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click.self="closeModal">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-2xl max-w-md w-full">
                <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-3 flex justify-between items-center">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $isEdit ? 'Edit Pengajuan Cuti' : 'Buat Pengajuan Cuti' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Cuti Info Display -->
                <div class="px-5 py-3 bg-blue-50 dark:bg-blue-900/20 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-300 mb-2">Informasi Cuti - {{ $jenis_cuti === 'tahunan' ? 'Tahunan' : 'Melahirkan' }}</p>
                    <div class="grid grid-cols-4 gap-2 text-xs">
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Sisa Cuti</p>
                            <p class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $cuti_sisa ?? '-' }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Dipakai</p>
                            <div>
                                <p class="text-lg font-bold text-orange-600 dark:text-orange-400">
                                    @if($cuti_terpakai_estimasi !== null)
                                        {{ $cuti_terpakai_estimasi }}
                                    @else
                                        {{ $cuti_terpakai ?? '-' }}
                                    @endif
                                </p>
                                @if($cuti_terpakai_estimasi !== null && $cuti_terpakai_estimasi != ($cuti_terpakai ?? 0))
                                    <p class="text-xs text-gray-500 dark:text-gray-400">(dari {{ $cuti_terpakai ?? 0 }})</p>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Maksimal</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ $cuti_maksimal ?? '-' }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Est. Sisa</p>
                            <div>
                                <p class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ $cuti_sisa_estimasi !== null ? $cuti_sisa_estimasi : '-' }}</p>
                                @if($cuti_sisa_estimasi !== null && $cuti_sisa_estimasi != ($cuti_sisa ?? 0))
                                    <p class="text-xs text-gray-500 dark:text-gray-400">(dari {{ $cuti_sisa ?? 0 }})</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Debug info (for development) -->
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 opacity-50">
                        <p>Debug: sisa={{ $cuti_sisa }}, dipakai={{ $cuti_terpakai }}, sisa_est={{ $cuti_sisa_estimasi }}, dipakai_est={{ $cuti_terpakai_estimasi }}, hari={{ $jumlah_hari }}</p>
                    </div>
                </div>

                <div class="px-5 py-4 space-y-3 max-h-80 overflow-y-auto">
                    <!-- Jenis Cuti -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">Jenis Cuti <span class="text-red-500">*</span></label>
                        <select wire:model.live="jenis_cuti" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="tahunan">Tahunan</option>
                            <option value="melahirkan">Melahirkan</option>
                        </select>
                        @error('jenis_cuti')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai & Selesai -->
                    <div class="grid grid-cols-2 gap-2" x-data="{ reservedDates: @js($reservedDatesArray ?? []) }">
                        <div>
                            <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">Mulai <span class="text-red-500">*</span></label>
                            <input type="date" wire:model.change="tanggal_mulai" {{ $tanggal_mulai_allowed ? 'min=' . $tanggal_mulai_allowed : '' }} @change="if (reservedDates.includes($event.target.value)) { window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Tanggal ' + $event.target.value + ' sudah diajukan. Silakan pilih tanggal yang berbeda.', type: 'warning' } })); $event.target.value = ''; }" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @if($h_min_cuti && $h_min_cuti > 0)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Min {{ $h_min_cuti }} hari dari hari ini</p>
                            @endif
                            @error('tanggal_mulai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">Selesai <span class="text-red-500">*</span></label>
                            <input type="date" wire:model.change="tanggal_selesai" {{ $tanggal_mulai ? 'min=' . $tanggal_mulai : '' }} @change="const reserved = reservedDates; const startStr = document.querySelector('[wire\\:model\\.change=tanggal_mulai]').value; const endStr = this.value; if (!startStr || !endStr) { return; } const start = new Date(startStr); const end = new Date(endStr); let hasConflict = false; for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) { const dateStr = d.toISOString().split('T')[0]; if (reserved.includes(dateStr)) { hasConflict = true; break; } } if (hasConflict) { window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Terdapat tanggal dalam rentang yang sudah diajukan. Silakan pilih tanggal yang berbeda.', type: 'warning' } })); this.value = ''; }" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tanggal_selesai')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Jumlah Hari -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">Jumlah Hari & Estimasi</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 dark:text-white">
                                <p class="text-xs text-gray-600 dark:text-gray-300">Yang Diajukan</p>
                                <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                    @if($jumlah_hari)
                                        {{ $jumlah_hari }} hari
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 dark:text-white">
                                <p class="text-xs text-gray-600 dark:text-gray-300">Est. Sisa Cuti</p>
                                <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                    @if($cuti_sisa_estimasi !== null)
                                        {{ $cuti_sisa_estimasi }} hari
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Alasan -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">Alasan</label>
                        <textarea wire:model="alasan" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="2"></textarea>
                        @error('alasan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Khusus Melahirkan -->
                    @if($jenis_cuti === 'melahirkan')
                        <div>
                            <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">Tanggal Estimasi Lahir</label>
                            <input type="date" wire:model="tanggal_estimasi_lahir" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('tanggal_estimasi_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">Nama Dokter</label>
                            <input type="text" wire:model="nama_dokter" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">Tanggal Surat Dokter</label>
                            <input type="date" wire:model="tanggal_surat_dokter" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    @endif

                    <!-- Contact Info -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">Alamat Kontak</label>
                            <input type="text" wire:model="contact_address" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Alamat">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-900 dark:text-white mb-1">No. HP</label>
                            <input type="text" wire:model="phone" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="HP">
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 px-5 py-3 border-t border-gray-200 dark:border-gray-700 justify-end">
                    <button wire:click="closeModal" class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition">
                        Batal
                    </button>
                    <button wire:click="save" class="px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        {{ $isEdit ? 'Perbarui' : 'Ajukan Cuti' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-sm w-full mx-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Hapus Pengajuan</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Apakah Anda yakin ingin menghapus pengajuan cuti ini?</p>
                    <div class="flex gap-3 justify-end">
                        <button wire:click="$set('confirmingDelete', false)" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>