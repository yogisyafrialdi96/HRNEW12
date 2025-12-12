<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Persetujuan Cuti</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola persetujuan pengajuan cuti bawahan</p>
        </div>
        <div class="flex items-center gap-4">
            <button wire:click="$toggle('showHistory')"
                class="bg-zinc-400 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition duration-200 whitespace-nowrap text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
                <span class="hidden sm:inline">{{ $showHistory ? 'Show Pending' : 'Show History' }}</span>
                <span class="sm:hidden">{{ $showHistory ? 'Pending' : 'History' }}</span>
            </button>
            <div class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold">
                {{ $totalPending ?? 0 }} Menunggu Approval
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" wire:model.live="search" placeholder="Cari nama karyawan atau nomor cuti..." 
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            
            <button wire:click="clearFilters" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300">
                Reset Filter
            </button>
        </div>
    </div>

    <!-- Approval List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        @if($pendingApprovals->count() > 0)
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">No. Cuti</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama Karyawan</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Tanggal</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Hari</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status Approval</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Level Saat Ini</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @foreach($pendingApprovals as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->nomor_cuti ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $item->user->karyawan->full_name ?? $item->user->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->user->karyawan->nip ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $item->tanggal_mulai->format('d/m/Y') }}<br>
                                <span class="text-xs">s/d {{ $item->tanggal_selesai->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 text-center font-semibold">
                                {{ $item->jumlah_hari }} hari
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    // Get approval status and details
                                    $lastApproval = $item->approval->where('status', '!=', 'pending')->last();
                                    if ($lastApproval) {
                                        $status = $lastApproval->status;
                                        $approvedBy = $lastApproval->approvedBy?->name ?? 'Unknown';
                                        if ($status === 'approved') {
                                            $color = 'bg-green-100 text-green-800';
                                            $label = "✓ Disetujui oleh $approvedBy";
                                        } else {
                                            $color = 'bg-red-100 text-red-800';
                                            $label = "✗ Ditolak oleh $approvedBy";
                                        }
                                    } else {
                                        // Find pending approvals
                                        $pendingApprovals = $item->approval->where('status', 'pending');
                                        if ($pendingApprovals->count() > 0) {
                                            $color = 'bg-yellow-100 text-yellow-800';
                                            $label = "⏳ Menunggu approval (" . $pendingApprovals->count() . ")";
                                        } else {
                                            $color = 'bg-gray-100 text-gray-800';
                                            $label = "- Belum ada approval";
                                        }
                                    }
                                @endphp
                                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full {{ $color }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $currentLevel = \App\Services\ApprovalService::getCurrentApprovalLevel($item);
                                    $totalLevels = \App\Services\ApprovalService::getTotalApprovalLevels($item->user_id);
                                @endphp
                                <div class="flex items-center gap-2">
                                    @for ($i = 1; $i <= $totalLevels; $i++)
                                        @if ($i <= $currentLevel)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-800 text-xs font-bold">
                                                ✓
                                            </span>
                                        @elseif ($i == $currentLevel + 1)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-800 text-xs font-bold">
                                                {{ $i }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-200 text-gray-600 text-xs font-bold">
                                                {{ $i }}
                                            </span>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <button wire:click="openApprovalModal({{ $item->id }})" 
                                    class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs font-medium transition">
                                    Tinjau & Setujui
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                    @if($showHistory)
                        Tidak Ada Riwayat Approval
                    @else
                        Tidak Ada Persetujuan Menunggu
                    @endif
                </h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    @if($showHistory)
                        Belum ada pengajuan yang sudah diproses
                    @else
                        Semua pengajuan cuti bawahan sudah diproses
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Approval Modal -->
    @if($showApprovalModal && $selectedCuti)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:click.self="closeApprovalModal">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-2xl w-full max-h-96 overflow-y-auto">
                <!-- Header -->
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center sticky top-0 bg-white dark:bg-gray-800">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Persetujuan Pengajuan Cuti
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $selectedCuti->nomor_cuti }}
                        </p>
                    </div>
                    <button wire:click="closeApprovalModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="px-6 py-4 space-y-4">
                    <!-- Informasi Karyawan -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                        <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-2">Informasi Karyawan</h3>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Nama</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $selectedCuti->user->karyawan->full_name ?? $selectedCuti->user->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">NIP</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $selectedCuti->user->karyawan->nip ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Cuti -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Detail Pengajuan</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Tanggal Mulai</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $selectedCuti->tanggal_mulai->format('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Tanggal Selesai</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $selectedCuti->tanggal_selesai->format('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Jumlah Hari</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $selectedCuti->jumlah_hari }} hari kerja
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400">Jenis Cuti</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst($selectedCuti->jenis_cuti) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Alasan -->
                    @if($selectedCuti->keterangan)
                        <div class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg border border-amber-200 dark:border-amber-800">
                            <h3 class="text-sm font-semibold text-amber-900 dark:text-amber-200 mb-2">Alasan/Keterangan</h3>
                            <p class="text-sm text-amber-900 dark:text-amber-100">
                                {{ $selectedCuti->keterangan }}
                            </p>
                        </div>
                    @endif

                    <!-- Approval Chain -->
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                        <h3 class="text-sm font-semibold text-purple-900 dark:text-purple-200 mb-3">Status Persetujuan</h3>
                        @php
                            $approvalChain = \App\Services\ApprovalService::getApprovalHierarchy($selectedCuti->user_id);
                            $currentLevel = \App\Services\ApprovalService::getCurrentApprovalLevel($selectedCuti);
                        @endphp
                        <div class="space-y-2">
                            @foreach($approvalChain as $item)
                                <div class="flex items-center gap-3">
                                    @if($item->level <= $currentLevel)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-500 text-white font-bold text-sm">
                                            ✓
                                        </span>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            <span class="font-medium">Level {{ $item->level }}:</span> 
                                            {{ $item->atasan->karyawan->full_name ?? $item->atasan->name }}
                                            <span class="text-xs text-gray-500">(Disetujui)</span>
                                        </p>
                                    @elseif($item->level == $currentLevel + 1)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-500 text-white font-bold text-sm">
                                            {{ $item->level }}
                                        </span>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            <span class="font-medium">Level {{ $item->level }}:</span> 
                                            {{ $item->atasan->karyawan->full_name ?? $item->atasan->name }}
                                            <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold">(Menunggu Anda)</span>
                                        </p>
                                    @else
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-300 text-gray-600 font-bold text-sm">
                                            {{ $item->level }}
                                        </span>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-medium">Level {{ $item->level }}:</span> 
                                            {{ $item->atasan->karyawan->full_name ?? $item->atasan->name }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Approval Action -->
                    <div class="space-y-3">
                        <!-- Notes for Approval -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">
                                Catatan (Opsional)
                            </label>
                            <textarea wire:model="approvalNotes" 
                                placeholder="Tambahkan catatan untuk persetujuan ini..."
                                class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" 
                                rows="2"></textarea>
                        </div>

                        <!-- Reason for Rejection -->
                        @if($approvalAction === 'reject')
                            <div>
                                <label class="block text-sm font-semibold text-red-900 dark:text-red-200 mb-1">
                                    Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="approvalReason" 
                                    placeholder="Jelaskan alasan penolakan..."
                                    class="w-full px-3 py-2 text-sm border border-red-300 dark:border-red-600 rounded-lg bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500 resize-none" 
                                    rows="2"></textarea>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 px-6 py-4 border-t border-gray-200 dark:border-gray-700 justify-end bg-gray-50 dark:bg-gray-700/50 sticky bottom-0">
                    <button wire:click="closeApprovalModal" 
                        class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 font-medium transition">
                        Batal
                    </button>
                    
                    @if($approvalAction !== 'reject')
                        <button wire:click="$set('approvalAction', 'reject')" 
                            class="px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                            Tolak
                        </button>
                        <button wire:click="approve" 
                            class="px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                            Setujui
                        </button>
                    @else
                        <button wire:click="$set('approvalAction', '')" 
                            class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 font-medium transition">
                            Kembali
                        </button>
                        <button wire:click="reject" 
                            class="px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition"
                            @if(!$approvalReason) disabled @endif>
                            Konfirmasi Penolakan
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    console.log('📋 CutiApprovalDashboard loaded');
    console.log('Livewire available:', typeof Livewire !== 'undefined');
    console.log('$wire available:', typeof $wire !== 'undefined');

    setTimeout(() => {
        // Find modal
        const modal = document.querySelector('[x-cloak][x-show]');
        console.log('✅ Found modal-like element:', !!modal);
        
        // Find ALL buttons
        const allButtons = document.querySelectorAll('button');
        console.log('✅ Total buttons on page:', allButtons.length);
        
        // Log all buttons with wire:click
        allButtons.forEach((btn, idx) => {
            const wireClick = btn.getAttribute('wire:click');
            const text = btn.textContent.trim();
            if (wireClick) {
                console.log(`  Button ${idx}: wire:click="${wireClick}" | text="${text}"`);
            }
        });

        // Try different selectors
        const approveBtn1 = document.querySelector('button[wire\\:click="approve"]');
        const approveBtn2 = Array.from(allButtons).find(b => b.getAttribute('wire:click') === 'approve');
        const rejectBtn1 = document.querySelector('button[wire\\:click="reject"]');
        const rejectBtn2 = Array.from(allButtons).find(b => b.getAttribute('wire:click') === 'reject');
        
        console.log('Approve button (selector1):', !!approveBtn1);
        console.log('Approve button (selector2):', !!approveBtn2);
        console.log('Reject button (selector1):', !!rejectBtn1);
        console.log('Reject button (selector2):', !!rejectBtn2);

        const approveBtn = approveBtn2 || approveBtn1;
        const rejectBtn = rejectBtn2 || rejectBtn1;

        if (approveBtn) {
            approveBtn.addEventListener('click', () => {
                console.log('🖱️ APPROVE button clicked!');
            });
        }
        
        if (rejectBtn) {
            rejectBtn.addEventListener('click', () => {
                console.log('🖱️ REJECT button clicked!');
            });
        }
        
        // Also check if modal is visible
        const visible = allButtons.length > 20; // rough check
        console.log('Modal probably visible:', visible);
    }, 200);
</script>
@endscript
