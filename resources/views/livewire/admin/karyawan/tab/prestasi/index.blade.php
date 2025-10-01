<div>

    <div class="flex flex-column sm:flex-row flex-wrap sm:space-y-0 items-center justify-between">
        <div>
            <flux:heading size="lg">Prestasi</flux:heading>
            <flux:text>This Page Show List of Prestasi</flux:text>
        </div>

        <div>
            <button wire:click="create"
                class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-1 rounded-lg flex items-center justify-center transition duration-200 whitespace-nowrap">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                <span>Create</span>
            </button>
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
                        placeholder="Search Prestasi...">
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
                            wire:click="sortBy('nama_prestasi')">
                            <div class="flex items-center gap-2">
                                <span>Prestasi</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'nama_prestasi')
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
                            wire:click="sortBy('peringkat')">
                            <div class="flex items-center gap-2">
                                <span>Peringkat</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'peringkat')
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
                            wire:click="sortBy('tingkat')">
                            <div class="flex items-center gap-2">
                                <span>Tingkat</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'tingkat')
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
                            kategori</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lokasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            File</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($prestasis as $prestasi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $prestasis->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4 w-72">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 capitalize">
                                        {{ $prestasi->nama_prestasi }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $prestasi->peringkatBadge['text'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $prestasi->tingkat ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $prestasi->kategori ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $prestasi->tanggal ? \Carbon\Carbon::parse($prestasi->tanggal)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $prestasi->lokasi ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if ($prestasi->document_path)
                                    <!-- Tombol untuk membuka modal preview -->
                                    <button
                                        wire:click="$dispatch('preview-file', { url: '{{ Storage::url($prestasi->document_path) }}' })"
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
                                    <button wire:click="showDetail({{ $prestasi->id }})"
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
                                    <button wire:click="edit({{ $prestasi->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-50 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $prestasi->id }})"
                                        class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">No prestasi found</p>
                                    <p class="text-sm">Get started by creating a new prestasi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-3 px-4 text-xs">
                {{ $prestasis->links() }}
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
                                    {{ $isEdit ? 'Edit Prestasi' : 'Tambah Prestasi' }}
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
                                <!-- Nama Prestasi -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nama Prestasi <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model.live="nama_prestasi" type="text"
                                        placeholder="e.g. Guru Terbaik, etc."
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    @error('nama_prestasi')
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
                                    <!--Tingkat Lomba -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Lomba
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="tingkat"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Tingkat
                                                </option>
                                                @foreach (\App\Models\Employee\KaryawanPrestasi::TINGKAT_LOMBA as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('tingkat')
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

                                    <!--Peringkat-->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Peringkat
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="peringkat"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Peringkat
                                                </option>
                                                @foreach (\App\Models\Employee\KaryawanPrestasi::PERINGKAT_LOMBA as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('peringkat')
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

                                    <!--Kategori-->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="kategori"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Kategori
                                                </option>
                                                @foreach (\App\Models\Employee\KaryawanPrestasi::KATEGORI_LOMBA as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('kategori')
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
                                            placeholder="e.g. Kemendikbud, etc."
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

                                    <!-- Tanggal Pelaksanaan -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Tanggal Lomba <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="tanggal" type="date"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tanggal')
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
                                        {{ $isEdit ? 'Update Prestasi' : 'Simpan Prestasi' }}
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
                                    d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                            </svg>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Prestasi Karyawan</h2>
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
                    @if (!empty($selectedPrestasi))
                        <!-- Achievement Header -->
                        <div class="mb-6 p-6 bg-gradient-to-r from-amber-50 via-yellow-50 to-orange-50 dark:from-amber-900/20 dark:via-yellow-900/20 dark:to-orange-900/20 rounded-lg border-2 border-amber-200 dark:border-amber-800 relative overflow-hidden">
                            <!-- Trophy Background Pattern -->
                            <div class="absolute top-0 right-0 opacity-10">
                                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                                </svg>
                            </div>
                            
                            <div class="flex items-start justify-between relative z-10">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <!-- Trophy Icon -->
                                        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center
                                            @if($selectedPrestasi->peringkat === 'juara 1') bg-yellow-400 text-yellow-900
                                            @elseif($selectedPrestasi->peringkat === 'juara 2') bg-gray-300 text-gray-700
                                            @elseif($selectedPrestasi->peringkat === 'juara 3') bg-amber-600 text-amber-100
                                            @else bg-blue-100 text-blue-600 @endif">
                                            @if(in_array($selectedPrestasi->peringkat, ['juara 1', 'juara 2', 'juara 3']))
                                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                                                </svg>
                                            @else
                                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                                </svg>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                                {{ $selectedPrestasi->nama_prestasi ?? 'Tidak Diketahui' }}
                                            </h3>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full
                                                    @if($selectedPrestasi->peringkat === 'juara 1') bg-yellow-400 text-yellow-900
                                                    @elseif($selectedPrestasi->peringkat === 'juara 2') bg-gray-300 text-gray-700
                                                    @elseif($selectedPrestasi->peringkat === 'juara 3') bg-amber-600 text-white
                                                    @elseif(in_array($selectedPrestasi->peringkat, ['harapan 1', 'harapan 2', 'harapan 3'])) bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                    @if($selectedPrestasi->peringkat === 'juara 1')
                                                        🥇
                                                    @elseif($selectedPrestasi->peringkat === 'juara 2')
                                                        🥈
                                                    @elseif($selectedPrestasi->peringkat === 'juara 3')
                                                        🥉
                                                    @else
                                                        ⭐
                                                    @endif
                                                    {{ ucwords(str_replace('_', ' ', $selectedPrestasi->peringkat)) }}
                                                </span>
                                                
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                                    @if($selectedPrestasi->tingkat === 'internasional') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400
                                                    @elseif($selectedPrestasi->tingkat === 'nasional') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                                    @elseif($selectedPrestasi->tingkat === 'regional') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                                    @elseif($selectedPrestasi->tingkat === 'lokal') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                    @if($selectedPrestasi->tingkat === 'internasional')
                                                        🌍
                                                    @elseif($selectedPrestasi->tingkat === 'nasional')
                                                        🇮🇩
                                                    @elseif($selectedPrestasi->tingkat === 'regional')
                                                        🏙️
                                                    @elseif($selectedPrestasi->tingkat === 'lokal')
                                                        🏘️
                                                    @endif
                                                    {{ ucfirst($selectedPrestasi->tingkat) }}
                                                </span>
                                                
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                                    @if($selectedPrestasi->kategori === 'individu') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400
                                                    @elseif($selectedPrestasi->kategori === 'tim') bg-cyan-100 text-cyan-800 dark:bg-cyan-900/20 dark:text-cyan-400
                                                    @elseif($selectedPrestasi->kategori === 'organisasi') bg-teal-100 text-teal-800 dark:bg-teal-900/20 dark:text-teal-400
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                    @if($selectedPrestasi->kategori === 'individu')
                                                        👤
                                                    @elseif($selectedPrestasi->kategori === 'tim')
                                                        👥
                                                    @elseif($selectedPrestasi->kategori === 'organisasi')
                                                        🏢
                                                    @endif
                                                    {{ ucfirst($selectedPrestasi->kategori) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-1 mt-3">
                                        <p class="text-amber-700 dark:text-amber-400 font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $selectedPrestasi->penyelenggara ?? 'Tidak Diketahui' }}
                                        </p>
                                        @if($selectedPrestasi->lokasi)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $selectedPrestasi->lokasi }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($selectedPrestasi->tanggal)
                                    <div class="flex-shrink-0 ml-4 text-right">
                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 shadow-md">
                                            <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">
                                                {{ \Carbon\Carbon::parse($selectedPrestasi->tanggal)->format('d') }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($selectedPrestasi->tanggal)->format('M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($selectedPrestasi->tanggal)->locale('id')->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Achievement Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Achievement Details -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Detail Prestasi</h4>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Prestasi</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $selectedPrestasi->nama_prestasi ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Peringkat</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex items-center px-3 py-1 text-sm font-bold rounded-full
                                                @if($selectedPrestasi->peringkat === 'juara 1') bg-yellow-400 text-yellow-900
                                                @elseif($selectedPrestasi->peringkat === 'juara 2') bg-gray-300 text-gray-700
                                                @elseif($selectedPrestasi->peringkat === 'juara 3') bg-amber-600 text-white
                                                @elseif(in_array($selectedPrestasi->peringkat, ['harapan 1', 'harapan 2', 'harapan 3'])) bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                                @elseif($selectedPrestasi->peringkat === 'nominasi') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                {{ ucwords(str_replace('_', ' ', $selectedPrestasi->peringkat)) }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tingkat</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                                @if($selectedPrestasi->tingkat === 'internasional') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400
                                                @elseif($selectedPrestasi->tingkat === 'nasional') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                                @elseif($selectedPrestasi->tingkat === 'regional') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                                @elseif($selectedPrestasi->tingkat === 'lokal') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                {{ ucfirst($selectedPrestasi->tingkat) }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                                @if($selectedPrestasi->kategori === 'individu') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400
                                                @elseif($selectedPrestasi->kategori === 'tim') bg-cyan-100 text-cyan-800 dark:bg-cyan-900/20 dark:text-cyan-400
                                                @elseif($selectedPrestasi->kategori === 'organisasi') bg-teal-100 text-teal-800 dark:bg-teal-900/20 dark:text-teal-400
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                {{ ucfirst($selectedPrestasi->kategori) }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Details -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Detail Acara</h4>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Penyelenggara</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedPrestasi->penyelenggara ?? 'N/A' }}</dd>
                                    </div>
                                    @if($selectedPrestasi->lokasi)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lokasi</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $selectedPrestasi->lokasi }}
                                            </dd>
                                        </div>
                                    @endif
                                    @if($selectedPrestasi->tanggal)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($selectedPrestasi->tanggal)->format('d F Y') }}
                                                </div>
                                                <span class="text-xs text-gray-500 ml-6">
                                                    ({{ \Carbon\Carbon::parse($selectedPrestasi->tanggal)->locale('id')->diffForHumans() }})
                                                </span>
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Achievement Stats -->
                        <div class="mt-6 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg border border-amber-200 dark:border-amber-800 p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Ringkasan Prestasi
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">
                                        @if($selectedPrestasi->peringkat === 'juara 1')
                                            🥇
                                        @elseif($selectedPrestasi->peringkat === 'juara 2')
                                            🥈
                                        @elseif($selectedPrestasi->peringkat === 'juara 3')
                                            🥉
                                        @else
                                            ⭐
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ ucwords($selectedPrestasi->peringkat) }}</div>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">
                                        @if($selectedPrestasi->tingkat === 'internasional')
                                            🌍
                                        @elseif($selectedPrestasi->tingkat === 'nasional')
                                            🇮🇩
                                        @elseif($selectedPrestasi->tingkat === 'regional')
                                            🏙️
                                        @else
                                            🏘️
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ ucfirst($selectedPrestasi->tingkat) }}</div>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                    <div class="text-3xl font-bold text-amber-600 dark:text-amber-400">
                                        @if($selectedPrestasi->kategori === 'individu')
                                            👤
                                        @elseif($selectedPrestasi->kategori === 'tim')
                                            👥
                                        @else
                                            🏢
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ ucfirst($selectedPrestasi->kategori) }}</div>
                                </div>
                                <div class="text-center p-3 bg-white dark:bg-gray-800 rounded-lg">
                                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                                        {{ \Carbon\Carbon::parse($selectedPrestasi->tanggal)->format('Y') }}
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tahun</div>
                                </div>
                            </div>
                        </div>

                        <!-- Achievement Impact -->
                        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <h4 class="font-semibold text-gray-900 dark:text-white">Tingkat Prestasi</h4>
                            </div>
                            <div class="p-4">
                                <div class="space-y-3">
                                    @php
                                        $tingkatScore = [
                                            'internasional' => 100,
                                            'nasional' => 75,
                                            'regional' => 50,
                                            'lokal' => 25
                                        ];
                                        $peringkatScore = [
                                            'juara 1' => 100,
                                            'juara 2' => 85,
                                            'juara 3' => 70,
                                            'harapan 1' => 60,
                                            'harapan 2' => 50,
                                            'harapan 3' => 40,
                                            'nominasi' => 30,
                                            'partisipasi' => 20
                                        ];
                                        
                                        $tingkatPercent = $tingkatScore[$selectedPrestasi->tingkat] ?? 0;
                                        $peringkatPercent = $peringkatScore[$selectedPrestasi->peringkat] ?? 0;
                                        $overallScore = ($tingkatPercent + $peringkatPercent) / 2;
                                    @endphp
                                    
                                    <!-- Tingkat Bar -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Cakupan Tingkat</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $tingkatPercent }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                            <div class="h-2 rounded-full transition-all duration-300
                                                @if($selectedPrestasi->tingkat === 'internasional') bg-purple-500
                                                @elseif($selectedPrestasi->tingkat === 'nasional') bg-red-500
                                                @elseif($selectedPrestasi->tingkat === 'regional') bg-blue-500
                                                @else bg-green-500 @endif"
                                                style="width: {{ $tingkatPercent }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Peringkat Bar -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pencapaian Peringkat</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $peringkatPercent }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                            <div class="h-2 rounded-full transition-all duration-300
                                                @if(in_array($selectedPrestasi->peringkat, ['juara 1', 'juara 2', 'juara 3'])) bg-amber-500
                                                @elseif(in_array($selectedPrestasi->peringkat, ['harapan 1', 'harapan 2', 'harapan 3'])) bg-blue-500
                                                @else bg-gray-400 @endif"
                                                style="width: {{ $peringkatPercent }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Overall Score -->
                                    <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Skor Keseluruhan</span>
                                            <span class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ number_format($overallScore, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3 dark:bg-gray-700">
                                            <div class="h-3 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 transition-all duration-300"
                                                style="width: {{ $overallScore }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                            @if($overallScore >= 85)
                                                Prestasi Luar Biasa! 🏆
                                            @elseif($overallScore >= 70)
                                                Prestasi Sangat Baik! 🌟
                                            @elseif($overallScore >= 50)
                                                Prestasi Baik! ⭐
                                            @else
                                                Prestasi Mengagumkan! ✨
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        @if($selectedPrestasi->keterangan)
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Keterangan</h4>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {{ $selectedPrestasi->keterangan }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Congratulations Message -->
                        @if(in_array($selectedPrestasi->peringkat, ['juara 1', 'juara 2', 'juara 3']))
                            <div class="mt-6 bg-gradient-to-r from-yellow-50 via-amber-50 to-orange-50 dark:from-yellow-900/20 dark:via-amber-900/20 dark:to-orange-900/20 border-2 border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-bold text-yellow-800 dark:text-yellow-200">
                                            🎉 Selamat atas Prestasinya!
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p>Pencapaian yang luar biasa! {{ ucwords($selectedPrestasi->peringkat) }} di tingkat {{ $selectedPrestasi->tingkat }} adalah bukti dedikasi dan kerja keras yang patut diapresiasi.</p>
                                        </div>
                                    </div>
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
                                            {{ $selectedPrestasi->created_at ? \Carbon\Carbon::parse($selectedPrestasi->created_at)->format('d F Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    @if($selectedPrestasi->created_by)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedPrestasi->creator?->name ?? $selectedPrestasi->created_by }}
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui</dt>
                                        <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                            {{ $selectedPrestasi->updated_at ? \Carbon\Carbon::parse($selectedPrestasi->updated_at)->format('d F Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    @if($selectedPrestasi->updated_by)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui Oleh</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedPrestasi->updater?->name ?? $selectedPrestasi->updated_by }}
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
