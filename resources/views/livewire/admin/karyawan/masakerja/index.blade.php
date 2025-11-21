<div>

    <flux:heading size="xl">Masa Kerja</flux:heading>
    <flux:text class="mt-2">This Page Show List of Masa Kerja</flux:text>

    <div class="relative overflow-x-auto bg-white rounded-lg shadow-md p-6 mb-4 mt-4">
        <div class="space-y-4">

            <!-- Filters and Actions Row -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                <!-- Filters Grid (4 columns for 3 filters) -->
                <div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Unit Filter -->
                    <select wire:model.live="unitFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Unit</option>
                        @foreach ($this->availableUnits as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                        @endforeach
                    </select>

                    <!-- Milestone Filter -->
                    <select wire:model.live="milestoneFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Masa Kerja</option>
                        <option value="5">5 Tahun</option>
                        <option value="10">10 Tahun</option>
                        <option value="15">15 Tahun</option>
                        <option value="20">20 Tahun</option>
                        <option value="25">25 Tahun</option>
                        <option value="30">30 Tahun</option>
                        <option value="35">35 Tahun</option>
                    </select>

                    <!-- Status Filter -->
                    <select wire:model.live="statusFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Status</option>
                        @foreach ($this->availableStatuses as $status)
                            <option value="{{ $status->id }}">{{ $status->nama_status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
        <div class="relative overflow-x-auto mt-4 shadow-md sm:rounded-lg">
            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between p-3">
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
                        placeholder="Search Nama, NIP, Awal Kerja...">
                </div>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- No - Fixed width -->
                        <th
                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 w-12">
                            <div class="flex items-center gap-2">
                                <span>No</span>
                            </div>
                        </th>
                        <!-- Nama - Wider column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 min-w-80"
                            wire:click="sortBy('full_name')">
                            <div class="flex items-center gap-2">
                                <span>Nama</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'full_name')
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
                        <!-- NIP -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('nip')">
                            <div class="flex items-center gap-2">
                                <span>NIP</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'nip')
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
                        <!-- Status Karyawan -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <span>Status</span>
                            </div>
                        </th>
                        <!-- Awal Kerja -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('created_at')">
                            <div class="flex items-center gap-2">
                                <span>Awal Kerja</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'created_at')
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
                        <!-- Info Pensiun -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <span>Info Pensiun</span>
                            </div>
                        </th>
                        <!-- Masa Kerja Berjalan -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <span>Masa Kerja</span>
                            </div>
                        </th>
                        <!-- 5th Anniversary -->
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <span>5th</span>
                            </div>
                        </th>
                        <!-- 10th Anniversary -->
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <span>10th</span>
                            </div>
                        </th>
                        <!-- 15th Anniversary -->
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <span>15th</span>
                            </div>
                        </th>
                        <!-- 20th Anniversary -->
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <span>20th</span>
                            </div>
                        </th>
                        <!-- 25th Anniversary -->
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <span>25th</span>
                            </div>
                        </th>
                        <!-- 30th Anniversary -->
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <span>30th</span>
                            </div>
                        </th>
                        <!-- 35th Anniversary -->
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center justify-center gap-2">
                                <span>35th</span>
                            </div>
                        </th>

                        
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($karyawans as $karyawan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $karyawans->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4 min-w-80">
                                <div class="flex items-center space-x-3">
                                    <img class="w-8 h-8 rounded-full object-cover"
                                        src="{{ $karyawan->foto
                                            ? asset('storage/' . $karyawan->foto)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($karyawan->full_name) }}"
                                        alt="{{ $karyawan->full_name }}">
                                    <div class="text-sm flex-1">
                                        <div class="font-semibold text-gray-900">{{ $karyawan->full_name ?? 'N/A' }}</div>
                                        @if($karyawan->activeJabatan)
                                            <div class="text-xs text-gray-500">
                                                <span class="font-medium">{{ $karyawan->activeJabatan->jabatan->nama_jabatan }}</span>
                                                - {{ $karyawan->activeJabatan->unit->unit }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $karyawan->nip ?? '-' }}
                            </td>
                            <!-- Status Karyawan -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($karyawan->statusPegawai)
                                    @php
                                        $badgeConfig = \App\Models\Master\StatusPegawai::getBadgeConfig($karyawan->statusPegawai->id);
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $badgeConfig['class'] }}">
                                        {{ $badgeConfig['label'] }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <!-- Awal Kerja -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($karyawan->contracts && $karyawan->contracts->count() > 0 && $karyawan->contracts[0]->tglmulai_kontrak)
                                        <span class="font-semibold">{{ \Carbon\Carbon::parse($karyawan->contracts[0]->tglmulai_kontrak)->translatedFormat('d M Y') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <!-- Info Pensiun -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($karyawan->retirement_info)
                                    <div class="flex flex-col gap-1">
                                        <!-- Tanggal Pensiun -->
                                        <div class="text-sm">
                                            <span class="font-semibold text-gray-700">Pensiun:</span>
                                            @if($karyawan->retirement_info['status'] === 'retired')
                                                <span class="text-gray-600">{{ $karyawan->retirement_info['formatted_retirement_date'] }}</span>
                                            @else
                                                <span class="text-blue-600">{{ $karyawan->retirement_info['formatted_retirement_date'] }}</span>
                                            @endif
                                        </div>
                                        <!-- Tanggal Lahir -->
                                        <div class="text-xs text-gray-600">
                                            <span class="font-semibold">Lahir:</span> {{ \Carbon\Carbon::parse($karyawan->tanggal_lahir)->translatedFormat('d M Y') }}
                                        </div>
                                        <!-- Akan Pensiun -->
                                        <div class="text-sm">
                                            @if($karyawan->retirement_info['status'] === 'retired')
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
                                                    ✓ Telah Pensiun
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    📅 {{ $karyawan->retirement_info['formatted'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <!-- Usia Saat Ini -->
                                        <div class="text-xs text-gray-500">
                                            <span class="font-semibold">Usia:</span> {{ $karyawan->retirement_info['current_age'] }} tahun
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($karyawan->current_duration)
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-semibold text-gray-900">{{ $karyawan->current_duration['formatted'] }}</span>
                                        <span class="text-xs text-gray-500">({{ $karyawan->current_duration['days'] }} hari)</span>
                                        
                                        {{-- Badge jika ada milestone yang akan datang dalam 30 hari --}}
                                        @if($karyawan->upcoming_milestone)
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 w-fit mt-1">
                                                ⚠️ Milestone {{ $karyawan->upcoming_milestone }} Th
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($karyawan->milestones && isset($karyawan->milestones[5]))
                                    @php
                                        $milestone = $karyawan->milestones[5];
                                        $milestoneDate = \Carbon\Carbon::parse($milestone['date']);
                                        $isBeforeRetirement = $karyawan->isMilestoneBeforeRetirement($milestoneDate);
                                        $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
                                    @endphp
                                    @if($isBeforeRetirement)
                                        <div class="flex flex-col gap-1 relative items-center">
                                            {{-- Badge alert jika milestone segera datang --}}
                                            @if($milestone['status'] === 'upcoming-soon')
                                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                                                </span>
                                            @endif
                                            <span class="text-sm font-medium">{{ $milestone['formatted_date'] }}</span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($karyawan->milestones && isset($karyawan->milestones[10]))
                                    @php
                                        $milestone = $karyawan->milestones[10];
                                        $milestoneDate = \Carbon\Carbon::parse($milestone['date']);
                                        $isBeforeRetirement = $karyawan->isMilestoneBeforeRetirement($milestoneDate);
                                        $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
                                    @endphp
                                    @if($isBeforeRetirement)
                                        <div class="flex flex-col gap-1 relative items-center">
                                            {{-- Badge alert jika milestone segera datang --}}
                                            @if($milestone['status'] === 'upcoming-soon')
                                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                                                </span>
                                            @endif
                                            <span class="text-sm font-medium">{{ $milestone['formatted_date'] }}</span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($karyawan->milestones && isset($karyawan->milestones[15]))
                                    @php
                                        $milestone = $karyawan->milestones[15];
                                        $milestoneDate = \Carbon\Carbon::parse($milestone['date']);
                                        $isBeforeRetirement = $karyawan->isMilestoneBeforeRetirement($milestoneDate);
                                        $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
                                    @endphp
                                    @if($isBeforeRetirement)
                                        <div class="flex flex-col gap-1 relative items-center">
                                            {{-- Badge alert jika milestone segera datang --}}
                                            @if($milestone['status'] === 'upcoming-soon')
                                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                                                </span>
                                            @endif
                                            <span class="text-sm font-medium">{{ $milestone['formatted_date'] }}</span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($karyawan->milestones && isset($karyawan->milestones[20]))
                                    @php
                                        $milestone = $karyawan->milestones[20];
                                        $milestoneDate = \Carbon\Carbon::parse($milestone['date']);
                                        $isBeforeRetirement = $karyawan->isMilestoneBeforeRetirement($milestoneDate);
                                        $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
                                    @endphp
                                    @if($isBeforeRetirement)
                                        <div class="flex flex-col gap-1 relative items-center">
                                            {{-- Badge alert jika milestone segera datang --}}
                                            @if($milestone['status'] === 'upcoming-soon')
                                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                                                </span>
                                            @endif
                                            <span class="text-sm font-medium">{{ $milestone['formatted_date'] }}</span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($karyawan->milestones && isset($karyawan->milestones[25]))
                                    @php
                                        $milestone = $karyawan->milestones[25];
                                        $milestoneDate = \Carbon\Carbon::parse($milestone['date']);
                                        $isBeforeRetirement = $karyawan->isMilestoneBeforeRetirement($milestoneDate);
                                        $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
                                    @endphp
                                    @if($isBeforeRetirement)
                                        <div class="flex flex-col gap-1 relative items-center">
                                            {{-- Badge alert jika milestone segera datang --}}
                                            @if($milestone['status'] === 'upcoming-soon')
                                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                                                </span>
                                            @endif
                                            <span class="text-sm font-medium">{{ $milestone['formatted_date'] }}</span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($karyawan->milestones && isset($karyawan->milestones[30]))
                                    @php
                                        $milestone = $karyawan->milestones[30];
                                        $milestoneDate = \Carbon\Carbon::parse($milestone['date']);
                                        $isBeforeRetirement = $karyawan->isMilestoneBeforeRetirement($milestoneDate);
                                        $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
                                    @endphp
                                    @if($isBeforeRetirement)
                                        <div class="flex flex-col gap-1 relative items-center">
                                            {{-- Badge alert jika milestone segera datang --}}
                                            @if($milestone['status'] === 'upcoming-soon')
                                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                                                </span>
                                            @endif
                                            <span class="text-sm font-medium">{{ $milestone['formatted_date'] }}</span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <!-- 35th Anniversary -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($karyawan->milestones && isset($karyawan->milestones[35]))
                                    @php
                                        $milestone = $karyawan->milestones[35];
                                        $milestoneDate = \Carbon\Carbon::parse($milestone['date']);
                                        $isBeforeRetirement = $karyawan->isMilestoneBeforeRetirement($milestoneDate);
                                        $badge = \App\Models\Employee\Karyawan::getMilestoneBadgeConfig($milestone['status']);
                                    @endphp
                                    @if($isBeforeRetirement)
                                        <div class="flex flex-col gap-1 relative items-center">
                                            {{-- Badge alert jika milestone segera datang --}}
                                            @if($milestone['status'] === 'upcoming-soon')
                                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                                                </span>
                                            @endif
                                            <span class="text-sm font-medium">{{ $milestone['formatted_date'] }}</span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">No Masa Kerja found</p>
                                    <p class="text-sm">Get started by creating a new Kontrak Kerja.</p>
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


</div>
