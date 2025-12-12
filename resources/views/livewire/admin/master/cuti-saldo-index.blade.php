<div class="w-full">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Cuti Saldo</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola saldo cuti karyawan</p>
        </div>
        @can('master_data.create')
            <button wire:click="create" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                + Tambah Saldo
            </button>
        @endcan
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Karyawan</label>
                <input type="text" wire:model.live.debounce="search" 
                    placeholder="Nama atau email..." 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun Ajaran</label>
                <select wire:model.live="filterTahunAjaran" 
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">Semua</option>
                    @foreach($tahunAjaranList as $tahun)
                        <option value="{{ $tahun->id }}">{{ $tahun->periode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="clearFilters" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 w-full">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Karyawan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Tahun Ajaran</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900 dark:text-white">Cuti Tahunan</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900 dark:text-white">Cuti Melahirkan</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900 dark:text-white">Carry Over</th>
                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($saldoList as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $item->user->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->user->email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $item->tahunAjaran->periode }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item->cuti_tahunan_sisa }}</span>
                                <span class="text-gray-500 dark:text-gray-400">/ {{ $item->cuti_tahunan_awal }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Terpakai: {{ $item->cuti_tahunan_terpakai }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item->cuti_melahirkan_sisa }}</span>
                                <span class="text-gray-500 dark:text-gray-400">/ {{ $item->cuti_melahirkan_awal }}</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Terpakai: {{ $item->cuti_melahirkan_terpakai }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $item->carry_over_tahunan }}</span>
                                <span class="text-gray-500 dark:text-gray-400">used: {{ $item->carry_over_digunakan }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex gap-2 justify-end">
                                @can('master_data.edit')
                                    <button wire:click="edit({{ $item->id }})" 
                                        class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                @endcan
                                @can('master_data.delete')
                                    <button wire:click="confirmDelete({{ $item->id }})" 
                                        class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data cuti saldo
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($saldoList->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $saldoList->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Create/Edit -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full mx-4">
                <!-- Modal Header -->
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $isEdit ? 'Edit Cuti Saldo' : 'Tambah Cuti Saldo' }}
                    </h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="px-6 py-4 space-y-4 max-h-96 overflow-y-auto">
                    <!-- User Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Karyawan <span class="text-red-500">*</span></label>
                        <select wire:model="user_id" 
                            @if($isEdit) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Karyawan</option>
                            @foreach($userList as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Tahun Ajaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                        <select wire:model="tahun_ajaran_id" 
                            @if($isEdit) disabled @endif
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach($tahunAjaranList as $tahun)
                                <option value="{{ $tahun->id }}">{{ $tahun->periode }}</option>
                            @endforeach
                        </select>
                        @error('tahun_ajaran_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Cuti Tahunan Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Cuti Tahunan</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Awal <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="cuti_tahunan_awal" min="0" max="100"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                @error('cuti_tahunan_awal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Terpakai</label>
                                <input type="number" wire:model="cuti_tahunan_terpakai" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                                @error('cuti_tahunan_terpakai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sisa</label>
                                <input type="number" wire:model="cuti_tahunan_sisa" min="0" readonly
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Cuti Melahirkan Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Cuti Melahirkan</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Awal</label>
                                <input type="number" wire:model="cuti_melahirkan_awal" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Terpakai</label>
                                <input type="number" wire:model="cuti_melahirkan_terpakai" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sisa</label>
                                <input type="number" wire:model="cuti_melahirkan_sisa" min="0" readonly
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Carry Over Section -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-3">Carry Over</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Carry Over</label>
                                <input type="number" wire:model="carry_over_tahunan" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Digunakan</label>
                                <input type="number" wire:model="carry_over_digunakan" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                        <textarea wire:model="catatan" rows="3" placeholder="Catatan..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closeModal" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Batal
                    </button>
                    <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete && $modelToDelete)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-sm w-full mx-4">
                <div class="px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Hapus Cuti Saldo?</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus cuti saldo untuk <strong>{{ $modelToDelete->user->name }}</strong>?
                    </p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="$set('confirmingDelete', false)" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        Batal
                    </button>
                    <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
