<div>

    <div class="flex flex-column sm:flex-row flex-wrap sm:space-y-0 items-center justify-between">
        <div>
            <flux:heading size="lg">Bahasa</flux:heading>
            <flux:text>This Page Show List of Bahasa</flux:text>
        </div>

        <div>
            @can('karyawan_bahasa.create')
                <button wire:click="create"
                    class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-1 rounded-lg flex items-center justify-center transition duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    <span>Create</span>
                </button>
            @else
                <div class="text-sm text-gray-500 italic">No permission to create Bahasa</div>
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
                        placeholder="Search Bahasa...">
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
                            wire:click="sortBy('nama_bahasa')">
                            <div class="flex items-center gap-2">
                                <span>Bahasa</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'nama_bahasa')
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
                            wire:click="sortBy('hubungan')">
                            <div class="flex items-center gap-2">
                                <span>Test</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'hubungan')
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
                            Skor</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            level</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            sertifikasi</th>
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
                    @forelse($bahasas as $bahasa)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $bahasas->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4 w-72">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 capitalize">
                                        {{ $bahasa->nama_bahasa }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                @if ($bahasa->jenis_test !== 'Lainnya')
                                    {{ $bahasa->jenis_test ?? 'N/A' }}
                                @else
                                    {{ $bahasa->jenistest_lain ?? 'N/A' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $bahasa->skor_numerik ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $bahasa->level_bahasa ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="space-y-2">
                                    <div>
                                        {{ $bahasa->tgl_sertifikasi ? \Carbon\Carbon::parse($bahasa->tgl_sertifikasi)->format('d M Y') : '-' }}
                                    </div>
                                    @if($bahasa->tgl_expired_sertifikasi)
                                        @php
                                            $expiredDate = \Carbon\Carbon::parse($bahasa->tgl_expired_sertifikasi);
                                            $now = now();
                                            $isExpired = $expiredDate->isPast();
                                            $daysUntilExpiry = $now->diffInDays($expiredDate, false);
                                        @endphp
                                        @if($isExpired)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                                Kadaluarsa
                                            </span>
                                        @elseif($daysUntilExpiry <= 30)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $daysUntilExpiry }} hari lagi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                s/d {{ $expiredDate->format('d M Y') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Selamanya
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $bahasa->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $bahasa->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <!-- Detail Button -->
                                    <button wire:click="showDetail({{ $bahasa->id }})"
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
                                    @can('karyawan_bahasa.edit')
                                    <button wire:click="edit({{ $bahasa->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-50 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    @endcan
                                    @can('karyawan_bahasa.delete')
                                    <button wire:click="confirmDelete({{ $bahasa->id }})"
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
                                    <p class="text-lg font-medium">No bahasa found</p>
                                    <p class="text-sm">Get started by creating a new bahasa.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-3 px-4 text-xs">
                {{ $bahasas->links() }}
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
                                    {{ $isEdit ? 'Edit Bahasa' : 'Tambah Bahasa' }}
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Bahasa -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Bahasa <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="nama_bahasa"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Bahasa
                                                </option>
                                                @foreach(\App\Models\Employee\KaryawanBahasa::BAHASA_UMUM as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('nama_bahasa')
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

                                    <!-- Level Mahir -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Level Mahir <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="level_bahasa"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Level
                                                </option>
                                                @foreach(\App\Models\Employee\KaryawanBahasa::LEVEL_BAHASA as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('level_bahasa')
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

                                    <!-- Jenis Test -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Test <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="jenis_test"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Jenis Test
                                                </option>
                                                @foreach(\App\Models\Employee\KaryawanBahasa::JENIS_TEST as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('jenis_test')
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

                                    @if($jenis_test === 'Lainnya')
                                    <!-- Jenis Test Lain -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Jenis Test Lain <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="jenistest_lain" type="text"
                                            placeholder="e.g. TestDaF, DELF, etc."
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('jenistest_lain')
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

                                    <!-- Lembaga Sertifikasi -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Lembaga Sertifikasi <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="lembaga_sertifikasi" type="text"
                                            placeholder="e.g. British Council, etc"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('lembaga_sertifikasi')
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

                                    <!-- Skor Test -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Skor Test <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="skor_numerik" type="number"
                                            placeholder="e.g. 750, 85, etc"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('skor_numerik')
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

                                    <!-- Is Active -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Is Active? <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center cursor-pointer">
                                                <input wire:model.live="is_active" type="radio" value="1"
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
                                                <input wire:model.live="is_active" type="radio" value="0"
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
                                        @error('is_active')
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

                                    <!-- Tanggal Sertifikasi -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Tanggal Sertifikasi <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model.live="tgl_sertifikasi" type="date"
                                            placeholder="Enter Tanggal Lahir"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tgl_sertifikasi')
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

                                    @if ($is_active === true)
                                        <!-- Tanggal Expired -->
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-gray-700">
                                                Tanggal Expired <span class="text-red-500">*</span>
                                            </label>
                                            <input wire:model.live="tgl_expired_sertifikasi" type="date"
                                                placeholder="Enter Tanggal Lahir"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                            @error('tgl_expired_sertifikasi')
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
                                </div>

                                <!-- Keterangan -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan </label>
                                    <textarea wire:model.live="keterangan" rows="3" placeholder="Masukkan Keterangan..."
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
                                        {{ $isEdit ? 'Update Bahasa' : 'Simpan Bahasa' }}
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
                                    d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3s-4.5 4.03-4.5 9 2.015 9 4.5 9Z m-8.716-6.747C3.055 14.253 3 13.647 3 13c0-5.591 4.409-10 9.85-10.194C17.289 2.648 21 6.238 21 10.5c0 .647-.055 1.253-.284 1.753M3.284 14.253l-.568-.447a12.03 12.03 0 0 1 0-7.612l.568-.447" />
                            </svg>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Kemampuan Bahasa</h2>
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
                    @if (!empty($selectedBahasa))
                        <!-- Language Header -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white capitalize">
                                            {{ $selectedBahasa->nama_bahasa ?? 'Tidak Diketahui' }}
                                        </h3>
                                        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full
                                            @if($selectedBahasa->level_bahasa === 'native') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400
                                            @elseif($selectedBahasa->level_bahasa === 'fasih') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                            @elseif($selectedBahasa->level_bahasa === 'mahir') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                            @elseif($selectedBahasa->level_bahasa === 'menengah') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                                            @elseif($selectedBahasa->level_bahasa === 'dasar') bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400
                                            @elseif($selectedBahasa->level_bahasa === 'pemula') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                            @php
                                                $stars = '';
                                                switch($selectedBahasa->level_bahasa) {
                                                    case 'native': $stars = '★★★★★★'; break;
                                                    case 'fasih': $stars = '★★★★★'; break;
                                                    case 'mahir': $stars = '★★★★'; break;
                                                    case 'menengah': $stars = '★★★'; break;
                                                    case 'dasar': $stars = '★★'; break;
                                                    case 'pemula': $stars = '★'; break;
                                                    default: $stars = '';
                                                }
                                            @endphp
                                            <span class="mr-1">{{ $stars }}</span>
                                            {{ ucfirst($selectedBahasa->level_bahasa ?? 'N/A') }}
                                        </span>
                                        @if($selectedBahasa->is_active)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Aktif
                                            </span>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-indigo-600 dark:text-indigo-400 font-medium">
                                            {{ $selectedBahasa->jenis_test ?? 'Tidak Ada Test' }}
                                            @if($selectedBahasa->jenis_test === 'Lainnya' && $selectedBahasa->jenistest_lain)
                                                <span class="text-gray-500 dark:text-gray-400">- {{ $selectedBahasa->jenistest_lain }}</span>
                                            @endif
                                        </p>
                                        @if($selectedBahasa->lembaga_sertifikasi)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                🏛️ {{ $selectedBahasa->lembaga_sertifikasi }}
                                            </p>
                                        @endif
                                        @if($selectedBahasa->skor_numerik)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                📊 Skor: {{ $selectedBahasa->skor_numerik }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if($selectedBahasa->tgl_expired_sertifikasi)
                                    <div class="flex-shrink-0 ml-4 text-right">
                                        <div class="text-sm">
                                            @php
                                                $expiredDate = \Carbon\Carbon::parse($selectedBahasa->tgl_expired_sertifikasi);
                                                $now = now();
                                                $isExpired = $expiredDate->isPast();
                                                $daysUntilExpiry = $now->diffInDays($expiredDate, false);
                                            @endphp
                                            @if($isExpired)
                                                <div class="text-red-600 dark:text-red-400 font-semibold">Kadaluarsa</div>
                                                <div class="text-xs text-red-500">{{ $expiredDate->format('d M Y') }}</div>
                                            @elseif($daysUntilExpiry <= 30)
                                                <div class="text-orange-600 dark:text-orange-400 font-semibold">Segera Kadaluarsa</div>
                                                <div class="text-xs text-orange-500">{{ $expiredDate->format('d M Y') }}</div>
                                            @else
                                                <div class="text-green-600 dark:text-green-400 font-semibold">Berlaku</div>
                                                <div class="text-xs text-green-500">s/d {{ $expiredDate->format('d M Y') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Language Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Language Details -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Detail Bahasa</h4>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Bahasa</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 capitalize">{{ $selectedBahasa->nama_bahasa ?? 'N/A' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Level Kemampuan</dt>
                                        <dd class="mt-1">
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full
                                                    @if($selectedBahasa->level_bahasa === 'native') bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400
                                                    @elseif($selectedBahasa->level_bahasa === 'fasih') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                                    @elseif($selectedBahasa->level_bahasa === 'mahir') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                                    @elseif($selectedBahasa->level_bahasa === 'menengah') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                                                    @elseif($selectedBahasa->level_bahasa === 'dasar') bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400
                                                    @elseif($selectedBahasa->level_bahasa === 'pemula') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400 @endif">
                                                    @php
                                                        $stars = '';
                                                        switch($selectedBahasa->level_bahasa) {
                                                            case 'native': $stars = '★★★★★★'; break;
                                                            case 'fasih': $stars = '★★★★★'; break;
                                                            case 'mahir': $stars = '★★★★'; break;
                                                            case 'menengah': $stars = '★★★'; break;
                                                            case 'dasar': $stars = '★★'; break;
                                                            case 'pemula': $stars = '★'; break;
                                                            default: $stars = '';
                                                        }
                                                    @endphp
                                                    <span class="mr-2">{{ $stars }}</span>
                                                    {{ ucfirst($selectedBahasa->level_bahasa ?? 'N/A') }}
                                                </span>
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                @switch($selectedBahasa->level_bahasa)
                                                    @case('native')
                                                        Bahasa ibu/asli - kemampuan setara penutur asli
                                                        @break
                                                    @case('fasih')
                                                        Sangat lancar - dapat berkomunikasi dengan mudah dalam situasi apapun
                                                        @break
                                                    @case('mahir')
                                                        Lancar - dapat berkomunikasi dengan baik dalam situasi formal dan informal
                                                        @break
                                                    @case('menengah')
                                                        Cukup baik - dapat berkomunikasi dalam situasi sehari-hari
                                                        @break
                                                    @case('dasar')
                                                        Pemahaman dasar - dapat berkomunikasi sederhana
                                                        @break
                                                    @case('pemula')
                                                        Baru belajar - pemahaman terbatas
                                                        @break
                                                @endswitch
                                            </div>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                        <dd class="mt-1">
                                            @if($selectedBahasa->is_active)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Certification Details -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Sertifikasi</h4>
                                </div>
                                <div class="p-4 space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Test</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $selectedBahasa->jenis_test ?? 'N/A' }}
                                            @if($selectedBahasa->jenis_test === 'Lainnya' && $selectedBahasa->jenistest_lain)
                                                <span class="text-gray-500">- {{ $selectedBahasa->jenistest_lain }}</span>
                                            @endif
                                        </dd>
                                    </div>
                                    @if($selectedBahasa->lembaga_sertifikasi)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Lembaga Sertifikasi</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedBahasa->lembaga_sertifikasi }}</dd>
                                        </div>
                                    @endif
                                    @if($selectedBahasa->skor_numerik)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Skor</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">
                                                {{ $selectedBahasa->skor_numerik }}
                                                @if($selectedBahasa->jenis_test === 'TOEFL')
                                                    @if($selectedBahasa->skor_numerik >= 600)
                                                        <span class="text-green-600 text-xs">(Excellent)</span>
                                                    @elseif($selectedBahasa->skor_numerik >= 550)
                                                        <span class="text-blue-600 text-xs">(Good)</span>
                                                    @elseif($selectedBahasa->skor_numerik >= 500)
                                                        <span class="text-yellow-600 text-xs">(Fair)</span>
                                                    @else
                                                        <span class="text-red-600 text-xs">(Limited)</span>
                                                    @endif
                                                @elseif($selectedBahasa->jenis_test === 'IELTS')
                                                    @if($selectedBahasa->skor_numerik >= 8.0)
                                                        <span class="text-green-600 text-xs">(Very Good)</span>
                                                    @elseif($selectedBahasa->skor_numerik >= 7.0)
                                                        <span class="text-blue-600 text-xs">(Good)</span>
                                                    @elseif($selectedBahasa->skor_numerik >= 6.0)
                                                        <span class="text-yellow-600 text-xs">(Competent)</span>
                                                    @else
                                                        <span class="text-red-600 text-xs">(Limited)</span>
                                                    @endif
                                                @endif
                                            </dd>
                                        </div>
                                    @endif
                                    @if($selectedBahasa->tgl_sertifikasi)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Sertifikasi</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($selectedBahasa->tgl_sertifikasi)->format('d F Y') }}
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Validity Period -->
                        @if($selectedBahasa->tgl_sertifikasi || $selectedBahasa->tgl_expired_sertifikasi)
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Masa Berlaku Sertifikasi</h4>
                                </div>
                                <div class="p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        @if($selectedBahasa->tgl_sertifikasi)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Terbit</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($selectedBahasa->tgl_sertifikasi)->format('d F Y') }}
                                                </dd>
                                            </div>
                                        @endif
                                        @if($selectedBahasa->tgl_expired_sertifikasi)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Kadaluarsa</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($selectedBahasa->tgl_expired_sertifikasi)->format('d F Y') }}
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Berlaku</dt>
                                                <dd class="mt-1">
                                                    @php
                                                        $expiredDate = \Carbon\Carbon::parse($selectedBahasa->tgl_expired_sertifikasi);
                                                        $now = now();
                                                        $isExpired = $expiredDate->isPast();
                                                        $daysUntilExpiry = $now->diffInDays($expiredDate, false);
                                                    @endphp
                                                    @if($isExpired)
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                            </svg>
                                                            Kadaluarsa
                                                        </span>
                                                    @elseif($daysUntilExpiry <= 30)
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Segera Kadaluarsa
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Berlaku
                                                        </span>
                                                    @endif
                                                </dd>
                                            </div>
                                        @else
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Berlaku</dt>
                                                <dd class="mt-1">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Selamanya
                                                    </span>
                                                </dd>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Notes -->
                        @if($selectedBahasa->keterangan)
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Keterangan</h4>
                                </div>
                                <div class="p-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {{ $selectedBahasa->keterangan }}
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
                                            {{ $selectedBahasa->created_at ? \Carbon\Carbon::parse($selectedBahasa->created_at)->format('d F Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    @if($selectedBahasa->created_by)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedBahasa->creator?->name ?? $selectedBahasa->created_by }}
                                            </dd>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui</dt>
                                        <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                            {{ $selectedBahasa->updated_at ? \Carbon\Carbon::parse($selectedBahasa->updated_at)->format('d F Y, H:i') : 'N/A' }}
                                        </dd>
                                    </div>
                                    @if($selectedBahasa->updated_by)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui Oleh</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedBahasa->updater?->name ?? $selectedBahasa->updated_by }}
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
