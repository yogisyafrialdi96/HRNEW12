<div class="flex flex-col gap-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Setup Izin</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Konfigurasi global pengaturan izin</p>
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
            <!-- Izin Sakit -->
            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Izin Sakit</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                        <span class="text-zinc-600 dark:text-zinc-400">Minimum hari</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->h_min_izin_sakit }} hari</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                        <span class="text-zinc-600 dark:text-zinc-400">Max per tahun</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->max_izin_sakit_per_tahun }} hari</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-zinc-600 dark:text-zinc-400">Surat dokter dari hari ke</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->hari_ke_berapa_perlu_dokter }}</span>
                    </div>
                </div>
            </div>

            <!-- Izin Penting -->
            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Izin Penting</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                        <span class="text-zinc-600 dark:text-zinc-400">Minimum hari</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->h_min_izin_penting }} hari</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-zinc-600 dark:text-zinc-400">Max per tahun</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->max_izin_penting_per_tahun }} hari</span>
                    </div>
                </div>
            </div>

            <!-- Izin Ibadah -->
            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Izin Ibadah</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                        <span class="text-zinc-600 dark:text-zinc-400">Minimum hari</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->h_min_izin_ibadah }} hari</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-zinc-600 dark:text-zinc-400">Max per tahun</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->max_hari_ibadah_per_tahun }} hari</span>
                    </div>
                </div>
            </div>

            <!-- Aturan Umum -->
            <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-4">Aturan Umum</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-zinc-200 dark:border-zinc-700">
                        <span class="text-zinc-600 dark:text-zinc-400">Tidak hitung libnas</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->tidak_hitung_libnas ? 'Ya' : 'Tidak' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-zinc-600 dark:text-zinc-400">Tidak hitung libur unit</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $setup->tidak_hitung_libur_unit ? 'Ya' : 'Tidak' }}</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12 border border-dashed border-zinc-300 dark:border-zinc-600 rounded-lg">
            <p class="text-zinc-500 dark:text-zinc-400 mb-4">Setup izin belum dikonfigurasi</p>
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
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Setup Izin</h2>
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
                    <!-- Izin Sakit -->
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Izin Sakit</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Minimum Hari</label>
                                <input type="number" wire:model="h_min_izin_sakit" class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white" />
                                @error('h_min_izin_sakit') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Max per Tahun</label>
                                <input type="number" wire:model="max_izin_sakit_per_tahun" class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white" />
                                @error('max_izin_sakit_per_tahun') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Surat Dokter dari Hari Ke</label>
                                <input type="number" wire:model="hari_ke_berapa_perlu_dokter" class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white" />
                                @error('hari_ke_berapa_perlu_dokter') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="sakit_perlu_surat_dokter" class="rounded" />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">Sakit memerlukan surat dokter</span>
                            </label>
                        </div>
                    </div>

                    <!-- Izin Penting & Ibadah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Izin Penting</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Minimum Hari</label>
                                    <input type="number" wire:model="h_min_izin_penting" class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white" />
                                    @error('h_min_izin_penting') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Max per Tahun</label>
                                    <input type="number" wire:model="max_izin_penting_per_tahun" class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white" />
                                    @error('max_izin_penting_per_tahun') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Izin Ibadah</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Minimum Hari</label>
                                    <input type="number" wire:model="h_min_izin_ibadah" class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white" />
                                    @error('h_min_izin_ibadah') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Max per Tahun</label>
                                    <input type="number" wire:model="max_hari_ibadah_per_tahun" class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white" />
                                    @error('max_hari_ibadah_per_tahun') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aturan Umum -->
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">Aturan Umum</h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="tidak_hitung_libnas" class="rounded" />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">Tidak menghitung libur nasional</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="tidak_hitung_libur_unit" class="rounded" />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">Tidak menghitung libur unit</span>
                            </label>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Catatan</label>
                        <textarea wire:model="catatan" rows="3" class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4">
                        <button wire:click="save" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">Simpan</button>
                        <button wire:click="closeModal" class="flex-1 px-4 py-2 border border-zinc-200 dark:border-zinc-700 text-zinc-900 dark:text-white rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-700 transition">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
