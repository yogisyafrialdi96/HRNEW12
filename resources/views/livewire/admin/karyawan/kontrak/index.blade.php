<div>

    <flux:heading size="xl">Kontrak Kerja</flux:heading>
    <flux:text class="mt-2 mb-6">This Page Show List of Kontrak Karyawan</flux:text>

    <!-- Stats Widget Section -->
    @php
        $stats = $this->getStats();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 mb-2">
        <!-- Total Kontrak -->
        <div class="bg-blue-50 rounded-lg p-3 border border-blue-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">Total Kontrak</p>
                    <p class="text-lg font-bold text-blue-600">{{ $stats['total_kontrak'] }}</p>
                </div>
            </div>
        </div>

        <!-- Kontrak Aktif -->
        <div class="bg-emerald-50 rounded-lg p-3 border border-emerald-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-emerald-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">Aktif</p>
                    <p class="text-lg font-bold text-emerald-600">{{ $stats['total_aktif'] }}</p>
                </div>
            </div>
        </div>

        <!-- Kontrak Non-Aktif -->
        <div class="bg-orange-50 rounded-lg p-3 border border-orange-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-orange-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                    </svg>

                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">N.Aktif</p>
                    <p class="text-lg font-bold text-orange-600">{{ $stats['total_non_aktif'] }}</p>
                </div>
            </div>
        </div>

        <!-- Jenis Kontrak 1 (first type if exists) -->
        @if($stats['by_jenis']->count() > 0)
            @php $firstJenis = $stats['by_jenis']->first(); $firstKey = $stats['by_jenis']->keys()->first(); @endphp
            <div class="bg-purple-50 rounded-lg p-3 border border-purple-200 hover:shadow-md transition">
                <div class="flex items-center gap-2">
                    <div class="bg-purple-600 rounded p-1.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.972 1.972 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-gray-500 text-xs truncate">{{ Str::limit($firstKey, 10) }}</p>
                        <p class="text-lg font-bold text-purple-600">{{ $firstJenis }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Jenis Kontrak 2 (second type if exists) -->
        @if($stats['by_jenis']->count() > 1)
            @php $secondJenis = $stats['by_jenis']->slice(1, 1)->first(); $secondKey = $stats['by_jenis']->keys()->slice(1, 1)->first(); @endphp
            <div class="bg-cyan-50 rounded-lg p-3 border border-cyan-200 hover:shadow-md transition">
                <div class="flex items-center gap-2">
                    <div class="bg-cyan-600 rounded p-1.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.972 1.972 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-gray-500 text-xs truncate">{{ Str::limit($secondKey, 10) }}</p>
                        <p class="text-lg font-bold text-cyan-600">{{ $secondJenis }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Kontrak PHL -->
        <div class="bg-pink-50 rounded-lg p-3 border border-pink-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-pink-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.972 1.972 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">PHL</p>
                    <p class="text-lg font-bold text-pink-600">{{ $stats['total_phl'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
        <div class="relative overflow-x-auto mt-4 shadow-md sm:rounded-lg">
            <!-- Filters and Actions Row -->
            <div class="bg-white rounded-t-lg p-4 border-b border-gray-200">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        <!-- Filters Grid (3 columns) -->
                        <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <!-- Filter Jenis Kontrak -->
                            <select wire:model.live="jenis_kontrak_filter"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">Semua Jenis Kontrak</option>
                                @foreach($masterKontrak as $kontrak)
                                    <option value="{{ $kontrak->id }}">{{ $kontrak->nama_kontrak }}</option>
                                @endforeach
                            </select>

                            <!-- Filter Status Kontrak -->
                            <select wire:model.live="status_kontrak_filter"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="selesai">Selesai</option>
                                <option value="perpanjangan">Perpanjangan</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>

                            <!-- Filter Sisa Kontrak -->
                            <select wire:model.live="sisa_kontrak_filter"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">Semua Sisa Kontrak</option>
                                <option value="expired">Sudah Berakhir</option>
                                <option value="expiring_soon">Akan Berakhir (≤30 hari)</option>
                                <option value="valid">Masih Berlaku (>30 hari)</option>
                                <option value="unlimited">Tidak Terbatas (TETAP)</option>
                            </select>
                        </div>

                        <!-- Action Buttons (2 columns) -->
                        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <button wire:click="$toggle('showDeleted')"
                                class="bg-zinc-400 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition duration-200 whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 flex-shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span class="hidden sm:inline">{{ $showDeleted ? 'Show Exist' : 'Show Deleted' }}</span>
                                <span class="sm:hidden">{{ $showDeleted ? 'Exist' : 'Deleted' }}</span>
                            </button>
                            <button wire:click="create"
                class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-1 rounded-lg flex items-center justify-center transition duration-200 whitespace-nowrap">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                <span>Buat Kontrak</span>
            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between p-3">
                <div>
                    <select wire:model.live="perPage"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="10">10/page</option>
                        <option value="25">25/page</option>
                        <option value="50">50/page</option>
                    </select>
                </div>
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
                    <input type="text" wire:model.live="search"
                        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search Kontrak...">
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 w-16">
                            <div class="flex items-center gap-2">
                                <span>No</span>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('nomor_kontrak')">
                            <div class="flex items-center gap-2">
                                <span>No. Kontrak</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'nomor_kontrak')
                                        @if ($sortDirection === 'asc')
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Karyawan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Kontrak
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jabatan
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode & Sisa Kontrak
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('status')">
                            <div class="flex items-center gap-2">
                                <span>Status</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'status')
                                        @if ($sortDirection === 'asc')
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span>Dokumen</span>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kontraks as $kontrak)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $kontraks->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $kontrak->nomor_kontrak }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $kontrak->unit->unit ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $kontrak->karyawan->full_name ?? 'N/A' }}</div>
                                @if($kontrak->karyawan->activeJabatan)
                                    <div class="text-xs text-gray-500">
                                        <span class="font-medium">{{ $kontrak->karyawan->activeJabatan->jabatan->nama_jabatan }}</span>
                                        - {{ $kontrak->karyawan->activeJabatan->unit->unit }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $kontrak->kontrak->nama_kontrak ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div>{{ $kontrak->jabatan->nama_jabatan ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $kontrak->golongan->nama_golongan ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="text-gray-900">{{ $kontrak->tglmulai_kontrak ? \Carbon\Carbon::parse($kontrak->tglmulai_kontrak)->format('d M Y') : '-' }}</div>
                                @if($kontrak->tglselesai_kontrak)
                                    <div class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($kontrak->tglselesai_kontrak)->format('d M Y') }}</div>
                                    @php
                                        $contractStatus = $this->getContractStatus($kontrak->tglselesai_kontrak);
                                    @endphp
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs rounded-full
                                            @if($contractStatus['color'] === 'red') bg-red-100 text-red-800
                                            @elseif($contractStatus['color'] === 'yellow') bg-yellow-100 text-yellow-800
                                            @elseif($contractStatus['color'] === 'blue') bg-blue-100 text-blue-800
                                            @elseif($contractStatus['color'] === 'green') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $contractStatus['text'] }}
                                        </span>
                                    </div>
                                @else
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            Tidak terbatas
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($kontrak->status === 'aktif') bg-green-100 text-green-800
                                    @elseif($kontrak->status === 'selesai') bg-gray-100 text-gray-800
                                    @elseif($kontrak->status === 'perpanjangan') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($kontrak->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($kontrak->document_path)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ asset('storage/' . $kontrak->document_path) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-900 inline-flex items-center gap-1 p-1 rounded hover:bg-blue-50 transition duration-200"
                                            title="Download dokumen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button wire:click="openUploadModal({{ $kontrak->id }})"
                                            class="text-yellow-600 hover:text-yellow-900 inline-flex items-center gap-1 p-1 rounded hover:bg-yellow-50 transition duration-200"
                                            title="Update dokumen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="deleteDocument({{ $kontrak->id }})"
                                            class="text-red-600 hover:text-red-900 inline-flex items-center gap-1 p-1 rounded hover:bg-red-50 transition duration-200"
                                            title="Hapus dokumen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <button wire:click="openUploadModal({{ $kontrak->id }})"
                                        class="text-gray-500 hover:text-gray-700 inline-flex items-center gap-1 p-1 rounded hover:bg-gray-50 transition duration-200"
                                        title="Upload dokumen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4">
                                            </path>
                                        </svg>
                                        <span class="text-xs">Upload</span>
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    @if ($showDeleted)
                                        <button wire:click="confirmRestore({{ $kontrak->id }})"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded transition duration-200"
                                            title="Restore kontrak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmForceDelete({{ $kontrak->id }})"
                                            class="text-red-600 hover:text-red-900 p-1 rounded transition duration-200"
                                            title="Hard Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @else
                                        <button wire:click="showDetail({{ $kontrak->id }})"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50"
                                            title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                        <a href="{{ route('kontrak.cetak', $kontrak->id) }}" target="_blank"
                                            class="text-green-600 hover:text-green-900 p-1 rounded-md hover:bg-green-50 transition duration-200 inline-flex"
                                            title="Cetak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button wire:click="edit({{ $kontrak->id }})"
                                            class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-50 transition duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $kontrak->id }})"
                                            class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">No contracts found</p>
                                    <p class="text-sm">Get started by creating a new contract.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-3 px-4 text-xs">
                {{ $kontraks->links() }}
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        @teleport('body')
            <div x-data="{
                init() {
                    document.body.style.overflow = 'hidden';
                    document.body.classList.add('modal-open');
                },
                destroy() {
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
                    class="relative w-full max-w-4xl mx-auto my-auto">

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <!-- Header -->
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $isEdit ? 'Edit Kontrak' : 'Tambah Kontrak' }}
                                </h3>
                                <button wire:click="closeModal"
                                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form -->
                        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                            <form wire:submit.prevent="save" class="space-y-5">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Karyawan with Search -->
                                    <div class="space-y-2" x-data="{ 
                                        open: false,
                                        karyawanId: {{ $karyawan_id ?? 'null' }}
                                    }" 
                                    @click.away="open = false">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Karyawan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <!-- Input Search or Selected Value -->
                                            <template x-if="karyawanId">
                                                <!-- Display Selected Value -->
                                                <div class="flex items-center gap-2">
                                                    <input 
                                                        type="text" 
                                                        readonly
                                                        :value="$wire.selectedKaryawanName"
                                                        @focus="open = true"
                                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer"
                                                    >
                                                    <!-- Clear Button -->
                                                    <button 
                                                        type="button"
                                                        @click="$wire.clearKaryawan(); karyawanId = null; open = false"
                                                        class="text-gray-400 hover:text-red-500 transition-colors flex-shrink-0">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <template x-if="!karyawanId">
                                                <!-- Search Input -->
                                                <input 
                                                    type="text" 
                                                    placeholder="Ketik nama karyawan..." 
                                                    @focus="open = true"
                                                    @input="$wire.searchKaryawan($event.target.value); open = true"
                                                    wire:model.debounce.300ms="karyawanSearch"
                                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                                                >
                                            </template>
                                            
                                            <!-- Dropdown Results -->
                                            <div x-show="open && !karyawanId" class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 max-h-48 overflow-y-auto">
                                                @if(count($filteredKaryawan ?? []) > 0)
                                                    @foreach($filteredKaryawan as $mk)
                                                        <div 
                                                            @click="$wire.selectKaryawan({{ $mk->id }}, '{{ addslashes($mk->full_name) }}'); karyawanId = {{ $mk->id }}; open = false;"
                                                            class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors">
                                                            <div class="font-medium text-gray-900">{{ $mk->full_name }}</div>
                                                            <div class="text-xs text-gray-500">{{ $mk->user->email ?? 'No email' }}</div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                                                        Tidak ada karyawan yang ditemukan
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @error('karyawan_id')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Nomor Kontrak -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Nomor Kontrak <span class="text-red-500">*</span>
                                        </label>
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <input wire:model.live="nomor_kontrak" type="text" placeholder="e.g. 001/KU-YKPI/X/2025"
                                                    class="flex-1 px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                                <button type="button" wire:click="generateNomorKontrak" title="Generate Nomor"
                                                    class="ml-2 px-3 py-3 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="text-xs text-gray-500">Format: 000/KU-YKPI/X/2025 (Klik tombol generate untuk nomor otomatis)</p>
                                        </div>
                                        @error('nomor_kontrak')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Jenis Kontrak -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kontrak <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="kontrak_id"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="">Pilih Jenis Kontrak</option>
                                                @foreach($masterKontrak as $mk)
                                                    <option value="{{ $mk->id }}">{{ $mk->nama_kontrak }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('kontrak_id')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Golongan -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Golongan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="golongan_id"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="">Pilih Golongan</option>
                                                @foreach($masterGolongan as $mg)
                                                    <option value="{{ $mg->id }}">{{ $mg->nama_golongan }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('golongan_id')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Department -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="department_id"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="">Pilih Department</option>
                                                @foreach($masterDepartment as $md)
                                                    <option value="{{ $md->id }}">{{ $md->department }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('department_id')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Unit -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="unit_id" @if(!$department_id) disabled @endif
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none disabled:bg-gray-100 disabled:cursor-not-allowed">
                                                <option value="">Pilih Unit</option>
                                                @foreach($masterUnit as $mu)
                                                    <option value="{{ $mu->id }}">{{ $mu->unit }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('unit_id')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Jabatan -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="jabatan_id" @if(!$department_id) disabled @endif
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none disabled:bg-gray-100 disabled:cursor-not-allowed">
                                                <option value="">Pilih Jabatan</option>
                                                @foreach($masterJabatan as $mj)
                                                    <option value="{{ $mj->id }}">{{ $mj->nama_jabatan }}</option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('jabatan_id')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Mata Pelajaran (Hanya untuk non-pegawai) -->
                                    @if($jenis_karyawan !== 'Pegawai')
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran</label>
                                            <div class="relative">
                                                <select wire:model.live="mapel_id"
                                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none disabled:bg-gray-100 disabled:cursor-not-allowed">
                                                    <option value="">Pilih Mata Pelajaran</option>
                                                    @foreach($masterMapel as $mapel)
                                                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                                    @endforeach
                                                </select>
                                                <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                            @error('mapel')
                                                <p class="text-xs text-red-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    @endif

                                </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Gaji Paket -->
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Gaji Paket</label>
                                            <input wire:model.live="gaji_paket" type="text" placeholder="e.g. Rp 5.000.000"
                                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                            @error('gaji_paket')
                                                <p class="text-xs text-red-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Gaji Pokok -->
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Gaji Pokok</label>
                                            <input wire:model.live="gaji_pokok" type="text" placeholder="e.g. Rp 4.000.000"
                                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                            @error('gaji_pokok')
                                                <p class="text-xs text-red-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <!-- Transport -->
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Transport</label>
                                            <input wire:model.live="transport" type="text" placeholder="e.g. Rp 500.000"
                                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                            @error('transport')
                                                <p class="text-xs text-red-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Tanggal Mulai Kontrak -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">Tanggal Mulai Kontrak <span class="text-red-500">*</span></label>
                                        <input wire:model.live="tglmulai_kontrak" type="date"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tglmulai_kontrak')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Selesai Kontrak -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Tanggal Selesai Kontrak
                                            @if($this->isKontrakTetap() && !$isEdit)
                                                <span class="text-gray-500 text-xs ml-1">(Tidak terbatas - Kontrak Tetap)</span>
                                            @endif
                                        </label>
                                        <input wire:model.live="tglselesai_kontrak" type="date"
                                            @if($this->isKontrakTetap() && !$isEdit) disabled @endif
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all @if($this->isKontrakTetap() && !$isEdit) disabled:bg-gray-100 disabled:cursor-not-allowed @endif">
                                        @if($this->isKontrakTetap() && !$isEdit)
                                            <p class="text-xs text-blue-600 mt-1">
                                                <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Kontrak tetap tidak memiliki tanggal selesai (baru)
                                            </p>
                                        @elseif($this->isKontrakTetap() && $isEdit)
                                            <p class="text-xs text-orange-600 mt-1">
                                                <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                Kontrak tetap - dapat diedit untuk pensiun/resign
                                            </p>
                                        @endif
                                        @error('tglselesai_kontrak')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                        <div class="flex gap-4 flex-wrap">
                                            <label class="flex items-center cursor-pointer">
                                                <input wire:model.live="status" type="radio" value="aktif" class="sr-only peer">
                                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-50 relative">
                                                    <div class="absolute inset-0.5 bg-green-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input wire:model.live="status" type="radio" value="selesai" class="sr-only peer">
                                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-gray-500 peer-checked:bg-gray-50 relative">
                                                    <div class="absolute inset-0.5 bg-gray-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-700">Selesai</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input wire:model.live="status" type="radio" value="perpanjangan" class="sr-only peer">
                                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-50 relative">
                                                    <div class="absolute inset-0.5 bg-blue-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-700">Perpanjangan</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input wire:model.live="status" type="radio" value="dibatalkan" class="sr-only peer">
                                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-red-500 peer-checked:bg-red-50 relative">
                                                    <div class="absolute inset-0.5 bg-red-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-700">Dibatalkan</span>
                                            </label>
                                        </div>
                                        @error('status')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Approved 1 (from Karyawan - top_managerial) -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Disetujui oleh (Approver 1 - Top Managerial)</label>
                                        <select wire:model.live="approved_1"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                            <option value="">-- Pilih Manajemen Puncak (Opsional) --</option>
                                            @foreach($masterApproved1 as $karyawan)
                                                <option value="{{ $karyawan->id }}">{{ $karyawan->full_name ?? $karyawan->user->name }} @if($karyawan->activeJabatan?->jabatan)({{ $karyawan->activeJabatan->jabatan->nama_jabatan }})@endif</option>
                                            @endforeach
                                        </select>
                                        @error('approved_1')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Approved 2 (from Pengurus) -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Disetujui oleh (Approver 2)</label>
                                        <select wire:model.live="approved_2"
                                            class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                            <option value="">-- Pilih Pengurus (Opsional) --</option>
                                            @foreach($masterPengurus as $pengurus)
                                                <option value="{{ $pengurus->id }}">{{ $pengurus->nama_pengurus }}</option>
                                            @endforeach
                                        </select>
                                        @error('approved_2')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Catatan -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                                    <textarea wire:model.live="catatan" rows="2" placeholder="Masukkan catatan..."
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                                    @error('catatan')
                                        <p class="text-xs text-red-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                                    <textarea wire:model.live="deskripsi" rows="3" placeholder="Masukkan deskripsi kontrak..."
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                                    @error('deskripsi')
                                        <p class="text-xs text-red-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ $isEdit ? 'Update Kontrak' : 'Simpan Kontrak' }}
                                    </span>
                                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <style>
            .modal-open {
                overflow: hidden !important;
            }

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

    <!-- Detail Modal -->
    @if ($showModalDetail)
        @teleport('body')
            <div x-data="{
                init() {
                    document.body.style.overflow = 'hidden';
                    document.body.classList.add('modal-open');
                },
                destroy() {
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
                    <div class="bg-white dark:bg-gray-800 flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 rounded-t-lg">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Kontrak Karyawan</h2>
                            </div>
                        </div>
                        <button wire:click="closeModal"
                            class="p-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Content Area -->
                    <div class="flex-1 overflow-y-auto p-4">
                        @if (!empty($selectedKontrak))
                            <!-- Contract Header -->
                            <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                            {{ $selectedKontrak->nomor_kontrak }}
                                        </h3>
                                        <div class="space-y-1">
                                            <p class="text-blue-600 dark:text-blue-400 font-medium">
                                                {{ $selectedKontrak->kontrak->nama_kontrak ?? 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $selectedKontrak->jabatan->nama_jabatan ?? 'N/A' }} - {{ $selectedKontrak->golongan->nama_golongan ?? 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                📍 {{ $selectedKontrak->unit->unit ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full
                                        @if($selectedKontrak->status === 'aktif') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                        @elseif($selectedKontrak->status === 'selesai') bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400
                                        @elseif($selectedKontrak->status === 'perpanjangan') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400
                                        @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                        @endif">
                                        {{ ucfirst($selectedKontrak->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Contract Information -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Kontrak</h4>
                                    </div>
                                    <div class="p-4 space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Kontrak</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $selectedKontrak->nomor_kontrak }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis Kontrak</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedKontrak->kontrak->nama_kontrak ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedKontrak->unit->unit ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                            <dd class="mt-1">
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($selectedKontrak->status === 'aktif') bg-green-100 text-green-800
                                                    @elseif($selectedKontrak->status === 'selesai') bg-gray-100 text-gray-800
                                                    @elseif($selectedKontrak->status === 'perpanjangan') bg-blue-100 text-blue-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($selectedKontrak->status) }}
                                                </span>
                                            </dd>
                                        </div>
                                    </div>
                                </div>

                                <!-- Position Information -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Jabatan</h4>
                                    </div>
                                    <div class="p-4 space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedKontrak->jabatan->nama_jabatan ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Golongan</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedKontrak->golongan->nama_golongan ?? 'N/A' }}</dd>
                                        </div>
                                        @if($selectedKontrak->mapel)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mata Pelajaran</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedKontrak->mapel->nama_mapel }}</dd>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Compensation Information -->
                            @if($selectedKontrak->gaji_paket || $selectedKontrak->gaji_pokok || $selectedKontrak->transport)
                                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Kompensasi</h4>
                                    </div>
                                    <div class="p-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            @if($selectedKontrak->gaji_paket)
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gaji Paket</dt>
                                                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $selectedKontrak->gaji_paket }}</dd>
                                                </div>
                                            @endif
                                            @if($selectedKontrak->gaji_pokok)
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gaji Pokok</dt>
                                                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $selectedKontrak->gaji_pokok }}</dd>
                                                </div>
                                            @endif
                                            @if($selectedKontrak->transport)
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Transport</dt>
                                                    <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $selectedKontrak->transport }}</dd>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Contract Period -->
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Periode Kontrak</h4>
                                </div>
                                <div class="p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $selectedKontrak->tglmulai_kontrak ? \Carbon\Carbon::parse($selectedKontrak->tglmulai_kontrak)->format('d F Y') : 'N/A' }}
                                            </dd>
                                        </div>
                                        @if($selectedKontrak->tglselesai_kontrak)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($selectedKontrak->tglselesai_kontrak)->format('d F Y') }}
                                                </dd>
                                            </div>
                                        @else
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</dt>
                                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                                        Tidak Terbatas
                                                    </span>
                                                </dd>
                                            </div>
                                        @endif
                                    </div>
                                    @if($selectedKontrak->tglmulai_kontrak && $selectedKontrak->tglselesai_kontrak)
                                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                                <span class="font-medium">Durasi:</span>
                                                {{ \Carbon\Carbon::parse($selectedKontrak->tglmulai_kontrak)->diffInDays(\Carbon\Carbon::parse($selectedKontrak->tglselesai_kontrak)) }} hari
                                                ({{ \Carbon\Carbon::parse($selectedKontrak->tglmulai_kontrak)->diffForHumans(\Carbon\Carbon::parse($selectedKontrak->tglselesai_kontrak), true) }})
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($selectedKontrak->catatan || $selectedKontrak->deskripsi)
                                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Catatan & Deskripsi</h4>
                                    </div>
                                    <div class="p-4 space-y-4">
                                        @if($selectedKontrak->catatan)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Catatan</dt>
                                                <dd class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $selectedKontrak->catatan }}</dd>
                                            </div>
                                        @endif
                                        @if($selectedKontrak->deskripsi)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Deskripsi</dt>
                                                <dd class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $selectedKontrak->deskripsi }}</dd>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Approval Information -->
                            @if($selectedKontrak->approver1 || $selectedKontrak->approver2)
                                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Persetujuan</h4>
                                    </div>
                                    <div class="p-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @if($selectedKontrak->approver1)
                                                <div class="flex items-center space-x-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                                    <div class="flex-shrink-0">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <dt class="text-xs font-medium text-gray-600 dark:text-gray-400">Approved By 1</dt>
                                                        <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedKontrak->approver1->name ?? 'N/A' }}</dd>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($selectedKontrak->approver2)
                                                <div class="flex items-center space-x-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                                    <div class="flex-shrink-0">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1">
                                                        <dt class="text-xs font-medium text-gray-600 dark:text-gray-400">Approved By 2</dt>
                                                        <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $selectedKontrak->approver2->name ?? 'N/A' }}</dd>
                                                    </div>
                                                </div>
                                            @endif
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
                                                {{ $selectedKontrak->created_at ? \Carbon\Carbon::parse($selectedKontrak->created_at)->format('d F Y, H:i') : 'N/A' }}
                                            </dd>
                                        </div>
                                        @if($selectedKontrak->created_by)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                                <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                    {{ $selectedKontrak->creator->name ?? $selectedKontrak->created_by }}
                                                </dd>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedKontrak->updated_at ? \Carbon\Carbon::parse($selectedKontrak->updated_at)->format('d F Y, H:i') : 'N/A' }}
                                            </dd>
                                        </div>
                                        @if($selectedKontrak->updated_by)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui Oleh</dt>
                                                <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                    {{ $selectedKontrak->updater->name ?? $selectedKontrak->updated_by }}
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

    <!-- Modal Konfirmasi Delete -->
    <x-modal-confirmation.modal-confirm-delete wire:model.live="confirmingDelete" onConfirm="delete" />
    <x-modal-confirmation.modal-force-delete />
    <x-modal-confirmation.modal-restore />

    <!-- Upload Document Modal -->
    @if ($showUploadModal)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[99999] flex items-center justify-center p-4 py-8">
            <div class="relative w-full max-w-lg mx-auto">
                <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Upload Dokumen Kontrak</h3>
                            <button wire:click="closeUploadModal()"
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Upload dokumen kontrak yang sudah di scan</p>
                    </div>

                    <!-- Content -->
                    <form wire:submit.prevent="uploadDocument" class="p-6 space-y-4">
                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                File Dokumen
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="file" wire:model="uploadedDocument"
                                    class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100
                                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-0"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            </div>
                            @error('uploadedDocument')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">
                                Format: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)
                            </p>
                        </div>

                        <!-- Progress Indicator -->
                        @if ($uploadedDocument)
                            <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <svg class="w-5 h-5 text-blue-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                <span class="text-sm text-blue-700">File siap untuk diupload</span>
                            </div>
                        @endif

                        <!-- Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button type="button" wire:click="closeUploadModal()"
                                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                                Upload Dokumen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>