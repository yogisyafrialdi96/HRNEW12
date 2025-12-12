<div class="flex flex-col gap-6">
    <!-- Header Section with Title and Toggle Button -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Approval Pengajuan Cuti</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Kelola dan proses pengajuan cuti dari karyawan</p>
        </div>
        <div class="justify-end mb-4 flex gap-2">
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
        </div>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <input
                type="text"
                placeholder="Cari nama/NIP karyawan..."
                wire:model.live="search"
                class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-600"
            />
        </div>
        <div>
            <select
                wire:model.live="filterJenisCuti"
                class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"
            >
                <option value="">Semua Jenis Cuti</option>
                <option value="tahunan">Cuti Tahunan</option>
                <option value="melahirkan">Cuti Melahirkan</option>
            </select>
        </div>
        @if(!$showHistory)
        <div>
            <select
                wire:model.live="filterStatus"
                class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"
            >
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-lg">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800">
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Karyawan</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Jenis Cuti</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Tanggal</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Durasi</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $pengajuan)
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $pengajuan->user->karyawan->full_name ?? 'N/A' }}</span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $pengajuan->user->karyawan->nip ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <span class="text-zinc-700 dark:text-zinc-300">
                                {{ $pengajuan->jenis_cuti === 'tahunan' ? 'Cuti Tahunan' : 'Cuti Melahirkan' }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex flex-col text-sm">
                                <span>{{ Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d/m/Y') }}</span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">s/d {{ Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            {{ $pengajuan->jumlah_hari }} hari
                        </td>
                        <td class="px-6 py-3">
                            <span @class([
                                'px-3 py-1 rounded-full text-xs font-medium',
                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => $pengajuan->status === 'pending',
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $pengajuan->status === 'approved',
                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $pengajuan->status === 'rejected',
                            ])>
                                {{ ucfirst($pengajuan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex gap-2">
                                @if(!$showHistory)
                                    <button
                                        wire:click="openApprovalModal({{ $pengajuan->id }})"
                                        class="px-3 py-1 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition"
                                    >
                                        Review
                                    </button>
                                @else
                                    <button
                                        wire:click="openApprovalModal({{ $pengajuan->id }})"
                                        class="px-3 py-1 text-sm font-medium text-zinc-600 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600 rounded-lg transition"
                                    >
                                        Lihat Detail
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                            @if($showHistory)
                                Tidak ada riwayat approval untuk ditampilkan
                            @else
                                Tidak ada pengajuan cuti yang perlu di-approve
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $pengajuans->links() }}
    </div>

    <!-- Approval Modal -->
    @if($showModal && $selectedApproval)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Detail Pengajuan Cuti</h2>
                    <button
                        wire:click="closeModal"
                        class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4 space-y-6">
                    <!-- Detail Pengajuan -->
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Informasi Pengajuan</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-zinc-600 dark:text-zinc-400">Nama Karyawan</label>
                                <p class="text-zinc-900 dark:text-white font-medium">{{ $selectedApproval->user->karyawan->full_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-zinc-600 dark:text-zinc-400">NIP / User ID</label>
                                <p class="text-zinc-900 dark:text-white font-medium">{{ $selectedApproval->user->karyawan->nip ?? $selectedApproval->user_id }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-zinc-600 dark:text-zinc-400">Jenis Cuti</label>
                                <p class="text-zinc-900 dark:text-white font-medium">
                                    {{ $selectedApproval->jenis_cuti === 'tahunan' ? 'Cuti Tahunan' : 'Cuti Melahirkan' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm text-zinc-600 dark:text-zinc-400">Durasi</label>
                                <p class="text-zinc-900 dark:text-white font-medium">{{ $selectedApproval->jumlah_hari }} hari</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-sm text-zinc-600 dark:text-zinc-400">Tanggal</label>
                                <p class="text-zinc-900 dark:text-white font-medium">
                                    {{ Carbon\Carbon::parse($selectedApproval->tanggal_mulai)->format('d/m/Y') }} s/d {{ Carbon\Carbon::parse($selectedApproval->tanggal_selesai)->format('d/m/Y') }}
                                </p>
                            </div>
                            @if($selectedApproval->jenis_cuti === 'melahirkan')
                                <div class="col-span-2">
                                    <label class="text-sm text-zinc-600 dark:text-zinc-400">Informasi Kelahiran</label>
                                    <p class="text-zinc-900 dark:text-white font-medium">
                                        Dr. {{ $selectedApproval->nama_dokter ?? 'N/A' }}
                                    </p>
                                </div>
                            @endif
                            @if($selectedApproval->alasan)
                                <div class="col-span-2">
                                    <label class="text-sm text-zinc-600 dark:text-zinc-400">Alasan</label>
                                    <p class="text-zinc-900 dark:text-white">{{ $selectedApproval->alasan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Approval History -->
                    @if($selectedApproval->approvalHistories && $selectedApproval->approvalHistories->count() > 0)
                        <div>
                            <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Riwayat Approval</h3>
                            <div class="space-y-3">
                                @foreach($selectedApproval->approvalHistories->sortBy('level') as $history)
                                    <div class="border-l-2 pl-4 pb-3 {{ $history->status === 'approved' ? 'border-green-500' : 'border-red-500' }}">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-zinc-900 dark:text-white">
                                                    Level {{ $history->level }}: 
                                                    <span class="{{ $history->status === 'approved' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                        {{ ucfirst($history->status) }}
                                                    </span>
                                                </p>
                                                <p class="text-sm text-zinc-600 dark:text-zinc-400">oleh {{ $history->approvedBy->name ?? 'N/A' }}</p>
                                            </div>
                                            <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        @if($history->approval_comment)
                                            <p class="text-sm text-zinc-700 dark:text-zinc-300 mt-2 italic">{{ $history->approval_comment }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Approval Form -->
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Berikan Approval</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Komentar <span class="text-xs text-zinc-500">(Opsional)</span></label>
                                <textarea
                                    wire:model="approvalComment"
                                    rows="4"
                                    placeholder="Masukkan komentar approval (opsional)..."
                                    class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-600"
                                ></textarea>
                                @error('approvalComment')
                                    <span class="text-sm text-red-600 dark:text-red-400 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex gap-3 pt-4">
                                <button
                                    x-on:click="console.log('✓ Approve button clicked'); console.log('Pengajuan ID:', {{ $selectedApproval->id }})"
                                    wire:click="approve({{ $selectedApproval->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="approve"
                                    type="button"
                                    class="flex-1 px-4 py-2 font-medium text-white bg-green-600 hover:bg-green-700 disabled:bg-green-400 disabled:cursor-not-allowed rounded-lg transition"
                                >
                                    <span wire:loading.remove wire:target="approve">✓ Setujui</span>
                                    <span wire:loading wire:target="approve"><span class="inline-block animate-spin">⊙</span> Memproses...</span>
                                </button>
                                <button
                                    x-on:click="console.log('✗ Reject button clicked'); console.log('Pengajuan ID:', {{ $selectedApproval->id }})"
                                    wire:click="reject({{ $selectedApproval->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="reject"
                                    type="button"
                                    class="flex-1 px-4 py-2 font-medium text-white bg-red-600 hover:bg-red-700 disabled:bg-red-400 disabled:cursor-not-allowed rounded-lg transition"
                                >
                                    <span wire:loading.remove wire:target="reject">✗ Tolak</span>
                                    <span wire:loading wire:target="reject"><span class="inline-block animate-spin">⊙</span> Memproses...</span>
                                </button>
                                <button
                                    x-on:click="console.log('Close modal button clicked')"
                                    wire:click="closeModal"
                                    type="button"
                                    class="flex-1 px-4 py-2 font-medium text-zinc-900 dark:text-white border border-zinc-200 dark:border-zinc-700 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 transition"
                                >
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    console.log('📋 CutiApprovalIndex view loaded');
    console.log('Livewire component:', $wire);
    
    // Check if Livewire is available
    if (typeof Livewire !== 'undefined') {
        console.log('✅ Livewire is loaded');
    } else {
        console.error('❌ Livewire is NOT loaded');
    }

    // Listen for modal state changes
    $watch('showModal', (value) => {
        console.log('Modal state changed:', value);
    });

    // Monitor button clicks with more detailed info
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, checking for buttons...');
        const buttons = document.querySelectorAll('button[wire\\:click]');
        console.log('Found ' + buttons.length + ' wire:click buttons');
        
        buttons.forEach((btn, idx) => {
            console.log(`Button ${idx}:`, btn.getAttribute('wire:click'));
            btn.addEventListener('click', function(e) {
                console.log('🖱️ Click detected on button:', btn.getAttribute('wire:click'));
            });
        });
    });
</script>
@endscript
