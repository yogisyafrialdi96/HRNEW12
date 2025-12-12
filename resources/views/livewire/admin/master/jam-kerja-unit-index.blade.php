<div class="flex flex-col gap-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Jam Kerja Unit</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Manajemen jam kerja per hari per unit</p>
        </div>
        <button
            wire:click="openModal"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition"
        >
            Tambah Jam Kerja
        </button>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input
            type="text"
            placeholder="Cari unit unit..."
            wire:model.live="search"
            class="px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"
        />
        <select
            wire:model.live="filterUnit"
            class="px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-white"
        >
            <option value="">Semua Unit</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
            @endforeach
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-lg">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800">
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Unit</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Hari</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Jam Masuk</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Jam Pulang</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-zinc-900 dark:text-white">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jamKerjas as $jam)
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <td class="px-6 py-3 font-medium text-zinc-900 dark:text-white">{{ $jam->unit->unit }}</td>
                        <td class="px-6 py-3 text-zinc-700 dark:text-zinc-300">{{ ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][$jam->hari_ke - 1] }}</td>
                        <td class="px-6 py-3 text-zinc-700 dark:text-zinc-300">{{ $jam->jam_masuk }}</td>
                        <td class="px-6 py-3 text-zinc-700 dark:text-zinc-300">{{ $jam->jam_pulang }}</td>
                        <td class="px-6 py-3">
                            @if($jam->is_libur)
                                <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Libur</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Kerja</span>
                            @endif
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex gap-2">
                                <button
                                    wire:click="edit({{ $jam->id }})"
                                    class="px-3 py-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 font-medium"
                                >
                                    Edit
                                </button>
                                <button
                                    wire:click="delete({{ $jam->id }})"
                                    onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()"
                                    class="px-3 py-1 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 font-medium"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                            Tidak ada jam kerja unit
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $jamKerjas->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-2xl w-full mx-4">
                <div class="border-b border-zinc-200 dark:border-zinc-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">{{ $isEditMode ? 'Edit' : 'Tambah' }} Jam Kerja Unit</h2>
                    <button
                        wire:click="closeModal"
                        class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Unit</label>
                            <select
                                wire:model="unit_id"
                                class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                                {{ $isEditMode ? 'disabled' : '' }}
                            >
                                <option value="">Pilih Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                @endforeach
                            </select>
                            @error('unit_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Hari</label>
                            <select
                                wire:model="hari_ke"
                                class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                                {{ $isEditMode ? 'disabled' : '' }}
                            >
                                @foreach($hari_list as $k => $v)
                                    <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('hari_ke') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Jam Masuk</label>
                            <input
                                type="time"
                                wire:model="jam_masuk"
                                class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                            />
                            @error('jam_masuk') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Jam Pulang</label>
                            <input
                                type="time"
                                wire:model="jam_pulang"
                                class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                            />
                            @error('jam_pulang') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Istirahat (menit)</label>
                            <input
                                type="number"
                                wire:model="jam_istirahat"
                                class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                            />
                            @error('jam_istirahat') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_libur" class="rounded" />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">Hari Libur</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_full_day" class="rounded" />
                                <span class="text-sm text-zinc-700 dark:text-zinc-300">Full Day</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-900 dark:text-white mb-2">Keterangan</label>
                        <textarea
                            wire:model="keterangan"
                            rows="2"
                            class="w-full px-4 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white"
                        ></textarea>
                    </div>

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
