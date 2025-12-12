<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pengajuan Izin</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola pengajuan izin Anda</p>
        </div>
        <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Buat Pengajuan
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <input type="text" wire:model.live="search" placeholder="Cari pengajuan..." 
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            
            <select wire:model.live="filterStatus" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                <option value="">-- Semua Status --</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="cancelled">Cancelled</option>
            </select>

            <select wire:model.live="filterJenisIzin" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                <option value="">-- Semua Jenis --</option>
                <option value="sakit">Sakit</option>
                <option value="penting">Penting</option>
                <option value="ibadah">Ibadah</option>
                <option value="lainnya">Lainnya</option>
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
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Jenis Izin</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Tanggal</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Durasi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Approval</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Alasan</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                @forelse($this->izinPengajuan as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 text-sm font-medium rounded-full 
                                @if($item->jenis_izin === 'sakit') bg-red-100 text-red-800
                                @elseif($item->jenis_izin === 'penting') bg-orange-100 text-orange-800
                                @elseif($item->jenis_izin === 'ibadah') bg-green-100 text-green-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst($item->jenis_izin) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            @if($item->tanggal_selesai)
                                {{ $item->tanggal_mulai->format('d/m/Y') }} - {{ $item->tanggal_selesai->format('d/m/Y') }}
                            @else
                                {{ $item->tanggal_mulai->format('d/m/Y') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            @if($item->jumlah_jam)
                                {{ $item->jumlah_jam }} jam
                            @else
                                Penuh
                            @endif
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
                                    $approverName = $lastApproval->atasanUser?->user?->name ?? 'Unknown';
                                    if ($status === 'approved') {
                                        $color = 'bg-green-100 text-green-800';
                                        $label = "✓ Disetujui oleh $approverName";
                                    } else {
                                        $color = 'bg-red-100 text-red-800';
                                        $label = "✗ Ditolak oleh $approverName";
                                    }
                                } else {
                                    $pendingCount = $approvals->where('status', 'pending')->count();
                                    if ($pendingCount > 0) {
                                        $color = 'bg-yellow-100 text-yellow-800';
                                        $label = "⏳ Menunggu ($pendingCount atasan)";
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
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ Str::limit($item->alasan, 50) ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex gap-2 justify-end">
                                <button wire:click="showDetail({{ $item->id }})" class="text-green-600 hover:text-green-800">Lihat Detail</button>
                                @if($item->status === 'draft')
                                    <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                @endif
                                @if($item->status === 'draft' || $item->status === 'pending')
                                    <button wire:click="cancel({{ $item->id }})" class="text-orange-600 hover:text-orange-800">Batalkan</button>
                                @endif
                                @if($item->status === 'draft')
                                    <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-800">Hapus</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada pengajuan izin
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($this->izinPengajuan->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $this->izinPengajuan->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Approval - Pure Livewire -->
    @if($showDetailModal && $detailModel)
        <div class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4 overflow-y-auto" wire:click.self="closeDetailModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl my-8">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between sticky top-0 bg-white dark:bg-gray-800 rounded-t-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail Pengajuan & Approval</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $detailModel->jenis_izin }}</p>
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
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-3 italic">Status pengajuan (dari tabel izin_pengajuan)</p>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-xs">Jenis Izin</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($detailModel->jenis_izin) }}</p>
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
                                <p class="font-medium text-gray-900 dark:text-white">{{ $detailModel->tanggal_selesai->format('d/m/Y') ?? '-' }}</p>
                            </div>
                            @if($detailModel->jumlah_jam)
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs">Durasi</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $detailModel->jumlah_jam }} jam</p>
                                </div>
                            @endif
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
                    @if($detailModel->approvalHistories && $detailModel->approvalHistories->count() > 0)
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
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-4 italic">Status per level approval (dari tabel izin_approval)</p>
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
                        {{ $isEdit ? 'Edit Pengajuan Izin' : 'Buat Pengajuan Izin' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-5 space-y-4 max-h-96 overflow-y-auto">
                    <!-- Alasan Izin (dari master) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alasan Izin</label>
                        <select wire:model.live="izin_alasan_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm">
                            <option value="">-- Pilih Alasan --</option>
                            @foreach($this->alasanList as $alasan)
                                <option value="{{ $alasan->id }}">{{ $alasan->nama_alasan }} {{ $alasan->is_bayar_penuh ? '(Bayar Penuh)' : '' }}</option>
                            @endforeach
                        </select>
                        @error('izin_alasan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Range (Tanggal Mulai - Selesai) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Periode Izin</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Tanggal Mulai</label>
                                <input type="date" wire:model="tanggal_mulai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Tanggal Selesai</label>
                                <input type="date" wire:model="tanggal_selesai" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm">
                            </div>
                        </div>
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('tanggal_selesai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Durasi (Jam) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Durasi (Jam) - Opsional</label>
                        <input type="number" wire:model="jumlah_jam" min="1" max="8" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm" placeholder="Kosongkan jika sepanjang hari">
                        @error('jumlah_jam')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alasan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alasan</label>
                        <textarea wire:model="alasan" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm" rows="3"></textarea>
                        @error('alasan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Khusus jika alasan memerlukan surat dokter -->
                    @if($this->selectedAlasan?->perlu_surat_dokter)
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 space-y-3 border border-blue-200 dark:border-blue-800">
                            <p class="text-xs font-medium text-blue-900 dark:text-blue-200">📋 Data Surat Dokter (Wajib Diisi)</p>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Surat Dokter</label>
                                <input type="date" wire:model="tanggal_surat_dokter" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm">
                                @error('tanggal_surat_dokter')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Surat Dokter (PDF/JPG/PNG)</label>
                                <input type="file" wire:model="file_surat_dokter" accept=".pdf,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm">
                                @error('file_surat_dokter')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @if($file_surat_dokter)
                                    <p class="text-green-600 dark:text-green-400 text-xs mt-1">✓ File dipilih: {{ $file_surat_dokter->getClientOriginalName() }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex gap-3 p-4 border-t border-gray-200 dark:border-gray-700 justify-end bg-gray-50 dark:bg-gray-700 rounded-b-lg">
                    <button wire:click="closeModal" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500">
                        Batal
                    </button>
                    <button wire:click="save" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-sm w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Hapus Pengajuan</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Apakah Anda yakin ingin menghapus pengajuan izin ini?</p>
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
