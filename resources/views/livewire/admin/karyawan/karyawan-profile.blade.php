<div>
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        @can('karyawan.view_list')
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl" class="text-gray-900 dark:text-white font-bold">
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Kelola Karyawan
                        </span>
                    </flux:heading>
                    <flux:text class="mt-2 text-gray-600 dark:text-gray-300">
                        Form Edit Data Karyawan - Kelola informasi lengkap pegawai
                    </flux:text>
                </div>

                <!-- Back Button -->
                <flux:button size="sm"
                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm hover:shadow-md transition-all duration-200 group"
                    wire:navigate href="{{ route('karyawan.index') }}">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-200"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Daftar
                </flux:button>
            </div>
        </div>
        @endcan

        <!-- Main Card -->
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">

            <!-- Employee Info Header -->
            <div
                class="p-6 md:p-8 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-br from-blue-50 dark:from-blue-900/20 via-indigo-50 dark:via-indigo-900/20 to-purple-50 dark:to-purple-900/20">
                @php
                    $activeJabatan = $karyawan->activeJabatan()->with(['jabatan', 'department', 'unit'])->first();
                    $firstContract = $karyawan->contracts()->oldest('tglmulai_kontrak')->first();
                    $statusBadge = $karyawan->getStatusBadgeAttribute();
                @endphp
                <div class="flex flex-col md:flex-row items-start gap-6">
                    <!-- Profile Photo -->
                    <div class="flex-shrink-0 relative">
                        @if($karyawan->foto)
                            <img src="{{ Storage::url($karyawan->foto) }}"
                                alt="{{ $karyawan->full_name ?? $karyawan->nama }}"
                                class="w-20 h-20 rounded-2xl object-cover shadow-lg border-4 border-white dark:border-gray-800">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($karyawan->full_name ?? $karyawan->nama ?? 'User') }}&background=random&color=fff&bold=true&size=200"
                                alt="{{ $karyawan->full_name ?? $karyawan->nama }}"
                                class="w-20 h-20 rounded-2xl object-cover shadow-lg border-4 border-white dark:border-gray-800">
                        @endif
                        <!-- Status Badge -->
                        @php
                            $badgeColors = [
                                1 => 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300',
                                2 => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
                                3 => 'bg-gray-100 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300',
                                4 => 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300',
                                5 => 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300',
                                6 => 'bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300',
                                7 => 'bg-black dark:bg-black text-white dark:text-white',
                            ];
                            $badgeClass = $badgeColors[$karyawan->statuskaryawan_id] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                        @endphp
                        <div class="absolute -bottom-2 -right-2 px-3 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }} shadow-md border border-current border-opacity-20">
                            <span>{{ $statusBadge['text'] ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Main Info -->
                    <div class="flex-1 min-w-0 flex flex-col justify-start">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <!-- Name and NIP -->
                            <div class="min-w-0">
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white truncate">
                                    {{ $karyawan->full_name ?? $karyawan->nama ?? 'Data Karyawan' }}
                                </h2>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                                        NIP: {{ $karyawan->nip ?? '-' }}
                                    </span>
                                    @if($karyawan->inisial)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-700 text-yellow-700 dark:text-yellow-300">
                                            {{ $karyawan->inisial }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quick Status Info -->
                            @if($activeJabatan || $firstContract)
                                <div class="flex flex-wrap gap-2">
                                    @if($activeJabatan)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 whitespace-nowrap">
                                            ✓ Posisi Aktif
                                        </span>
                                    @endif
                                    @if($firstContract)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 whitespace-nowrap">
                                            📅 {{ \Carbon\Carbon::parse($firstContract->tglmulai_kontrak)->format('Y') }} - Sekarang
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Detailed Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mt-5">
                            @if($activeJabatan)
                                <!-- Jabatan -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Jabatan</p>
                                    <p class="text-sm md:text-base font-semibold text-gray-900 dark:text-white mt-1 line-clamp-2">{{ $activeJabatan->jabatan?->nama_jabatan ?? '-' }}</p>
                                </div>

                                <!-- Unit -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Unit</p>
                                    <p class="text-sm md:text-base font-semibold text-gray-900 dark:text-white mt-1 line-clamp-2">{{ $activeJabatan->unit?->unit ?? '-' }}</p>
                                </div>

                                <!-- Departemen -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Departemen</p>
                                    <p class="text-sm md:text-base font-semibold text-gray-900 dark:text-white mt-1 line-clamp-2">{{ $activeJabatan->department?->department ?? '-' }}</p>
                                </div>

                                <!-- Mulai Jabatan -->
                                @if($activeJabatan->tgl_mulai)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Efektif</p>
                                        <p class="text-sm md:text-base font-semibold text-gray-900 dark:text-white mt-1">{{ \Carbon\Carbon::parse($firstContract->tglmulai_kontrak)->translatedFormat('d F Y') }}</p>
                                    </div>
                                @endif
                            @else
                                <div class="col-span-full bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 text-center">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">⚠️ Belum ada jabatan aktif</p>
                                </div>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>

            @php
                $tabs = [
                    'profile' => [
                        'label' => 'Profile',
                        'icon' => 'user',
                        'description' => 'Data pribadi',
                        'color' => 'blue',
                    ],
                    'kontrak' => [
                        'label' => 'Kontrak',
                        'icon' => 'document',
                        'description' => 'Info kontrak',
                        'color' => 'emerald',
                    ],
                    'jabatan' => [
                        'label' => 'Jabatan',
                        'icon' => 'briefcase',
                        'description' => 'Posisi & role',
                        'color' => 'purple',
                    ],
                    'pendidikan' => [
                        'label' => 'Pendidikan',
                        'icon' => 'academic-cap',
                        'description' => 'Riwayat studi',
                        'color' => 'amber',
                    ],
                    'organisasi' => [
                        'label' => 'Organisasi',
                        'icon' => 'users',
                        'description' => 'Keanggotaan',
                        'color' => 'rose',
                    ],
                    'pekerjaan' => [
                        'label' => 'Pekerjaan',
                        'icon' => 'clipboard-list',
                        'description' => 'Riwayat kerja',
                        'color' => 'cyan',
                    ],
                    'keluarga' => [
                        'label' => 'Keluarga',
                        'icon' => 'heart',
                        'description' => 'Data keluarga',
                        'color' => 'pink',
                    ],
                    'bahasa' => [
                        'label' => 'Bahasa',
                        'icon' => 'globe',
                        'description' => 'Kemampuan bahasa',
                        'color' => 'indigo',
                    ],
                    'sertifikasi' => [
                        'label' => 'Sertifikasi',
                        'icon' => 'badge-check',
                        'description' => 'Sertifikat',
                        'color' => 'green',
                    ],
                    'pelatihan' => [
                        'label' => 'Pelatihan',
                        'icon' => 'chart-bar',
                        'description' => 'Training',
                        'color' => 'orange',
                    ],
                    'prestasi' => [
                        'label' => 'Prestasi',
                        'icon' => 'trophy',
                        'description' => 'Penghargaan',
                        'color' => 'yellow',
                    ],
                    'dokumen' => [
                        'label' => 'Dokumen',
                        'icon' => 'document-text',
                        'description' => 'Dokumen',
                        'color' => 'slate',
                    ],
                    'bank' => [
                        'label' => 'Bank',
                        'icon' => 'credit-card',
                        'description' => 'Akun Bank',
                        'color' => 'teal',
                    ],
                ];
            @endphp

            <!-- Minimalist Sticky Tabs -->
            <div class="sticky top-0 md:top-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-md md:shadow-sm">
                <div class="overflow-x-auto">
                    <div class="flex space-x-1 px-6 py-2 min-w-max">
                        @foreach ($tabs as $key => $tab)
                            @php
                                $isActive = $activeTab === $key;
                                // Determine route based on user permission
                                if (auth()->user()->hasPermissionTo('karyawan.view_list')) {
                                    $tabRoute = route('karyawan.edit', [$karyawan->id, $key]);
                                } else {
                                    $tabRoute = route('karyawan.profile', [$karyawan->id, $key]);
                                }
                            @endphp

                            <a href="{{ $tabRoute }}" wire:navigate
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                                      {{ $isActive
                                          ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 shadow-sm'
                                          : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">

                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    @switch($tab['icon'])
                                        @case('user')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @break
                                        @case('document')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @break
                                        @case('briefcase')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                                            </svg>
                                        @break
                                        @case('academic-cap')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                            </svg>
                                        @break
                                        @case('users')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                            </svg>
                                        @break
                                        @case('clipboard-list')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                                            </svg>
                                        @break
                                        @case('heart')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        @break
                                        @case('globe')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                        @break
                                        @case('badge-check')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                            </svg>
                                        @break
                                        @case('chart-bar')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        @break
                                        @case('trophy')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                                            </svg>
                                        @break
                                        @case('document-text')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                        @break
                                        @case('credit-card')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                            </svg>
                                        @break
                                    @endswitch
                                </div>

                                <!-- Label -->
                                <span>{{ $tab['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            
            <!-- Tab Content -->
            <div class="p-4 md:p-6">
                <div
                    class="bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 p-4 md:p-6 min-h-[600px] overflow-auto">
                    @if ($activeTab === 'profile')
                        @livewire('admin.karyawan.karyawan-form', ['karyawan' => $karyawan], key('profile'))
                    @elseif ($activeTab === 'kontrak')
                        @livewire('admin.karyawan.tab.kontrak.index', ['karyawan' => $karyawan], key('kontrak'))
                    @elseif ($activeTab === 'jabatan')
                        @livewire('admin.karyawan.tab.jabatan.index', ['karyawan' => $karyawan], key('jabatan'))
                    @elseif ($activeTab === 'pendidikan')
                        @livewire('admin.karyawan.tab.pendidikan.index', ['karyawan' => $karyawan], key('pendidikan'))
                    @elseif ($activeTab === 'organisasi')
                        @livewire('admin.karyawan.tab.organisasi.index', ['karyawan' => $karyawan], key('organisasi'))
                    @elseif ($activeTab === 'pekerjaan')
                        @livewire('admin.karyawan.tab.pekerjaan.index', ['karyawan' => $karyawan], key('pekerjaan'))
                    @elseif ($activeTab === 'keluarga')
                        @livewire('admin.karyawan.tab.keluarga.index', ['karyawan' => $karyawan], key('keluarga'))
                    @elseif ($activeTab === 'bahasa')
                        @livewire('admin.karyawan.tab.bahasa.index', ['karyawan' => $karyawan], key('bahasa'))
                    @elseif ($activeTab === 'sertifikasi')
                        @livewire('admin.karyawan.tab.sertifikasi.index', ['karyawan' => $karyawan], key('sertifikasi'))
                    @elseif ($activeTab === 'pelatihan')
                        @livewire('admin.karyawan.tab.pelatihan.index', ['karyawan' => $karyawan], key('pelatihan'))
                    @elseif ($activeTab === 'prestasi')
                        @livewire('admin.karyawan.tab.prestasi.index', ['karyawan' => $karyawan], key('prestasi'))
                    @elseif ($activeTab === 'dokumen')
                        @livewire('admin.karyawan.tab.dokumen.index', ['karyawan' => $karyawan], key('dokumen'))
                    @elseif ($activeTab === 'bank')
                        @livewire('admin.karyawan.tab.bank.index', ['karyawan' => $karyawan], key('rekening'))
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>