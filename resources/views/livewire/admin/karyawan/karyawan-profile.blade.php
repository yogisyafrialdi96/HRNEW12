<div>
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
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

        <!-- Main Card -->
        <div
            class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm border border-gray-200/50 dark:border-gray-700/50 rounded-2xl shadow-xl shadow-gray-200/20 dark:shadow-gray-900/30">

            <!-- Employee Info Header -->
            <div
                class="p-6 border-b border-gray-200/50 dark:border-gray-700/50 bg-gradient-to-r from-blue-500/5 to-indigo-500/5 rounded-t-2xl">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $karyawan->nama ?? 'Data Karyawan' }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">ID: {{ $karyawan->id ?? '-' }}</p>
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

            <!-- Enhanced Navigation Tabs -->
            <div class="p-6">
                <div class="overflow-x-auto">
                    <div class="flex space-x-2 min-w-max pb-4">
                        @foreach ($tabs as $key => $tab)
                            @php
                                $isActive = $activeTab === $key;
                                $colorClasses = [
                                    'blue' => $isActive
                                        ? 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-700'
                                        : 'hover:bg-blue-50 dark:hover:bg-blue-900/30',
                                    'emerald' => $isActive
                                        ? 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/50 dark:text-emerald-300 dark:border-emerald-700'
                                        : 'hover:bg-emerald-50 dark:hover:bg-emerald-900/30',
                                    'purple' => $isActive
                                        ? 'bg-purple-100 text-purple-700 border-purple-200 dark:bg-purple-900/50 dark:text-purple-300 dark:border-purple-700'
                                        : 'hover:bg-purple-50 dark:hover:bg-purple-900/30',
                                    'amber' => $isActive
                                        ? 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/50 dark:text-amber-300 dark:border-amber-700'
                                        : 'hover:bg-amber-50 dark:hover:bg-amber-900/30',
                                    'rose' => $isActive
                                        ? 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-900/50 dark:text-rose-300 dark:border-rose-700'
                                        : 'hover:bg-rose-50 dark:hover:bg-rose-900/30',
                                    'cyan' => $isActive
                                        ? 'bg-cyan-100 text-cyan-700 border-cyan-200 dark:bg-cyan-900/50 dark:text-cyan-300 dark:border-cyan-700'
                                        : 'hover:bg-cyan-50 dark:hover:bg-cyan-900/30',
                                    'pink' => $isActive
                                        ? 'bg-pink-100 text-pink-700 border-pink-200 dark:bg-pink-900/50 dark:text-pink-300 dark:border-pink-700'
                                        : 'hover:bg-pink-50 dark:hover:bg-pink-900/30',
                                    'indigo' => $isActive
                                        ? 'bg-indigo-100 text-indigo-700 border-indigo-200 dark:bg-indigo-900/50 dark:text-indigo-300 dark:border-indigo-700'
                                        : 'hover:bg-indigo-50 dark:hover:bg-indigo-900/30',
                                    'green' => $isActive
                                        ? 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/50 dark:text-green-300 dark:border-green-700'
                                        : 'hover:bg-green-50 dark:hover:bg-green-900/30',
                                    'orange' => $isActive
                                        ? 'bg-orange-100 text-orange-700 border-orange-200 dark:bg-orange-900/50 dark:text-orange-300 dark:border-orange-700'
                                        : 'hover:bg-orange-50 dark:hover:bg-orange-900/30',
                                    'yellow' => $isActive
                                        ? 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/50 dark:text-yellow-300 dark:border-yellow-700'
                                        : 'hover:bg-yellow-50 dark:hover:bg-yellow-900/30',
                                    'slate' => $isActive
                                        ? 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-900/50 dark:text-slate-300 dark:border-slate-700'
                                        : 'hover:bg-slate-50 dark:hover:bg-slate-900/30',
                                    'teal' => $isActive
                                        ? 'bg-teal-100 text-teal-700 border-teal-200 dark:bg-teal-900/50 dark:text-teal-300 dark:border-teal-700'
                                        : 'hover:bg-teal-50 dark:hover:bg-teal-900/30',
                                ];
                            @endphp

                            <a href="{{ route('karyawan.edit', [$karyawan->id, $key]) }}" wire:navigate
                                class="group relative flex flex-col items-center p-4 min-w-[120px] rounded-xl border-2 transition-all duration-300 transform hover:scale-105 hover:shadow-lg
                                      {{ $isActive
                                          ? $colorClasses[$tab['color']] . ' shadow-md'
                                          : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:shadow-md ' .
                                              $colorClasses[$tab['color']] }}">

                                <!-- Icon -->
                                <div
                                    class="mb-2 p-2 rounded-full {{ $isActive ? 'bg-white/50 dark:bg-gray-800/50' : 'bg-gray-100 dark:bg-gray-700 group-hover:bg-white/50 dark:group-hover:bg-gray-600' }} transition-colors duration-200">
                                    @switch($tab['icon'])
                                        @case('user')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        @break

                                        @case('document')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @break

                                        @case('briefcase')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                            </svg>
                                        @break

                                        @case('academic-cap')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                            </svg>
                                        @break

                                        @case('users')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                            </svg>
                                        @break

                                        @case('clipboard-list')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                                            </svg>
                                        @break

                                        @case('heart')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        @break

                                        @case('globe')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                        @break

                                        @case('badge-check')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                            </svg>
                                        @break

                                        @case('chart-bar')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        @break

                                        @case('trophy')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                                            </svg>
                                        @break
                                        @case('document-text')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                        @break
                                        @case('credit-card')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                            </svg>

                                        @break
                                    @endswitch
                                </div>

                                <!-- Label and Description -->
                                <div class="text-center">
                                    <div class="font-semibold text-sm mb-1">{{ $tab['label'] }}</div>
                                    <div class="text-xs opacity-75">{{ $tab['description'] }}</div>
                                </div>

                                <!-- Active Indicator -->
                                @if ($isActive)
                                    <div
                                        class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-current rounded-full">
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            
            <!-- Tab Content -->
            <div class="p-6 pt-0">
                <div
                    class="bg-white/50 dark:bg-gray-800/50 rounded-xl border border-gray-200/50 dark:border-gray-700/50 p-6 min-h-[400px] shadow-inner">
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