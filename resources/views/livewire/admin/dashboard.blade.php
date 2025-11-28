<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Informasi ringkas manajemen pegawai</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">
        
        <!-- Total Pegawai -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pegawai</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_employees'] }}</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM15 20H9m6 0h6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pegawai Aktif -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pegawai Aktif</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['active_employees'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ round(($stats['active_employees'] / max($stats['total_employees'], 1)) * 100, 1) }}% dari total
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-green-100 dark:bg-green-900/30">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pegawai Non-Aktif -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pegawai Non-Aktif</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['inactive_employees'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ round(($stats['inactive_employees'] / max($stats['total_employees'], 1)) * 100, 1) }}% dari total
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-red-100 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jenis Pegawai (Pegawai) -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pegawai (Jenis)</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['pegawai_count'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ round(($stats['pegawai_count'] / max($stats['total_employees'], 1)) * 100, 1) }}% dari total
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-amber-100 dark:bg-amber-900/30">
                        <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jenis Pegawai (Guru) -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Guru (Jenis)</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['guru_count'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                        {{ round(($stats['guru_count'] / max($stats['total_employees'], 1)) * 100, 1) }}% dari total
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-purple-100 dark:bg-purple-900/30">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rata-rata Usia -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200 dark:border-slate-700 p-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Usia</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['average_age'] }} <span class="text-sm">tahun</span></p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Berdasarkan tgl lahir</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m7 8H3V7a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Gender Stats Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6">
        
        <!-- Gender Distribution -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pegawai Aktif Berdasarkan Gender</h2>
            <div class="space-y-3">
                @php
                    $totalActiveByGender = array_sum(array_column($genderStats, 'count'));
                @endphp
                @forelse($genderStats as $gender)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            @if($gender['gender'] === 'laki-laki')
                                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-pink-100 dark:bg-pink-900/30 flex-shrink-0">
                                    <svg class="h-5 w-5 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $gender['gender'] }}</p>
                                <div class="mt-1 w-40 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full" style="width: {{ ($gender['count'] / max($totalActiveByGender, 1)) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $gender['count'] }}</p>
                            <p class="text-xs text-gray-500">{{ round(($gender['count'] / max($totalActiveByGender, 1)) * 100, 1) }}%</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                        <p>Tidak ada data gender tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chart Placeholder for Contract Types -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Karyawan Aktif Berdasarkan Jenis Kontrak</h2>
            @if(count($contractStats) > 0)
                <div class="space-y-3">
                    @php
                        $totalContracts = array_sum(array_column($contractStats, 'total'));
                        $colors = ['bg-blue-500', 'bg-green-500', 'bg-amber-500', 'bg-red-500', 'bg-purple-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500'];
                    @endphp
                    @foreach($contractStats as $index => $contract)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <div class="flex items-center justify-center h-10 w-10 rounded-full {{ $colors[$index % count($colors)] }} flex-shrink-0">
                                    <span class="text-xs font-bold text-white">{{ $index + 1 }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $contract['name'] }}</p>
                                    @if($contract['total'] > 0)
                                        <div class="mt-1 w-40 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="{{ $colors[$index % count($colors)] }} h-2 rounded-full" style="width: {{ ($contract['total'] / max($totalContracts, 1)) * 100 }}%"></div>
                                        </div>
                                    @else
                                        <div class="mt-1 text-xs text-gray-400">Tidak ada data</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right ml-4">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $contract['total'] }}</p>
                                @if($totalContracts > 0 && $contract['total'] > 0)
                                    <p class="text-xs text-gray-500">{{ round(($contract['total'] / max($totalContracts, 1)) * 100, 1) }}%</p>
                                @else
                                    <p class="text-xs text-gray-400">-</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                    <p>Tidak ada kontrak aktif</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Status Pegawai Section -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Status Pegawai</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            
            <!-- Status Chart with Tailwind -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-6">Persentase Status Pegawai</h3>
                <div class="space-y-4">
                    @php
                        $statusColors = [
                            1 => 'bg-green-500',   // Aktif
                            2 => 'bg-red-500',     // Resign
                            3 => 'bg-gray-500',    // Pensiun
                            4 => 'bg-slate-500',   // Pensiun Dini
                            5 => 'bg-amber-500',   // LWP
                            6 => 'bg-blue-500',    // Tugas Belajar
                            7 => 'bg-orange-500',  // Habis Kontrak
                            8 => 'bg-gray-900',    // Meninggal Dunia
                        ];
                    @endphp
                    @foreach($statusStats as $status)
                        @php
                            $total = array_sum(array_column($statusStats, 'total'));
                            $percentage = $total > 0 ? round(($status['total'] / $total) * 100, 1) : 0;
                            $colorClass = $statusColors[$status['id']] ?? 'bg-gray-500';
                        @endphp
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $status['name'] }}</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $status['total'] }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="{{ $colorClass }} h-3 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status Summary Cards -->
            <div class="space-y-3">
                @php
                    $statusColorMap = [
                        1 => ['bg' => 'bg-green-50 dark:bg-green-900/20', 'text' => 'text-green-600 dark:text-green-400', 'icon' => 'bg-green-100 dark:bg-green-900/30'],
                        2 => ['bg' => 'bg-red-50 dark:bg-red-900/20', 'text' => 'text-red-600 dark:text-red-400', 'icon' => 'bg-red-100 dark:bg-red-900/30'],
                        3 => ['bg' => 'bg-gray-50 dark:bg-gray-900/20', 'text' => 'text-gray-600 dark:text-gray-400', 'icon' => 'bg-gray-100 dark:bg-gray-900/30'],
                        4 => ['bg' => 'bg-slate-50 dark:bg-slate-900/20', 'text' => 'text-slate-600 dark:text-slate-400', 'icon' => 'bg-slate-100 dark:bg-slate-900/30'],
                        5 => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'text' => 'text-amber-600 dark:text-amber-400', 'icon' => 'bg-amber-100 dark:bg-amber-900/30'],
                        6 => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'text' => 'text-blue-600 dark:text-blue-400', 'icon' => 'bg-blue-100 dark:bg-blue-900/30'],
                        7 => ['bg' => 'bg-orange-50 dark:bg-orange-900/20', 'text' => 'text-orange-600 dark:text-orange-400', 'icon' => 'bg-orange-100 dark:bg-orange-900/30'],
                        8 => ['bg' => 'bg-gray-900/10 dark:bg-gray-800', 'text' => 'text-gray-900 dark:text-white', 'icon' => 'bg-gray-800 dark:bg-gray-700'],
                    ];
                @endphp
                @foreach($statusStats as $status)
                    @php
                        $colorConfig = $statusColorMap[$status['id']] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'icon' => 'bg-gray-100'];
                        $total = array_sum(array_column($statusStats, 'total'));
                        $percentage = $total > 0 ? round(($status['total'] / $total) * 100, 1) : 0;
                    @endphp
                    <div class="flex items-center justify-between p-4 rounded-lg {{ $colorConfig['bg'] }} border border-opacity-20">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg {{ $colorConfig['icon'] }} flex items-center justify-center">
                                <span class="text-sm font-bold {{ $colorConfig['text'] }}">{{ $status['total'] }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $status['name'] }}</p>
                                <p class="text-xs {{ $colorConfig['text'] }}">{{ $percentage }}% dari total</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <!-- Department and Unit Section -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Pegawai per Departemen & Unit</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            
            <!-- Department Distribution -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Pegawai Per Departemen</h3>
                @if(count($departmentStats) > 0)
                    <div class="space-y-3">
                        @php
                            $totalDept = array_sum(array_column($departmentStats, 'total'));
                            $deptColors = ['bg-blue-500', 'bg-green-500', 'bg-amber-500', 'bg-red-500', 'bg-purple-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500'];
                        @endphp
                        @foreach($departmentStats as $index => $dept)
                            @php
                                $percentage = $totalDept > 0 ? round(($dept['total'] / $totalDept) * 100, 1) : 0;
                            @endphp
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $dept['name'] }}</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $dept['total'] }} ({{ $percentage }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="{{ $deptColors[$index % count($deptColors)] }} h-2.5 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                        <p>Tidak ada data departemen</p>
                    </div>
                @endif
            </div>

            <!-- Unit Distribution -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Pegawai Per Unit</h3>
                @if(count($unitStats) > 0)
                    <div class="space-y-3">
                        @php
                            $totalUnit = array_sum(array_column($unitStats, 'total'));
                            $unitColors = ['bg-indigo-500', 'bg-cyan-500', 'bg-lime-500', 'bg-rose-500', 'bg-violet-500', 'bg-fuchsia-500', 'bg-sky-500', 'bg-emerald-500'];
                        @endphp
                        @foreach($unitStats as $index => $unit)
                            @php
                                $percentage = $totalUnit > 0 ? round(($unit['total'] / $totalUnit) * 100, 1) : 0;
                            @endphp
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $unit['name'] }}</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $unit['total'] }} ({{ $percentage }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                    <div class="{{ $unitColors[$index % count($unitColors)] }} h-2.5 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                        <p>Tidak ada data unit</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Education Distribution Section -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Pegawai Aktif Berdasarkan Pendidikan</h2>
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            @if(count($educationStats) > 0)
                <div class="space-y-4">
                    @php
                        $totalEdu = array_sum(array_column($educationStats, 'total'));
                        $eduColors = ['bg-blue-500', 'bg-green-500', 'bg-amber-500', 'bg-red-500', 'bg-purple-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500'];
                    @endphp
                    @foreach($educationStats as $index => $education)
                        @php
                            $percentage = $totalEdu > 0 ? round(($education['total'] / $totalEdu) * 100, 1) : 0;
                        @endphp
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $education['name'] }}</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $education['total'] }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="{{ $eduColors[$index % count($eduColors)] }} h-3 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                    <p>Tidak ada data pendidikan</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Work Relationship Distribution Section -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Distribusi Pegawai Aktif Berdasarkan Hubungan Kerja</h2>
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            @if(count($workRelationshipStats) > 0)
                <div class="space-y-4">
                    @php
                        $totalWork = array_sum(array_column($workRelationshipStats, 'total'));
                        $workColors = ['bg-blue-500', 'bg-green-500', 'bg-amber-500', 'bg-red-500', 'bg-purple-500', 'bg-pink-500', 'bg-teal-500', 'bg-orange-500'];
                    @endphp
                    @foreach($workRelationshipStats as $index => $work)
                        @php
                            $percentage = $totalWork > 0 ? round(($work['total'] / $totalWork) * 100, 1) : 0;
                        @endphp
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $work['name'] }}</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $work['total'] }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="{{ $workColors[$index % count($workColors)] }} h-3 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                    <p>Tidak ada data hubungan kerja</p>
                </div>
            @endif
        </div>
    </div>

    <div class="mb-6">
        <div class="flex items-center justify-between py-3 px-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Keseluruhan</span>
            <span class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_employees'] }}</span>
        </div>
        <div class="flex items-center justify-between py-3 px-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktif</span>
            <span class="text-xl font-bold text-green-600 dark:text-green-400">{{ $stats['active_employees'] }}</span>
        </div>
        <div class="flex items-center justify-between py-3 px-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Non-Aktif</span>
            <span class="text-xl font-bold text-red-600 dark:text-red-400">{{ $stats['inactive_employees'] }}</span>
        </div>
        <div class="flex items-center justify-between py-3 px-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Usia Rata-rata</span>
            <span class="text-xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['average_age'] }} th</span>
        </div>
    </div>
</div>

        
        
