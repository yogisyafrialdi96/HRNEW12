<div>

    <!-- Header Section with Title and Add Button -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Karyawan</flux:heading>
            <flux:text class="mt-2">This Page Show List of Karyawan</flux:text>
        </div>
        <div class="justify-end mb-4 flex gap-2">
            <button wire:click="$toggle('showDeleted')"
                class="bg-zinc-400 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition duration-200 whitespace-nowrap text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
                <span class="hidden sm:inline">{{ $showDeleted ? 'Show Exist' : 'Show Deleted' }}</span>
                <span class="sm:hidden">{{ $showDeleted ? 'Exist' : 'Deleted' }}</span>
            </button>
            <button wire:click="openImportModal()"
                class="bg-green-600 hover:bg-green-800 text-white px-3 py-2 rounded-lg flex items-center justify-center gap-2 transition duration-200 whitespace-nowrap text-sm font-medium h-fit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33A3 3 0 0116.5 19.5H6.75z"></path>
                </svg>
                <span>Import Excel</span>
            </button>
            <button wire:click="create"
                class="bg-blue-600 hover:bg-blue-800 text-white px-3 py-2 rounded-lg flex items-center justify-center gap-2 transition duration-200 whitespace-nowrap text-sm font-medium h-fit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Add Karyawan</span>
            </button>
        </div>
    </div>

    <!-- Stats Widget Section -->
    @php
        $stats = $this->getStats();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-2 mb-6">
        <!-- Total Karyawan -->
        <div class="bg-blue-50 rounded-lg p-3 border border-blue-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-blue-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">Total</p>
                    <p class="text-lg font-bold text-blue-600">{{ $stats['total_karyawan'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pegawai -->
        <div class="bg-purple-50 rounded-lg p-3 border border-purple-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-purple-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">Pegawai</p>
                    <p class="text-lg font-bold text-purple-600">{{ $stats['total_pegawai'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Guru -->
        <div class="bg-orange-50 rounded-lg p-3 border border-orange-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-orange-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">Guru</p>
                    <p class="text-lg font-bold text-orange-600">{{ $stats['total_guru'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Aktif -->
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

        <!-- Total Tidak Aktif -->
        <div class="bg-red-50 rounded-lg p-3 border border-red-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-red-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">N.Aktif</p>
                    <p class="text-lg font-bold text-orange-600">{{ $stats['total_tidak_aktif'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Laki-laki -->
        <div class="bg-cyan-50 rounded-lg p-3 border border-cyan-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-cyan-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">Laki-laki</p>
                    <p class="text-lg font-bold text-cyan-600">{{ $stats['total_laki_laki'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Perempuan -->
        <div class="bg-pink-50 rounded-lg p-3 border border-pink-200 hover:shadow-md transition">
            <div class="flex items-center gap-2">
                <div class="bg-pink-600 rounded p-1.5">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-gray-500 text-xs truncate">Perempuan</p>
                    <p class="text-lg font-bold text-pink-600">{{ $stats['total_perempuan'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto bg-white rounded-lg shadow-md p-6 mb-4">
        <div class="space-y-4">

            <!-- Filters and Actions Row -->
            <div class="grid grid-cols-1 lg:grid-cols-7 gap-4">
                <!-- Filters Grid -->
                <div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <!-- Status Filter -->
                    <select wire:model.live="statusFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Status</option>
                        @foreach ($statusKaryawan as $status)
                            <option value="{{ $status->id }}">{{ $status->nama_status }}</option>
                        @endforeach
                    </select>

                    <!-- Unit Filter -->
                    <select wire:model.live="unitFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Unit</option>
                        @foreach ($this->getUnits() as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                        @endforeach
                    </select>

                    <!-- Jabatan Filter -->
                    <select wire:model.live="jabatanFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Jabatan</option>
                        @foreach ($this->getJabatans() as $jabatan)
                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>

                    <!-- Reset Filters Button -->
                    <button wire:click="resetFilters"
                        class="px-4 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 font-medium text-sm transition">
                        Reset Filters
                    </button>
                </div>

                <!-- Date Range and Action Buttons -->
                <div class="lg:col-span-2 flex flex-col sm:flex-row gap-3">
                    <!-- Date Range Filters -->
                    <div class="flex gap-2 flex-1">
                        <input type="date" wire:model.live="tgl_masuk_dari"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="Dari">
                        <input type="date" wire:model.live="tgl_masuk_sampai"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="Sampai">
                    </div>

                    <button wire:click="export"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition duration-200 whitespace-nowrap text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span class="hidden sm:inline">Export Excel</span>
                        <span class="sm:hidden">Export</span>
                    </button>
                </div>


            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
            <div class="relative overflow-x-auto mt-4 shadow-md sm:rounded-lg">
                <div
                    class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between p-3">
                    <div>
                        <select wire:model.live="perPage"
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
                        <input type="text" wire:model.live="search"
                            class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Nama, NIP, Email...">
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
                            <!-- Nama & NIP -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                wire:click="sortBy('full_name')">
                                <div class="flex items-center gap-2">
                                    <span>Nama & NIP</span>
                                    <div class="sort-icon">
                                        @if ($sortField === 'full_name')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-blue-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
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
                            <!-- Jabatan Aktif -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jabatan
                            </th>
                            <!-- Unit Aktif -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Unit
                            </th>
                            <!-- WhatsApp -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                HP
                            </th>
                            <!-- Status Pegawai -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                wire:click="sortBy('tgl_masuk')">
                                <div class="flex items-center gap-2">
                                    <span>Tgl Masuk</span>
                                    <div class="sort-icon">
                                        @if ($sortField === 'tgl_masuk')
                                            @if ($sortDirection === 'asc')
                                                <svg class="w-4 h-4 text-blue-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-blue-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
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

                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($karyawans as $karyawan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                    {{ $karyawans->firstItem() + $loop->index }}.
                                </td>
                                <!-- Nama & NIP -->
                                <td class="px-6 py-4 w-72">
                                    <div class="flex items-center space-x-3">
                                        <img class="w-8 h-8 rounded-full object-cover"
                                            src="{{ $karyawan->foto
                                                ? asset('storage/' . $karyawan->foto)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($karyawan->full_name) }}"
                                            alt="{{ $karyawan->full_name }}">
                                        <div class="text-sm">
                                            <div class="font-semibold text-gray-900">{{ $karyawan->full_name }}</div>
                                            <div class="text-gray-500 text-xs">{{ $karyawan->nip ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Jabatan Aktif -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($karyawan->activeJabatan && $karyawan->activeJabatan->jabatan)
                                        {{ $karyawan->activeJabatan->jabatan->nama_jabatan ?? '-' }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <!-- Unit Aktif -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($karyawan->activeJabatan && $karyawan->activeJabatan->unit)
                                        {{ $karyawan->activeJabatan->unit->unit ?? '-' }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <!-- WhatsApp -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($karyawan->hp === null)
                                        {{ $karyawan->whatsapp ?? '-' }}
                                    @else
                                        {{ $karyawan->hp ?? '-' }}
                                    @endif
                                </td>

                                <!-- Status Pegawai -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($karyawan->statusPegawai)
                                        @php
                                            $badgeConfig = \App\Models\Master\StatusPegawai::getBadgeConfig(
                                                $karyawan->statusPegawai->id,
                                            );
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badgeConfig['class'] }}">
                                            {{ $badgeConfig['label'] }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ \carbon\Carbon::parse($karyawan->tgl_masuk)->format('d M Y') ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if ($showDeleted)
                                        <div class="flex justify-end gap-2">
                                            <button wire:click="confirmRestore({{ $karyawan->id }})"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded transition duration-200"
                                                title="Reset karyawan">

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99">
                                                    </path>
                                                </svg>

                                            </button>
                                            <button wire:click="confirmForceDelete({{ $karyawan->id }})"
                                                class="text-red-600 hover:text-red-900 p-1 rounded transition duration-200"
                                                title="Hard Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex justify-end gap-2">
                                            <!-- Detail Button -->
                                            <button wire:click="showDetail({{ $karyawan->id }})"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50"
                                                title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button wire:navigate
                                                href="{{ route('karyawan.edit', ['karyawan' => $karyawan->id, 'tab' => 'profile']) }}"
                                                class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-50 transition duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button wire:click="confirmDelete({{ $karyawan->id }})"
                                                class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium">No Karyawan found</p>
                                        <p class="text-sm">Get started by creating a new Karyawan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="py-3 px-4 text-xs">
                    {{ $karyawans->links() }}
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        @if ($showModal)
            <div
                class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                        <!-- Header -->
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    {{ $isEdit ? 'Edit Karyawan' : 'Create Karyawan' }}
                                </h3>
                                <button wire:click="closeModal"
                                    class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form -->
                        <div class="px-6 py-6">
                            <form wire:submit.prevent="save" class="space-y-5">

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Nama Lengkap -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="full_name" type="text" placeholder="Enter Nama Lengkap"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('full_name')
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

                                    <!-- Email -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="email" type="email" placeholder="Enter email"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('email')
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

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Password -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Password <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="password" type="password" placeholder="Enter password"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('password')
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

                                    <!-- Confirm Password -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Confirm Password <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="password_confirmation" type="password"
                                            placeholder="Enter Password Confirmation"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('password_confirmation')
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

                                    <!-- NIP -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            NIP
                                        </label>
                                        <input wire:model="nip" type="text"
                                            placeholder="Enter Nomor Induk Pegawai"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('nip')
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

                                    <!-- Inisial -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Inisial
                                        </label>
                                        <input wire:model="inisial" type="text" placeholder="Enter Inisial"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('inisial')
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

                                    <!-- Gender -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model="gender"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Gender</option>
                                                <option value="laki-laki">Laki-laki</option>
                                                <option value="perempuan">Perempuan</option>
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('gender')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Jenis -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Karyawan
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model="jenis_karyawan"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih jenis</option>
                                                <option value="Guru">Guru</option>
                                                <option value="Pegawai">Pegawai</option>
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('jenis_karyawan')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Status Karyawan -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Karyawan
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model="statuskaryawan_id"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="" class="text-gray-400">Pilih Status</option>
                                                @foreach ($statusKaryawan as $status)
                                                    <option value="{{ $status->id }}">{{ $status->nama_status }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                        @error('statuskaryawan_id')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Efektif -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">
                                            Tanggal Efektif <span class="text-red-500">*</span>
                                        </label>
                                        <input wire:model="tgl_masuk" type="date"
                                            placeholder="Enter Tanggal Masuk"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tgl_masuk')
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
                                <!-- Photo Upload Field -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">
                                        Foto <span class="text-red-500">*</span>
                                    </label>

                                    <div class="relative">
                                        <input wire:model="foto" type="file" accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                            id="photoUpload">

                                        <!-- Upload Area -->
                                        <div
                                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                                            @if ($foto)
                                                <!-- Preview Image -->
                                                <div class="space-y-3">
                                                    <div
                                                        class="mx-auto w-24 h-24 rounded-lg overflow-hidden border-2 border-gray-200">
                                                        @if (is_string($foto))
                                                            <!-- Existing image from database -->
                                                            <img src="{{ asset('storage/' . $foto) }}" alt="Preview"
                                                                class="w-full h-full object-cover">
                                                        @else
                                                            <!-- New uploaded image -->
                                                            <img src="{{ $foto->temporaryUrl() }}" alt="Preview"
                                                                class="w-full h-full object-cover">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @if (is_string($foto))
                                                            <!-- Existing image info -->
                                                            <p class="text-sm font-medium text-gray-700">
                                                                {{ basename($foto) }}</p>
                                                            <p class="text-xs text-gray-500">Foto tersimpan</p>
                                                        @else
                                                            <!-- New uploaded image info -->
                                                            <p class="text-sm font-medium text-gray-700">
                                                                {{ $foto->getClientOriginalName() }}</p>
                                                            <p class="text-xs text-gray-500">
                                                                {{ number_format($foto->getSize() / 1024, 1) }} KB</p>
                                                        @endif
                                                    </div>
                                                    <button type="button" wire:click="$set('foto', null)"
                                                        class="text-xs text-red-600 hover:text-red-700 font-medium">
                                                        Hapus Foto
                                                    </button>
                                                </div>
                                            @else
                                                <!-- Default State -->
                                                <div class="space-y-3">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 48 48">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02">
                                                        </path>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700">Upload Foto</p>
                                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        Pilih Foto
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @error('foto')
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
                        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                            <div class="flex gap-3 justify-end">
                                <button type="button" wire:click="closeModal"
                                    class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-500/20 transition-all">
                                    Cancel
                                </button>
                                <button type="submit" wire:click="save"
                                    class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <span wire:loading.remove wire:target="save">
                                        {{ $isEdit ? 'Update Karyawan' : 'Create Karyawan' }}
                                    </span>
                                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                                        <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 2v4m0 12v4m8-8h-4M6 12H2m15.364-6.364l-2.828 2.828M9.464 16.536l-2.828 2.828m9.192-9.192l-2.828 2.828M6.464 6.464L3.636 3.636">
                                            </path>
                                        </svg>
                                        {{ $isEdit ? 'Updating...' : 'Creating...' }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Detail Karyawan -->
        @include('livewire.admin.karyawan.modal-detail-tabs')

        <!-- Import Excel Modal -->
        @if ($showImportModal)
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-[99999] flex items-center justify-center p-4 py-8">
                <div class="relative w-full max-w-2xl mx-auto">
                    <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                        <!-- Header -->
                        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Import Karyawan dari Excel</h3>
                                <button wire:click="closeImportModal()"
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        @if (!$importResult)
                            <form wire:submit.prevent="importKaryawan" class="p-6 space-y-4">
                                <!-- Template Info -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex gap-3">
                                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-sm">
                                            <p class="font-medium text-blue-900">Format File Excel</p>
                                            <p class="text-blue-700 text-xs mt-1">File harus memiliki kolom: NIP, Full Name, Email (opsional), Gender, Status Pegawai, dan lainnya sesuai template.</p>
                                            <a href="{{ asset('template_import_karyawan.xlsx') }}" download class="text-blue-600 hover:text-blue-700 text-xs font-medium mt-2 inline-block">Download Template XLSX →</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- File Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        File Excel
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" wire:model="importFile"
                                        class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-lg file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-green-50 file:text-green-700
                                        hover:file:bg-green-100
                                        focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-0"
                                        accept=".xlsx,.xls,.csv">
                                    @error('importFile')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-2">
                                        Format: Excel (.xlsx, .xls) atau CSV (Max: 5MB)
                                    </p>
                                </div>

                                <!-- Progress Indicator -->
                                @if ($importFile)
                                    <div class="flex items-center gap-2 p-3 bg-green-50 rounded-lg border border-green-200">
                                        <svg class="w-5 h-5 text-green-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-green-700">File siap untuk diimport</span>
                                    </div>
                                @endif

                                <!-- Buttons -->
                                <div class="flex gap-3 pt-4">
                                    <button type="button" wire:click="closeImportModal()"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition duration-200">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition duration-200">
                                        Import Karyawan
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- Import Result -->
                            <div class="p-6 space-y-4">
                                <!-- Success Summary -->
                                @if ($importResult['successCount'] > 0)
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                        <div class="flex gap-3">
                                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-green-900">Import Berhasil</p>
                                                <p class="text-sm text-green-700 mt-1">{{ $importResult['successCount'] }} karyawan berhasil ditambahkan ke sistem</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Error Summary -->
                                @if ($importResult['errorCount'] > 0)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex gap-3">
                                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M9 3h6a9 9 0 019 9v6a9 9 0 01-9 9H9a9 9 0 01-9-9V9a9 9 0 019-9z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-medium text-yellow-900">Ada {{ $importResult['errorCount'] }} Baris dengan Error</p>
                                                <p class="text-sm text-yellow-700 mt-1">Baris ini tidak diimport, silakan perbaiki dan coba lagi</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Error Details -->
                                    <div class="max-h-96 overflow-y-auto">
                                        <table class="w-full text-sm">
                                            <thead class="bg-gray-100 sticky top-0">
                                                <tr>
                                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Baris</th>
                                                    <th class="px-4 py-2 text-left font-medium text-gray-700">NIP</th>
                                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Nama</th>
                                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Error</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach ($importResult['errors'] as $error)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-2 text-gray-600">{{ $error['row'] }}</td>
                                                        <td class="px-4 py-2 text-gray-600">{{ $error['nip'] }}</td>
                                                        <td class="px-4 py-2 text-gray-600">{{ $error['full_name'] }}</td>
                                                        <td class="px-4 py-2 text-red-600 text-xs">{{ $error['error'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                <!-- Summary Stats -->
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                                        <p class="text-xs text-blue-600 font-medium">Berhasil</p>
                                        <p class="text-2xl font-bold text-blue-600">{{ $importResult['successCount'] }}</p>
                                    </div>
                                    <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                                        <p class="text-xs text-yellow-600 font-medium">Error</p>
                                        <p class="text-2xl font-bold text-yellow-600">{{ $importResult['errorCount'] }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                        <p class="text-xs text-gray-600 font-medium">Total</p>
                                        <p class="text-2xl font-bold text-gray-600">{{ $importResult['successCount'] + $importResult['errorCount'] }}</p>
                                    </div>
                                </div>

                                <!-- Close Button -->
                                <div class="flex gap-3 pt-4">
                                    <button wire:click="closeImportModal()"
                                        class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal Konfirmasi Floating (Tanpa overlay) -->
        <x-modal-confirmation.modal-confirm-delete wire:model="confirmingDelete" onConfirm="delete" />
        <x-modal-confirmation.modal-force-delete />
        <x-modal-confirmation.modal-restore />
        {{-- end modal --}}
    </div>
