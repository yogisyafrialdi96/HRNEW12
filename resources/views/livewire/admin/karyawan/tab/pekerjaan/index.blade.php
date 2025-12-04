<div>

    <div class="flex flex-column sm:flex-row flex-wrap sm:space-y-0 items-center justify-between">
        <div>
            <flux:heading size="lg">Pekerjaan</flux:heading>
            <flux:text>This Page Show List of Pekerjaan</flux:text>
        </div>

        <div>
            @can('karyawan_pekerjaan.create')
                <button wire:click="create"
                    class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-1 rounded-lg flex items-center justify-center transition duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    <span>Create</span>
                </button>
            @else
                <div class="text-sm text-gray-500 italic">No permission to create Pekerjaan</div>
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
                        placeholder="Search Pekerjaan...">
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
                            wire:click="sortBy('nama_instansi')">
                            <div class="flex items-center gap-2">
                                <span>Instansi</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'nama_instansi')
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
                            wire:click="sortBy('jabatan')">
                            <div class="flex items-center gap-2">
                                <span>Jabatan</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'jabatan')
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
                            Kontrak</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            T.Masuk</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            T.Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('status')">
                            <div class="flex items-center gap-2">
                                <span>Status</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'status')
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
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pekerjaans as $pekerjaan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $pekerjaans->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4 w-72">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 capitalize">
                                        {{ $pekerjaan->nama_instansi }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $pekerjaan->jabatan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $pekerjaan->jenis_kontrak ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pekerjaan->tgl_awal ? \Carbon\Carbon::parse($pekerjaan->tgl_awal)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $pekerjaan->tgl_akhir ? \Carbon\Carbon::parse($pekerjaan->tgl_akhir)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $pekerjaan->statusBadge['class'] }}">
                                    {{ $pekerjaan->statusBadge['text'] }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <!-- Detail Button -->
                                    <button wire:click="showDetail({{ $pekerjaan->id }})"
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
                                    @can('karyawan_pekerjaan.edit')
                                    <button wire:click="edit({{ $pekerjaan->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-50 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    @endcan
                                    @can('karyawan_pekerjaan.delete')
                                    <button wire:click="confirmDelete({{ $pekerjaan->id }})"
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
                                    <p class="text-lg font-medium">No pekerjaan found</p>
                                    <p class="text-sm">Get started by creating a new pekerjaan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-3 px-4 text-xs">
                {{ $pekerjaans->links() }}
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
                                    {{ $isEdit ? 'Edit Pekerjaan' : 'Tambah Pekerjaan' }}
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
                                <!-- Nama Pekerjaan -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nama Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model.live="nama_instansi" type="text"
                                        placeholder="e.g. PT. ABC, Universitas XYZ, etc"
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    @error('nama_instansi')
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
                                    <!-- Bidang Industri -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Bidang Industri <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="bidang_industri" type="text" placeholder="e.g. Pendidikan, Migas, etc"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('bidang_industri')
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

                                    <!-- Departemen -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Departemen <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="departemen" type="text" placeholder="e.g.IT, Finance, etc"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('departemen')
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

                                    <!-- Jabatan -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Jabatan <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="jabatan" type="text" placeholder="e.g. Manager, Staff, etc"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('jabatan')
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

                                    <!-- Jenis Kontrak -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kontrak <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="jenis_kontrak"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Jenis Kontrak
                                                </option>
                                                <option value="kontrak">Kontrak</option>
                                                <option value="tetap">Tetap</option>
                                                <option value="magang">Magang</option>
                                                <option value="freelance">Freelance</option>
                                                <option value="konsultan">Konsultan</option>
                                                <option value="paruh_waktu">Paruh Waktu</option>
                                                <option value="harian">Harian</option>
                                                <option value="borongan">Borongan</option>
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('jenis_kontrak')
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

                                    <!-- Status Kerja -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Kerja <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="status_kerja"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Status Kerja
                                                </option>
                                                <option value="aktif">Aktif</option>
                                                <option value="selesai">Selesai</option>
                                                <option value="resign">Resign</option>
                                                <option value="phk">PHK</option>
                                                <option value="mutasi">Mutasi</option>
                                                <option value="pensiun">Pensiun</option>
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('status_kerja')
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

                                    <!-- Tanggal Awal -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Tanggal Awal <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="tgl_awal" type="date"
                                            placeholder="Enter Tanggal Efektif"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tgl_awal')
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

                                    @if ($status_kerja === 'selesai' || $status_kerja === 'resign' || $status_kerja === 'phk' || $status_kerja === 'mutasi' || $status_kerja === 'pensiun')
                                    <!-- Tanggal Akhir -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Tanggal Akhir <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="tgl_akhir" type="date"
                                            placeholder="Enter Tanggal Efektif"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tgl_akhir')
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
                                    @endif

                                    <!-- Kota -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Kota Bekerja <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="lokasi_pekerjaan" type="text"
                                            placeholder="e.g. Jakarta, Bandung, etc"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('lokasi_pekerjaan')
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

                                    <!-- Mata Uang -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Mata Uang Gaji
                                        </label>
                                        <input wire:model.live="mata_uang" type="text"
                                            placeholder="e.g. IDR, USD, EUR"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('mata_uang')
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

                                    <!-- Gaji Pertama -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Gaji Pertama
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                            <input wire:model="gaji_awal" type="number" placeholder="0"
                                                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        </div>
                                        @error('gaji_awal')
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

                                    <!-- Gaji Terakhir -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Gaji Terakhir
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                            <input wire:model="gaji_akhir" type="number" placeholder="0"
                                                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        </div>
                                        @error('gaji_akhir')
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

                                <!-- Peran/Tanggung Jawab -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Peran/Tanggung Jawab <span class="text-red-500">*</span></label>
                                    <textarea wire:model.live="peran" rows="3" placeholder="Peran & Tanggung Jawab..."
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                                    @error('peran')
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

                                @if ($status_kerja === 'resign' || $status_kerja === 'selesai' || $status_kerja === 'mutasi')
                                <!-- Alasan -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Berhenti</label>
                                    <textarea wire:model.live="alasan_berhenti" rows="3" placeholder="Alasan Berhenti/Resign..."
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                                    @error('alasan_berhenti')
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
                                @endif
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
                                        {{ $isEdit ? 'Update Pekerjaan' : 'Simpan Pekerjaan' }}
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
                                    d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                            </svg>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Riwayat Pekerjaan</h2>
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
                    @if (!empty($selectedPekerjaan))
                        <!-- Job Header -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $selectedPekerjaan->jabatan ?? 'Tidak Diketahui' }}
                                        </h3>
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                            @if($selectedPekerjaan->status_kerja === 'active') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                            @elseif($selectedPekerjaan->status_kerja === 'resigned') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                            @elseif($selectedPekerjaan->status_kerja === 'terminated') bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400
                                            @elseif($selectedPekerjaan->status_kerja === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                            {{ ucfirst($selectedPekerjaan->status_kerja ?? 'N/A') }}
                                        </span>
                                    </div>
                                    <p class="text-emerald-600 dark:text-emerald-400 font-medium text-lg mb-1">
                                        {{ $selectedPekerjaan->nama_instansi ?? 'Tidak Diketahui' }}
                                    </p>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                        @if($selectedPekerjaan->departemen)
                                            <p>Departemen: {{ $selectedPekerjaan->departemen }}</p>
                                        @endif
                                        @if($selectedPekerjaan->lokasi_pekerjaan)
                                            <p>📍 {{ $selectedPekerjaan->lokasi_pekerjaan }}</p>
                                        @endif
                                        @if($selectedPekerjaan->bidang_industri)
                                            <p>Industri: {{ ucfirst($selectedPekerjaan->bidang_industri) }}</p>
                                        @endif
                                        @if($selectedPekerjaan->jenis_kontrak)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                {{ ucfirst($selectedPekerjaan->jenis_kontrak) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($selectedPekerjaan->tgl_awal || $selectedPekerjaan->tgl_akhir)
                                    <div class="flex-shrink-0 ml-4 text-right">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            @if($selectedPekerjaan->tgl_awal)
                                                <div>Mulai: {{ \Carbon\Carbon::parse($selectedPekerjaan->tgl_awal)->format('M Y') }}</div>
                                            @endif
                                            @if($selectedPekerjaan->tgl_akhir)
                                                <div>Selesai: {{ \Carbon\Carbon::parse($selectedPekerjaan->tgl_akhir)->format('M Y') }}</div>
                                            @else
                                                <div class="text-green-600 dark:text-green-400 font-medium">Sekarang</div>
                                            @endif
                                            @if($selectedPekerjaan->tgl_awal && $selectedPekerjaan->tgl_akhir)
                                                @php
                                                    $start = \Carbon\Carbon::parse($selectedPekerjaan->tgl_awal);
                                                    $end = \Carbon\Carbon::parse($selectedPekerjaan->tgl_akhir);
                                                    $duration = $start->diffInMonths($end);
                                                    $years = floor($duration / 12);
                                                    $months = $duration % 12;
                                                @endphp
                                                <div class="text-xs text-gray-500 mt-1">
                                                    ({{ $years > 0 ? $years . ' thn ' : '' }}{{ $months }} bln)
                                                </div>
                                            @elseif($selectedPekerjaan->tgl_awal && !$selectedPekerjaan->tgl_akhir)
                                                @php
                                                    $start = \Carbon\Carbon::parse($selectedPekerjaan->tgl_awal);
                                                    $duration = $start->diffInMonths(now());
                                                    $years = floor($duration / 12);
                                                    $months = $duration % 12;
                                                @endphp
                                                <div class="text-xs text-gray-500 mt-1">
                                                    ({{ $years > 0 ? $years . ' thn ' : '' }}{{ $months }} bln)
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Job Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Company Details -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Detail Perusahaan</h4>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Instansi</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedPekerjaan->nama_instansi ?? 'N/A' }}</dd>
                                    </div>
                                    @if($selectedPekerjaan->departemen)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Departemen</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedPekerjaan->departemen }}</dd>
                                        </div>
                                    @endif
                                    @if($selectedPekerjaan->bidang_industri)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bidang Industri</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($selectedPekerjaan->bidang_industri) }}</dd>
                                        </div>
                                    @endif
                                    @if($selectedPekerjaan->lokasi_pekerjaan)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lokasi</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $selectedPekerjaan->lokasi_pekerjaan }}
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Position Details -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Detail Posisi</h4>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedPekerjaan->jabatan ?? 'N/A' }}</dd>
                                    </div>
                                    @if($selectedPekerjaan->peran)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Peran</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($selectedPekerjaan->peran) }}</dd>
                                        </div>
                                    @endif
                                    @if($selectedPekerjaan->jenis_kontrak)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kontrak</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($selectedPekerjaan->jenis_kontrak) }}</dd>
                                        </div>
                                    @endif
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                        <dd class="mt-1">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($selectedPekerjaan->status_kerja === 'active') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                                @elseif($selectedPekerjaan->status_kerja === 'resigned') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                                @elseif($selectedPekerjaan->status_kerja === 'terminated') bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400
                                                @elseif($selectedPekerjaan->status_kerja === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                {{ ucfirst($selectedPekerjaan->status_kerja ?? 'N/A') }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Period -->
                        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <h4 class="font-semibold text-gray-900 dark:text-white">Periode Kerja</h4>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $selectedPekerjaan->tgl_awal ? \Carbon\Carbon::parse($selectedPekerjaan->tgl_awal)->format('d F Y') : 'N/A' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $selectedPekerjaan->tgl_akhir ? \Carbon\Carbon::parse($selectedPekerjaan->tgl_akhir)->format('d F Y') : 'Masih Berlangsung' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Durasi</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            @if($selectedPekerjaan->tgl_awal)
                                                @php
                                                    $start = \Carbon\Carbon::parse($selectedPekerjaan->tgl_awal);
                                                    $end = $selectedPekerjaan->tgl_akhir ? \Carbon\Carbon::parse($selectedPekerjaan->tgl_akhir) : now();
                                                    $duration = $start->diffInMonths($end);
                                                    $years = floor($duration / 12);
                                                    $months = $duration % 12;
                                                @endphp
                                                {{ $years > 0 ? $years . ' tahun ' : '' }}{{ $months }} bulan
                                            @else
                                                N/A
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Salary Information -->
                        @if($selectedPekerjaan->gaji_awal || $selectedPekerjaan->gaji_akhir)
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Gaji</h4>
                                </div>
                                <div class="p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        @if($selectedPekerjaan->gaji_awal)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gaji Awal</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">
                                                    {{ $selectedPekerjaan->mata_uang ?? 'IDR' }} {{ number_format($selectedPekerjaan->gaji_awal, 0, ',', '.') }}
                                                </dd>
                                            </div>
                                        @endif
                                        @if($selectedPekerjaan->gaji_akhir)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gaji Akhir</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">
                                                    {{ $selectedPekerjaan->mata_uang ?? 'IDR' }} {{ number_format($selectedPekerjaan->gaji_akhir, 0, ',', '.') }}
                                                </dd>
                                            </div>
                                        @endif
                                        @if($selectedPekerjaan->gaji_awal && $selectedPekerjaan->gaji_akhir && $selectedPekerjaan->gaji_akhir > $selectedPekerjaan->gaji_awal)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kenaikan</dt>
                                                <dd class="mt-1 text-sm">
                                                    @php
                                                        $increase = (($selectedPekerjaan->gaji_akhir - $selectedPekerjaan->gaji_awal) / $selectedPekerjaan->gaji_awal) * 100;
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                        </svg>
                                                        +{{ number_format($increase, 1) }}%
                                                    </span>
                                                </dd>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Reason for Leaving -->
                        @if($selectedPekerjaan->alasan_berhenti)
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Alasan Berhenti</h4>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {{ $selectedPekerjaan->alasan_berhenti }}
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
                                            {{ $selectedPekerjaan->created_at ? \Carbon\Carbon::parse($selectedPekerjaan->created_at)->format('d F Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    @if($selectedPekerjaan->created_by)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedPekerjaan->creator?->name ?? $selectedPekerjaan->created_by }}
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui</dt>
                                        <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                            {{ $selectedPekerjaan->updated_at ? \Carbon\Carbon::parse($selectedPekerjaan->updated_at)->format('d F Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    @if($selectedPekerjaan->updated_by)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui Oleh</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedPekerjaan->updater?->name ?? $selectedPekerjaan->updated_by }}
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
