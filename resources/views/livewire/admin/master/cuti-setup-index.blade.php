<div class="flex flex-col gap-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Setup Cuti</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Konfigurasi global pengaturan cuti</p>
        </div>
        <button
            wire:click="openModal"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition"
        >
            Edit Setup
        </button>
    </div>

    @if($setup)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Cuti Tahunan -->
            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Cuti Tahunan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                        <span class="text-zinc-600 dark:text-zinc-400">Minimum hari kerja</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->h_min_cuti_tahunan }} hari</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                        <span class="text-zinc-600 dark:text-zinc-400">Max per tahun</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->max_cuti_tahunan_per_tahun }} hari</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-zinc-600 dark:text-zinc-400">Max carry over</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->max_carry_over }} hari</span>
                    </div>
                </div>
            </div>

            <!-- Cuti Melahirkan -->
            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Cuti Melahirkan</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                        <span class="text-zinc-600 dark:text-zinc-400">Minimum hari kerja</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->h_min_cuti_melahirkan }} hari</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-zinc-600 dark:text-zinc-400">Total hari cuti</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->hari_cuti_melahirkan }} hari</span>
                    </div>
                </div>
            </div>

            <!-- Jam Kerja -->
            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Jam Kerja</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                        <span class="text-zinc-600 dark:text-zinc-400">Hari kerja</span>
                        <span class="font-medium text-zinc-900 dark:text-white text-sm">{{ str_replace(',', ', ', $setup->hari_kerja) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-zinc-600 dark:text-zinc-400">Jam per hari</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->jam_kerja_per_hari }} jam</span>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            @if($setup->catatan)
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Catatan</h3>
                    <p class="text-zinc-700 dark:text-zinc-300">{{ $setup->catatan }}</p>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-12 border border-dashed border-zinc-300 dark:border-zinc-600 rounded-lg">
            <p class="text-zinc-500 dark:text-zinc-400 mb-4">Setup cuti belum dikonfigurasi</p>
            <button
                wire:click="openModal"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition"
            >
                Buat Setup
            </button>
        </div>
    @endif

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Setup Cuti</h2>
                    <button
                        wire:click="closeModal"
                        class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Cuti Tahunan -->
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Cuti Tahunan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Minimum Hari Kerja</label>
                                <input
                                    type="number"
                                    wire:model="h_min_cuti_tahunan"
                                    class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                                />
                                @error('h_min_cuti_tahunan') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Max per Tahun</label>
                                <input
                                    type="number"
                                    wire:model="max_cuti_tahunan_per_tahun"
                                    class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                                />
                                @error('max_cuti_tahunan_per_tahun') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Max Carry Over</label>
                                <input
                                    type="number"
                                    wire:model="max_carry_over"
                                    class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                                />
                                @error('max_carry_over') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Cuti Melahirkan -->
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Cuti Melahirkan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Hari Cuti Melahirkan</label>
                                <input
                                    type="number"
                                    wire:model="hari_cuti_melahirkan"
                                    class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                                />
                                @error('hari_cuti_melahirkan') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Minimum Hari Kerja</label>
                                <input
                                    type="number"
                                    wire:model="h_min_cuti_melahirkan"
                                    class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                                />
                                @error('h_min_cuti_melahirkan') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Jam Kerja -->
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Jam Kerja</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Hari Kerja (contoh: 1,2,3,4,5)</label>
                                <input
                                    type="text"
                                    wire:model="hari_kerja"
                                    placeholder="1,2,3,4,5"
                                    class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                                />
                                @error('hari_kerja') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Jam Kerja per Hari</label>
                                <input
                                    type="number"
                                    wire:model="jam_kerja_per_hari"
                                    class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                                />
                                @error('jam_kerja_per_hari') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Catatan</label>
                        <textarea
                            wire:model="catatan"
                            rows="3"
                            class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                        ></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4">
                        <button
                            wire:click="save"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition"
                        >
                            Simpan
                        </button>
                        <button
                            wire:click="closeModal"
                            class="flex-1 px-4 py-2 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 transition"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
