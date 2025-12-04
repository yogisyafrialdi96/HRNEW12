<div>

    <div class="flex flex-column sm:flex-row flex-wrap sm:space-y-0 items-center justify-between">
        <div>
            <flux:heading size="lg">Pelatihan</flux:heading>
            <flux:text>This Page Show List of Pelatihan</flux:text>
        </div>

        <div>
            @can('karyawan_pelatihan.create')
                <button wire:click="create"
                    class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-1 rounded-lg flex items-center justify-center transition duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    <span>Create</span>
                </button>
            @else
                <div class="text-sm text-gray-500 italic">No permission to create Pelatihan</div>
            @endcan
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
        <div class="relative overflow-x-auto mt-4 shadow-md sm:rounded-lg">
            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between p-3">
                <div>
                    <select wire:model.live.live="perPage"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="10">10/page</option>
                        <option value="25">25/page</option>
                        <option value="50">50/page</option>
                    </select>
                </div>
                <!-- Search and Per Page Row -->
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.live="search"
                        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search Pelatihan...">
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- No - Fixed width -->
                        <th
                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 w-16">
                            <div class="flex items-center gap-2">
                                <span>No</span>
                            </div>
                        </th>
                        <th class="px-1 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('nama_pelatihan')">
                            <div class="flex items-center gap-2">
                                <span>Pelatihan</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'nama_pelatihan')
                                        @if ($sortDirection === 'asc')
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('penyelenggara')">
                            <div class="flex items-center gap-2">
                                <span>Penyelenggara</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'penyelenggara')
                                        @if ($sortDirection === 'asc')
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            T.Mulai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            T.Selesai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            File</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pelatihans as $pelatihan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $pelatihans->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4 w-72">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 capitalize">
                                        {{ $pelatihan->nama_pelatihan }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $pelatihan->penyelenggara ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $pelatihan->jenis_pelatihan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pelatihan->tgl_mulai ? \Carbon\Carbon::parse($pelatihan->tgl_mulai)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pelatihan->tgl_selesai ? \Carbon\Carbon::parse($pelatihan->tgl_selesai)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if ($pelatihan->document_path)
                                    <!-- Tombol untuk membuka modal preview -->
                                    <button
                                        wire:click="$dispatch('preview-file', { url: '{{ Storage::url($pelatihan->document_path) }}' })"
                                        class="text-green-600 hover:text-green-800" title="Preview dokumen">
                                        <!-- Ikon kustom dokumen -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125
                                        1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25
                                        0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0
                                        .621.504 1.125 1.125 1.125h12.75c.621 0
                                        1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </button>
                                @else
                                    <!-- Ikon merah jika tidak ada file -->
                                    <span class="text-red-500" title="Tidak ada file">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125
                                        1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25
                                        0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0
                                        .621.504 1.125 1.125 1.125h12.75c.621 0
                                        1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <!-- Detail Button -->
                                    <button wire:click="showDetail({{ $pelatihan->id }})"
                                        class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50"
                                        title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                    @can('karyawan_pelatihan.edit')
                                    <button wire:click="edit({{ $pelatihan->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-50 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    @endcan
                                    @can('karyawan_pelatihan.delete')
                                    <button wire:click="confirmDelete({{ $pelatihan->id }})"
                                        class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">No pelatihan found</p>
                                    <p class="text-sm">Get started by creating a new pelatihan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-3 px-4 text-xs">
                {{ $pelatihans->links() }}
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        @teleport('body')
            <div x-data="{
                init() {
                        // Lock body scroll when modal opens
                        document.body.style.overflow = 'hidden';
                        document.body.classList.add('modal-open');
                    },
                    destroy() {
                        // Restore body scroll when modal closes
                        document.body.style.overflow = '';
                        document.body.classList.remove('modal-open');
                    }
            }" x-init="init()" x-on:destroy="destroy()"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[99999] flex items-center justify-center px-4 py-8 sm:py-12">

                <div x-transition:enter="transition ease-out duration-300 delay-100"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95" @click.away="$wire.closeModal()"
                    class="relative w-full max-w-lg mx-auto my-auto">

                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <!-- Header -->
                        <div
                            class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $isEdit ? 'Edit Sertifikasi' : 'Tambah Sertifikasi' }}
                                </h3>
                                <button wire:click="closeModal"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form -->
                        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                            <form wire:submit.prevent="save" enctype="multipart/form-data" class="space-y-5">
                                <!-- Nama Pelatihan -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nama Pelatihan <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model.live="nama_pelatihan" type="text"
                                        placeholder="e.g. Leadership Training, etc"
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    @error('nama_pelatihan')
                                        <p class="text-xs text-red-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Lembaga Pelatihan -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelatihan
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="jenis_pelatihan"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Jenis Pelatihan
                                                </option>
                                                @foreach (\App\Models\Employee\KaryawanPelatihan::JENIS_PELATIHAN as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('jenis_pelatihan')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Penyelenaggara -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Penyelenggara <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="penyelenggara" type="text"
                                            placeholder="e.g. Lembaga Pelatihan XYZ"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('penyelenggara')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Mulai -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Tanggal Mulai <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="tgl_mulai" type="date"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tgl_mulai')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Selesai -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Tanggal Selesai <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="tgl_selesai" type="date"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tgl_selesai')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Lokasi -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Lokasi <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="lokasi" type="text"
                                            placeholder="Masukkan Lokasi"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('lokasi')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Dapat Sertifikat -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Dapat Sertifikat? <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center cursor-pointer">
                                                <input wire:model.live="sertifikat_diperoleh" type="radio" value="1"
                                                    class="sr-only peer">
                                                <div
                                                    class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-50 relative">
                                                    <div
                                                        class="absolute inset-0.5 bg-green-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity">
                                                    </div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-700">Ya</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input wire:model.live="sertifikat_diperoleh" type="radio" value="0"
                                                    class="sr-only peer">
                                                <div
                                                    class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-red-500 peer-checked:bg-red-50 relative">
                                                    <div
                                                        class="absolute inset-0.5 bg-red-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity">
                                                    </div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-700">Tidak</span>
                                            </label>
                                        </div>
                                        @error('sertifikat_diperoleh')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Keterangan -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                                    <textarea wire:model.live="keterangan" rows="3" placeholder="Keterangan..."
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                                    @error('keterangan')
                                        <p class="text-xs text-red-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Document Path Upload -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Dokumen <span class="text-red-500">*</span>
                                    </label>

                                    <!-- File Upload Area -->
                                    <div class="relative">
                                        <input wire:model.live="document" type="file"
                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                            id="document_upload">

                                        <!-- Custom Upload Button -->
                                        <div
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-400 dark:hover:border-blue-500 transition-all cursor-pointer">
                                            <div class="flex items-center justify-center space-x-3">
                                                <!-- Upload Icon -->
                                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>

                                                <!-- Upload Text -->
                                                <div class="text-center">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                                        <span
                                                            class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">Klik
                                                            untuk upload</span>
                                                        atau drag & drop
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                        PDF, JPG, PNG, DOC, DOCX (Max. 2MB)
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Preview (New Upload) -->
                                    @if ($document && is_object($document))
                                        <div
                                            class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <!-- File Icon -->
                                                    <div class="flex-shrink-0">
                                                        @if (in_array(pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                                            <!-- Image Icon -->
                                                            <svg class="w-8 h-8 text-green-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        @elseif(pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION) == 'pdf')
                                                            <!-- PDF Icon -->
                                                            <svg class="w-8 h-8 text-red-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            <!-- Document Icon -->
                                                            <svg class="w-8 h-8 text-blue-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    <!-- File Info -->
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $document->getClientOriginalName() }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ round($document->getSize() / 1024, 2) }} KB
                                                        </p>
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 mt-1">
                                                            File baru
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Remove Button -->
                                                <button wire:click="$set('document', null)" type="button"
                                                    class="p-1 text-gray-400 hover:text-red-500 transition-colors"
                                                    title="Hapus file">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Existing Document Preview (For Edit Mode) -->
                                    @if ($document_path && !$document)
                                        <div
                                            class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <!-- File Icon Based on Extension -->
                                                    <div class="flex-shrink-0">
                                                        @php
                                                            $extension = strtolower(
                                                                pathinfo($document_path, PATHINFO_EXTENSION),
                                                            );
                                                            $filename = basename($document_path);
                                                        @endphp

                                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                            <!-- Image Icon -->
                                                            <svg class="w-8 h-8 text-green-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        @elseif($extension == 'pdf')
                                                            <!-- PDF Icon -->
                                                            <svg class="w-8 h-8 text-red-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        @else
                                                            <!-- Document Icon -->
                                                            <svg class="w-8 h-8 text-blue-500" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 4a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    <!-- File Info -->
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $filename }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            Dokumen tersimpan
                                                        </p>
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 mt-1">
                                                            File tersimpan
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="flex items-center space-x-2">
                                                    <!-- View/Download Button -->
                                                    <a href="{{ asset('storage/' . $document_path) }}" target="_blank"
                                                        class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-100 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                        title="Lihat dokumen">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                            </path>
                                                        </svg>
                                                    </a>

                                                    <!-- Remove Button -->
                                                    <button wire:click="removeExistingDocument" type="button"
                                                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-100 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                        title="Hapus dokumen">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"
                                                                clip-rule="evenodd"></path>
                                                            <path fill-rule="evenodd"
                                                                d="M4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 3a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 001-1v-3a1 1 0 000-2H7z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Upload Progress (if uploading) -->
                                    <div wire:loading wire:target="document" class="mt-3">
                                        <div
                                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                            <div class="flex items-center space-x-3">
                                                <!-- Loading Spinner -->
                                                <svg class="animate-spin w-5 h-5 text-blue-500" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                <span class="text-sm text-blue-600 dark:text-blue-400">Mengupload
                                                    file...</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Error Message -->
                                    @error('document')
                                        <p class="text-xs text-red-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </form>
                        </div>

                        <!-- Footer -->
                        <div class="py-4 px-6">
                            <div class="flex flex-col-reverse sm:flex-row gap-3 justify-end">
                                <button type="button" wire:click="closeModal"
                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-200 dark:border-gray-500 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 focus:ring-2 focus:ring-gray-500/20 transition-all">
                                    Batal
                                </button>
                                <button type="submit" wire:click="save"
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:ring-2 focus:ring-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg hover:shadow-xl"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $isEdit ? 'Update Sertifikasi' : 'Simpan Sertifikasi' }}
                                    </span>
                                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 2v4m0 12v4m8-8h-4M6 12H2m15.364-6.364l-2.828 2.828M9.464 16.536l-2.828 2.828m9.192-9.192l-2.828 2.828M6.464 6.464L3.636 3.636">
                                            </path>
                                        </svg>
                                        {{ $isEdit ? 'Mengupdate...' : 'Menyimpan...' }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endteleport

        <!-- CSS untuk modal -->
        <style>
            .modal-open {
                overflow: hidden !important;
            }

            /* Custom scrollbar untuk form content */
            .max-h-\[70vh\]::-webkit-scrollbar {
                width: 4px;
            }

            .max-h-\[70vh\]::-webkit-scrollbar-track {
                background: #f1f5f9;
            }

            .max-h-\[70vh\]::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 2px;
            }

            .max-h-\[70vh\]::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* Dark mode scrollbar */
            .dark .max-h-\[70vh\]::-webkit-scrollbar-track {
                background: #374151;
            }

            .dark .max-h-\[70vh\]::-webkit-scrollbar-thumb {
                background: #6b7280;
            }

            .dark .max-h-\[70vh\]::-webkit-scrollbar-thumb:hover {
                background: #9ca3af;
            }
        </style>
    @endif

    
