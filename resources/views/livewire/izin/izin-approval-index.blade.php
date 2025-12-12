<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Approval Pengajuan Izin</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Kelola dan proses pengajuan izin dari karyawan</p>
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
                wire:model.live="filterJenisIzin"
                class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"
            >
                <option value="">Semua Jenis Izin</option>
                <option value="sakit">Sakit</option>
                <option value="penting">Penting</option>
                <option value="ibadah">Ibadah</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
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
    </div>

    <!-- Table -->
    <div class="overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-lg">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800">
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Karyawan</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Jenis Izin</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Tanggal</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Durasi</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $pengajuan)
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                        <td class="px-6 py-3">
                            <div class="flex flex-col">
                                <span class="font-medium text-zinc-900 dark:text-white">{{ $pengajuan->karyawan->nama ?? 'N/A' }}</span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $pengajuan->karyawan->nip ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <span @class([
                                'px-2 py-1 rounded text-xs font-medium',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => $pengajuan->jenis_izin === 'sakit',
                                'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' => $pengajuan->jenis_izin === 'penting',
                                'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200' => $pengajuan->jenis_izin === 'ibadah',
                                'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' => $pengajuan->jenis_izin === 'lainnya',
                            ])>
                                {{ ucfirst(str_replace('_', ' ', $pengajuan->jenis_izin)) }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex flex-col text-sm">
                                <span>{{ Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d/m/Y') }}</span>
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">s/d {{ Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            @if($pengajuan->jumlah_jam)
                                {{ $pengajuan->jumlah_jam }} jam
                            @else
                                Penuh
                            @endif
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
                                <button
                                    wire:click="openApprovalModal({{ $pengajuan->id }})"
                                    class="px-3 py-1 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition"
                                >
                                    Review
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                            Tidak ada pengajuan izin yang perlu di-approve
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
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Detail Pengajuan Izin</h2>
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
                                <p class="text-zinc-900 dark:text-white font-medium">{{ $selectedApproval->karyawan->nama ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-zinc-600 dark:text-zinc-400">NIP</label>
                                <p class="text-zinc-900 dark:text-white font-medium">{{ $selectedApproval->karyawan->nip ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-zinc-600 dark:text-zinc-400">Jenis Izin</label>
                                <p class="text-zinc-900 dark:text-white font-medium">
                                    {{ ucfirst(str_replace('_', ' ', $selectedApproval->jenis_izin)) }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm text-zinc-600 dark:text-zinc-400">Durasi</label>
                                <p class="text-zinc-900 dark:text-white font-medium">
                                    @if($selectedApproval->jumlah_jam)
                                        {{ $selectedApproval->jumlah_jam }} jam
                                    @else
                                        Penuh
                                    @endif
                                </p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-sm text-zinc-600 dark:text-zinc-400">Tanggal</label>
                                <p class="text-zinc-900 dark:text-white font-medium">
                                    {{ Carbon\Carbon::parse($selectedApproval->tanggal_mulai)->format('d/m/Y') }} s/d {{ Carbon\Carbon::parse($selectedApproval->tanggal_selesai)->format('d/m/Y') }}
                                </p>
                            </div>
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
                                @foreach($selectedApproval->approvalHistories->sortByDesc('created_at') as $history)
                                    <div class="border-l-2 pl-4 pb-3 @if($history->action === 'approved') border-green-500 @elseif($history->action === 'rejected') border-red-500 @else border-blue-500 @endif">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-zinc-900 dark:text-white">
                                                    {{ $history->user->name ?? 'N/A' }}: 
                                                    <span @class(['text-green-600 dark:text-green-400' => $history->action === 'approved', 'text-red-600 dark:text-red-400' => $history->action === 'rejected'])>
                                                        {{ ucfirst($history->action) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <span class="text-xs text-zinc-500 dark:text-zinc-400">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        @if($history->keterangan)
                                            <p class="text-sm text-zinc-700 dark:text-zinc-300 mt-2 italic">{{ $history->keterangan }}</p>
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
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Komentar</label>
                                <textarea
                                    wire:model="approvalComment"
                                    rows="4"
                                    placeholder="Masukkan komentar approval..."
                                    class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-600"
                                ></textarea>
                                @error('approvalComment')
                                    <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex gap-3 pt-4">
                                <button
                                    wire:click="approve({{ $selectedApproval->id }})"
                                    class="flex-1 px-4 py-2 font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition"
                                >
                                    Setujui
                                </button>
                                <button
                                    wire:click="reject({{ $selectedApproval->id }})"
                                    class="flex-1 px-4 py-2 font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition"
                                >
                                    Tolak
                                </button>
                                <button
                                    wire:click="closeModal"
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