<!-- Detail modal -->
@if ($showModalDetail)
    @teleport('body')
        <div x-data="{
            init() {
                    // Lock body scroll when modal opens
                    document.body.style.overflow = 'hidden';
                    document.body.classList.add('modal-open');
                },
                destroy() {
                    // Restore body scroll when modal closes
                    document.body.style.overflow = '';
                    document.body.classList.remove('modal-open');
                }
        }" x-init="init()" x-on:destroy="destroy()"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[99999] flex items-center justify-center px-4 py-8 sm:py-12">

            <div x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.away="$wire.closeModal()"
                class="relative w-full max-w-4xl mx-auto my-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg flex flex-col max-h-[90vh]">

                <!-- Header -->
                <div
                    class="bg-white dark:bg-gray-800 flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 rounded-t-lg">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443a55.381 55.381 0 0 1 5.25 2.882V15" />
                            </svg>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Pelatihan Karyawan</h2>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button wire:click="closeModal"
                            class="p-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="flex-1 overflow-y-auto p-4">
                    @if (!empty($selectedPelatihan))
                        <!-- Training Header -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 rounded-lg border border-violet-200 dark:border-violet-800">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $selectedPelatihan->nama_pelatihan ?? 'Tidak Diketahui' }}
                                        </h3>
                                        @if($selectedPelatihan->jenis_pelatihan)
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full
                                                @if($selectedPelatihan->jenis_pelatihan === 'internal') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                                @elseif($selectedPelatihan->jenis_pelatihan === 'eksternal') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                                @elseif($selectedPelatihan->jenis_pelatihan === 'online') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400
                                                @elseif($selectedPelatihan->jenis_pelatihan === 'offline') bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400
                                                @elseif($selectedPelatihan->jenis_pelatihan === 'hybrid') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                @if($selectedPelatihan->jenis_pelatihan === 'internal')
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                @elseif($selectedPelatihan->jenis_pelatihan === 'eksternal')
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                                                    </svg>
                                                @elseif($selectedPelatihan->jenis_pelatihan === 'online')
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                @elseif($selectedPelatihan->jenis_pelatihan === 'offline')
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                @elseif($selectedPelatihan->jenis_pelatihan === 'hybrid')
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                @endif
                                                {{ ucfirst($selectedPelatihan->jenis_pelatihan) }}
                                            </span>
                                        @endif
                                        @if($selectedPelatihan->sertifikat_diperoleh)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                Bersertifikat
                                            </span>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-violet-600 dark:text-violet-400 font-medium">
                                            🏛️ {{ $selectedPelatihan->penyelenggara ?? 'Tidak Diketahui' }}
                                        </p>
                                        @if($selectedPelatihan->lokasi)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                📍 {{ $selectedPelatihan->lokasi }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if($selectedPelatihan->tgl_mulai || $selectedPelatihan->tgl_selesai)
                                    <div class="flex-shrink-0 ml-4 text-right">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            @if($selectedPelatihan->tgl_mulai)
                                                <div>Mulai: {{ \Carbon\Carbon::parse($selectedPelatihan->tgl_mulai)->format('d M Y') }}</div>
                                            @endif
                                            @if($selectedPelatihan->tgl_selesai)
                                                <div>Selesai: {{ \Carbon\Carbon::parse($selectedPelatihan->tgl_selesai)->format('d M Y') }}</div>
                                            @else
                                                <div class="text-blue-600 dark:text-blue-400 font-medium">Berlangsung</div>
                                            @endif
                                            @if($selectedPelatihan->tgl_mulai && $selectedPelatihan->tgl_selesai)
                                                @php
                                                    $start = \Carbon\Carbon::parse($selectedPelatihan->tgl_mulai);
                                                    $end = \Carbon\Carbon::parse($selectedPelatihan->tgl_selesai);
                                                    $duration = $start->diffInDays($end) + 1;
                                                @endphp
                                                <div class="text-xs text-gray-500 mt-1">
                                                    ({{ $duration }} hari)
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Training Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Pelatihan</h4>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Pelatihan</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $selectedPelatihan->nama_pelatihan ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Penyelenggara</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedPelatihan->penyelenggara ?? 'N/A' }}</dd>
                                    </div>
                                    @if($selectedPelatihan->jenis_pelatihan)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Pelatihan</dt>
                                            <dd class="mt-1">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($selectedPelatihan->jenis_pelatihan === 'internal') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                                    @elseif($selectedPelatihan->jenis_pelatihan === 'eksternal') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                                    @elseif($selectedPelatihan->jenis_pelatihan === 'online') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400
                                                    @elseif($selectedPelatihan->jenis_pelatihan === 'offline') bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400
                                                    @elseif($selectedPelatihan->jenis_pelatihan === 'hybrid') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                    {{ ucfirst($selectedPelatihan->jenis_pelatihan) }}
                                                </span>
                                            </dd>
                                        </div>
                                    @endif
                                    @if($selectedPelatihan->lokasi)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lokasi</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $selectedPelatihan->lokasi }}
                                            </dd>
                                        </div>
                                    @endif
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Sertifikat</dt>
                                        <dd class="mt-1">
                                            @if($selectedPelatihan->sertifikat_diperoleh)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    Sertifikat Diperoleh
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                    </svg>
                                                    Tanpa Sertifikat
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule Information -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Jadwal Pelatihan</h4>
                                </div>
                                <div class="p-4 space-y-3">
                                    @if($selectedPelatihan->tgl_mulai)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($selectedPelatihan->tgl_mulai)->format('d F Y') }}
                                                    <span class="ml-2 text-xs text-gray-500">
                                                        ({{ \Carbon\Carbon::parse($selectedPelatihan->tgl_mulai)->locale('id')->diffForHumans() }})
                                                    </span>
                                                </div>
                                            </dd>
                                        </div>
                                    @endif
                                    @if($selectedPelatihan->tgl_selesai)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($selectedPelatihan->tgl_selesai)->format('d F Y') }}
                                                    <span class="ml-2 text-xs text-gray-500">
                                                        ({{ \Carbon\Carbon::parse($selectedPelatihan->tgl_selesai)->locale('id')->diffForHumans() }})
                                                    </span>
                                                </div>
                                            </dd>
                                        </div>
                                    @else
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                            <dd class="mt-1">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                                    <svg class="w-3 h-3 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Sedang Berlangsung
                                                </span>
                                            </dd>
                                        </div>
                                    @endif
                                    @if($selectedPelatihan->tgl_mulai && $selectedPelatihan->tgl_selesai)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Durasi</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                @php
                                                    $start = \Carbon\Carbon::parse($selectedPelatihan->tgl_mulai);
                                                    $end = \Carbon\Carbon::parse($selectedPelatihan->tgl_selesai);
                                                    $duration = $start->diffInDays($end) + 1;
                                                    $hours = $duration * 8; // Asumsi 8 jam per hari
                                                @endphp
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $duration }} hari (≈{{ $hours }} jam)
                                                </div>
                                            </dd>
                                        </div>
                                    @endif
                                    @if($selectedPelatihan->tgl_mulai && $selectedPelatihan->tgl_selesai)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pelatihan</dt>
                                            <dd class="mt-1">
                                                @php
                                                    $now = now();
                                                    $start = \Carbon\Carbon::parse($selectedPelatihan->tgl_mulai);
                                                    $end = \Carbon\Carbon::parse($selectedPelatihan->tgl_selesai);
                                                @endphp
                                                @if($now->isBefore($start))
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Selesai
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Progress Timeline -->
                        @if($selectedPelatihan->tgl_mulai)
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Timeline Pelatihan</h4>
                                </div>
                                <div class="p-4">
                                    @php
                                        $now = now();
                                        $start = \Carbon\Carbon::parse($selectedPelatihan->tgl_mulai);
                                        $end = $selectedPelatihan->tgl_selesai ? \Carbon\Carbon::parse($selectedPelatihan->tgl_selesai) : null;
                                        
                                        if ($end) {
                                            if ($now->isBefore($start)) {
                                                $progress = 0;
                                                $status = 'upcoming';
                                            } elseif ($now->isAfter($end)) {
                                                $progress = 100;
                                                $status = 'completed';
                                            } else {
                                                $totalDays = $start->diffInDays($end);
                                                $passedDays = $start->diffInDays($now);
                                                $progress = $totalDays > 0 ? ($passedDays / $totalDays) * 100 : 0;
                                                $status = 'ongoing';
                                            }
                                        } else {
                                            $progress = $now->isBefore($start) ? 0 : 50;
                                            $status = $now->isBefore($start) ? 'upcoming' : 'ongoing';
                                        }
                                    @endphp
                                    
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                                @if($status === 'completed') bg-green-100 text-green-600
                                                @elseif($status === 'ongoing') bg-blue-100 text-blue-600
                                                @else bg-gray-100 text-gray-600 @endif">
                                                @if($status === 'completed')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif($status === 'ongoing')
                                                    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Progress Pelatihan</span>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($progress, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                                <div class="h-2 rounded-full transition-all duration-300
                                                    @if($status === 'completed') bg-green-500
                                                    @elseif($status === 'ongoing') bg-blue-500
                                                    @else bg-gray-400 @endif"
                                                    style="width: {{ $progress }}%"></div>
                                            </div>
                                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <span>{{ $start->format('d M Y') }}</span>
                                                @if($end)
                                                    <span>{{ $end->format('d M Y') }}</span>
                                                @else
                                                    <span>Ongoing</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Document -->
                        @if($selectedPelatihan->document_path)
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Dokumen Pelatihan</h4>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                @if($selectedPelatihan->sertifikat_diperoleh)
                                                    Sertifikat {{ $selectedPelatihan->nama_pelatihan }}
                                                @else
                                                    Dokumen {{ $selectedPelatihan->nama_pelatihan }}
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ basename($selectedPelatihan->document_path) }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <a href="{{ asset('storage/' . $selectedPelatihan->document_path) }}" 
                                               target="_blank"
                                               class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                @if($selectedPelatihan->sertifikat_diperoleh)
                                                    Lihat Sertifikat
                                                @else
                                                    Lihat Dokumen
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Training Summary -->
                        @if($selectedPelatihan->tgl_mulai && $selectedPelatihan->tgl_selesai)
                            <div class="mt-6 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 rounded-lg border border-violet-200 dark:border-violet-800 p-4">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Ringkasan Pelatihan</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">
                                            @php
                                                $start = \Carbon\Carbon::parse($selectedPelatihan->tgl_mulai);
                                                $end = \Carbon\Carbon::parse($selectedPelatihan->tgl_selesai);
                                                $duration = $start->diffInDays($end) + 1;
                                            @endphp
                                            {{ $duration }}
                                        </div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Hari</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">
                                            {{ $duration * 8 }}
                                        </div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Jam (Perkiraan)</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">
                                            {{ ucfirst($selectedPelatihan->jenis_pelatihan ?? 'N/A') }}
                                        </div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Jenis</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-violet-600 dark:text-violet-400">
                                            @if($selectedPelatihan->sertifikat_diperoleh)
                                                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            @else
                                                <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">Sertifikat</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Notes -->
                        @if($selectedPelatihan->keterangan)
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Keterangan</h4>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {{ $selectedPelatihan->keterangan }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Log Information -->
                        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <h4 class="font-semibold text-gray-900 dark:text-white">Log Informasi</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
                                <div class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat</dt>
                                        <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                            {{ $selectedPelatihan->created_at ? \Carbon\Carbon::parse($selectedPelatihan->created_at)->format('d F Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    @if($selectedPelatihan->created_by)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedPelatihan->creator?->name ?? $selectedPelatihan->created_by }}
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui</dt>
                                        <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                            {{ $selectedPelatihan->updated_at ? \Carbon\Carbon::parse($selectedPelatihan->updated_at)->format('d F Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    @if($selectedPelatihan->updated_by)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui Oleh</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedPelatihan->updater?->name ?? $selectedPelatihan->updated_by }}
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="flex items-center justify-center py-12">
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">Memuat data...</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endteleport
@endif


    <!-- Modal Konfirmasi Floating (Tanpa overlay) -->
    <x-modal-confirmation.modal-confirm-delete wire:model.live="confirmingDelete" onConfirm="delete" />

    <!-- Modal Preview -->
    <x-modal-preview.preview-file-modal />
    {{-- end modal --}}
</div>
