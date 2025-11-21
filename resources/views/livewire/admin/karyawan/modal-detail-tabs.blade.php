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
                    class="relative w-full max-w-6xl mx-auto my-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg flex flex-col max-h-[90vh]">

                    <!-- Header -->
                    <div class="bg-white dark:bg-gray-800 flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700 rounded-t-lg">
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Profile Karyawan</h2>
                            @if($selectedKaryawan)
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedKaryawan->full_name }}</span>
                            @endif
                        </div>
                        <button wire:click="closeModal"
                            class="p-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Tab Navigation -->
                    <div class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 overflow-x-auto">
                        <div class="flex space-x-1 px-4 py-3">
                            <button wire:click="switchTab('profile')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'profile' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                👤 Profile
                            </button>
                            <button wire:click="switchTab('kontrak')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'kontrak' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                📜 Kontrak
                            </button>
                            <button wire:click="switchTab('jabatan')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'jabatan' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                💼 Jabatan
                            </button>
                            <button wire:click="switchTab('pendidikan')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'pendidikan' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                🎓 Pendidikan
                            </button>
                            <button wire:click="switchTab('organisasi')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'organisasi' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                🏢 Organisasi
                            </button>
                            <button wire:click="switchTab('pekerjaan')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'pekerjaan' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                🎯 Pekerjaan
                            </button>
                            <button wire:click="switchTab('keluarga')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'keluarga' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                👨‍👩‍👧 Keluarga
                            </button>
                            <button wire:click="switchTab('bahasa')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'bahasa' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                🗣️ Bahasa
                            </button>
                            <button wire:click="switchTab('sertifikasi')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'sertifikasi' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                📜 Sertifikasi
                            </button>
                            <button wire:click="switchTab('pelatihan')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'pelatihan' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                📚 Pelatihan
                            </button>
                            <button wire:click="switchTab('prestasi')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'prestasi' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                🏆 Prestasi
                            </button>
                            <button wire:click="switchTab('dokumen')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'dokumen' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                📋 Dokumen
                            </button>
                            <button wire:click="switchTab('bank')" 
                                class="px-4 py-2 rounded-t-lg font-medium text-sm whitespace-nowrap transition-colors {{ $activeTab === 'bank' ? 'bg-white dark:bg-gray-800 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' }}">
                                🏦 Bank
                            </button>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="flex-1 overflow-y-auto p-6">
                        @if ($selectedKaryawan)
                            
                            <!-- Tab: Profile -->
                            @if($activeTab === 'profile')
                                <div class="space-y-6">
                                    <!-- Info Dasar -->
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">📋 Informasi Dasar</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama Lengkap</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->full_name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">NIP</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->nip ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inisial</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->inisial ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->user?->email ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jenis Kelamin</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ ucfirst($selectedKaryawan->gender) ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status Karyawan</p>
                                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $selectedKaryawan->statusBadge['class'] }}">
                                                    {{ $selectedKaryawan->statusBadge['text'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Pribadi -->
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">👤 Data Pribadi</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal Lahir</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                                    @if($selectedKaryawan->tanggal_lahir)
                                                        {{ \Carbon\Carbon::parse($selectedKaryawan->tanggal_lahir)->format('d M Y') }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tempat Lahir</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->tempat_lahir ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">NIK</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->nik ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">NKK</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->nkk ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Agama</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->agama ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Golongan Darah</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->blood_type ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">No. HP</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->hp ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">No. WhatsApp</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->whatsapp ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status Perkawinan</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->status_kawin ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alamat KTP -->
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🏠 Alamat KTP</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="md:col-span-2">
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Alamat</p>
                                                <p class="text-base text-gray-900 dark:text-white">{{ $selectedKaryawan->alamat_ktp ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">RT</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->rt_ktp ?? '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">RW</p>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedKaryawan->rw_ktp ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Tab: Kontrak -->
                            @if($activeTab === 'kontrak')
                                <div class="space-y-6">
                                    @php
                                        $activeContract = $selectedKaryawan->contracts && $selectedKaryawan->contracts->count() > 0 
                                            ? $selectedKaryawan->contracts->where('status', 'aktif')->first() 
                                            : null;
                                    @endphp
                                    @if($activeContract)
                                        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 p-4 rounded-lg">
                                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">📜 Kontrak Aktif</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                <div>
                                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Jenis Kontrak</p>
                                                    <p class="text-base font-semibold text-blue-900 dark:text-blue-100">{{ $activeContract->kontrak?->nama_kontrak ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Tanggal Mulai</p>
                                                    <p class="text-base font-semibold text-blue-900 dark:text-blue-100">
                                                        @if($activeContract->tglmulai_kontrak)
                                                            {{ \Carbon\Carbon::parse($activeContract->tglmulai_kontrak)->format('d M Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Tanggal Selesai</p>
                                                    <p class="text-base font-semibold text-blue-900 dark:text-blue-100">
                                                        @if($activeContract->tglselesai_kontrak)
                                                            {{ \Carbon\Carbon::parse($activeContract->tglselesai_kontrak)->format('d M Y') }}
                                                        @else
                                                            Tidak terbatas
                                                        @endif
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Status</p>
                                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        Aktif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 p-4 rounded-lg text-center">
                                            <p class="text-yellow-800 dark:text-yellow-200">Tidak ada kontrak aktif</p>
                                        </div>
                                    @endif

                                    @if($selectedKaryawan->contracts && $selectedKaryawan->contracts->count() > 0)
                                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">📜 Riwayat Kontrak</h3>
                                            <div class="space-y-3">
                                                @foreach($selectedKaryawan->contracts as $contract)
                                                    <div class="border-l-4 border-blue-500 pl-4 py-2 bg-white dark:bg-gray-800 p-3 rounded">
                                                        <div class="flex justify-between items-start">
                                                            <div>
                                                                <p class="font-semibold text-gray-900 dark:text-white">{{ $contract->kontrak?->nama_kontrak ?? 'N/A' }}</p>
                                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                                    @if($contract->tglmulai_kontrak)
                                                                        {{ \Carbon\Carbon::parse($contract->tglmulai_kontrak)->format('d M Y') }}
                                                                    @endif
                                                                    @if($contract->tglselesai_kontrak)
                                                                        - {{ \Carbon\Carbon::parse($contract->tglselesai_kontrak)->format('d M Y') }}
                                                                    @else
                                                                        - Tidak terbatas
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $contract->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                                {{ ucfirst($contract->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Jabatan -->
                            @if($activeTab === 'jabatan')
                                <div class="space-y-6">
                                    @if($selectedKaryawan->activeJabatan)
                                        <div class="bg-purple-50 dark:bg-purple-900 border border-purple-200 dark:border-purple-700 p-4 rounded-lg">
                                            <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-4">💼 Jabatan Aktif</h3>
                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                <div>
                                                    <p class="text-sm font-medium text-purple-800 dark:text-purple-200">Jabatan</p>
                                                    <p class="text-base font-semibold text-purple-900 dark:text-purple-100">{{ $selectedKaryawan->activeJabatan->jabatan?->nama_jabatan ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-purple-800 dark:text-purple-200">Department</p>
                                                    <p class="text-base font-semibold text-purple-900 dark:text-purple-100">{{ $selectedKaryawan->activeJabatan->department?->department ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-purple-800 dark:text-purple-200">Unit</p>
                                                    <p class="text-base font-semibold text-purple-900 dark:text-purple-100">{{ $selectedKaryawan->activeJabatan->unit?->unit ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-purple-800 dark:text-purple-200">Hubungan Kerja</p>
                                                    <p class="text-base font-semibold text-purple-900 dark:text-purple-100">{{ $selectedKaryawan->activeJabatan->hub_kerja ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-purple-800 dark:text-purple-200">Tanggal Mulai</p>
                                                    <p class="text-base font-semibold text-purple-900 dark:text-purple-100">
                                                        @if($selectedKaryawan->activeJabatan->tgl_mulai)
                                                            {{ \Carbon\Carbon::parse($selectedKaryawan->activeJabatan->tgl_mulai)->format('d M Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-purple-800 dark:text-purple-200">Status</p>
                                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                        Aktif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 p-4 rounded-lg text-center">
                                            <p class="text-yellow-800 dark:text-yellow-200">Tidak ada jabatan aktif</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Pendidikan -->
                            @if($activeTab === 'pendidikan')
                                <div class="space-y-6">
                                    @php
                                        $ongoingPendidikan = $selectedKaryawan->pendidikan ? $selectedKaryawan->pendidikan->where('status', 'ongoing') : collect();
                                        $completedPendidikan = $selectedKaryawan->pendidikan ? $selectedKaryawan->pendidikan->whereIn('status', ['completed', null]) : collect();
                                    @endphp
                                    
                                    <!-- Pendidikan Sedang Berjalan -->
                                    @if($ongoingPendidikan->count() > 0)
                                        <div>
                                            <h4 class="text-base font-semibold text-yellow-800 dark:text-yellow-200 mb-3">📚 Pendidikan Sedang Berjalan</h4>
                                            <div class="space-y-3">
                                                @foreach ($ongoingPendidikan as $pend)
                                                    <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 p-4 rounded">
                                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                            <div>
                                                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Tingkat Pendidikan</p>
                                                                <p class="font-semibold text-yellow-900 dark:text-yellow-100">{{ $pend->educationLevel?->level_name ?? 'N/A' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Jurusan</p>
                                                                <p class="font-semibold text-yellow-900 dark:text-yellow-100">{{ $pend->jurusan ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Institusi</p>
                                                                <p class="font-semibold text-yellow-900 dark:text-yellow-100">{{ $pend->nama_institusi ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Tahun Mulai</p>
                                                                <p class="font-semibold text-yellow-900 dark:text-yellow-100">{{ $pend->tahun_mulai ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Status</p>
                                                                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full bg-yellow-200 text-yellow-900">
                                                                    Sedang Berjalan
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Pendidikan Selesai -->
                                    @if($completedPendidikan->count() > 0)
                                        <div>
                                            <h4 class="text-base font-semibold text-blue-800 dark:text-blue-200 mb-3">✅ Pendidikan Selesai</h4>
                                            <div class="space-y-3">
                                                @foreach ($completedPendidikan as $pend)
                                                    <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 rounded">
                                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                            <div>
                                                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Tingkat Pendidikan</p>
                                                                <p class="font-semibold text-blue-900 dark:text-blue-100">{{ $pend->educationLevel?->level_name ?? 'N/A' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Jurusan</p>
                                                                <p class="font-semibold text-blue-900 dark:text-blue-100">{{ $pend->jurusan ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Institusi</p>
                                                                <p class="font-semibold text-blue-900 dark:text-blue-100">{{ $pend->nama_institusi ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Tahun Mulai</p>
                                                                <p class="font-semibold text-blue-900 dark:text-blue-100">{{ $pend->tahun_mulai ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Tahun Selesai</p>
                                                                <p class="font-semibold text-blue-900 dark:text-blue-100">{{ $pend->tahun_selesai ?? '-' }}</p>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-medium text-blue-800 dark:text-blue-200">IPK</p>
                                                                <p class="font-semibold text-blue-900 dark:text-blue-100">{{ $pend->ipk ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if ($selectedKaryawan->pendidikan && $selectedKaryawan->pendidikan->count() === 0)
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data pendidikan
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Organisasi -->
                            @if($activeTab === 'organisasi')
                                <div class="space-y-6">
                                    @if ($selectedKaryawan->organisasi && $selectedKaryawan->organisasi->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($selectedKaryawan->organisasi as $org)
                                                <div class="bg-indigo-50 dark:bg-indigo-900 border-l-4 border-indigo-500 p-4 rounded">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Nama Organisasi</p>
                                                            <p class="font-semibold text-indigo-900 dark:text-indigo-100">{{ $org->organisasi ?? 'N/A' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Jabatan</p>
                                                            <p class="font-semibold text-indigo-900 dark:text-indigo-100">{{ $org->jabatan ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Level Organisasi</p>
                                                            <p class="font-semibold text-indigo-900 dark:text-indigo-100">{{ $org->level ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Status Organisasi</p>
                                                            <p class="font-semibold text-indigo-900 dark:text-indigo-100">
                                                                @if($org->status_organisasi)
                                                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                                                        @if(strtolower($org->status_organisasi) === 'aktif')
                                                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                                        @elseif(strtolower($org->status_organisasi) === 'nonaktif')
                                                                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                                        @else
                                                                            bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                                        @endif
                                                                    ">{{ $org->status_organisasi }}</span>
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Tanggal Mulai</p>
                                                            <p class="font-semibold text-indigo-900 dark:text-indigo-100">{{ $org->tgl_awal ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-indigo-800 dark:text-indigo-200">Tanggal Selesai</p>
                                                            <p class="font-semibold text-indigo-900 dark:text-indigo-100">{{ $org->tgl_akhir ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data organisasi
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Pekerjaan -->
                            @if($activeTab === 'pekerjaan')
                                <div class="space-y-6">
                                    @if ($selectedKaryawan->pekerjaan && $selectedKaryawan->pekerjaan->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($selectedKaryawan->pekerjaan as $kerja)
                                                <div class="bg-orange-50 dark:bg-orange-900 border-l-4 border-orange-500 p-4 rounded">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-orange-800 dark:text-orange-200">Nama Perusahaan</p>
                                                            <p class="font-semibold text-orange-900 dark:text-orange-100">{{ $kerja->nama_instansi ?? 'N/A' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-orange-800 dark:text-orange-200">Jabatan</p>
                                                            <p class="font-semibold text-orange-900 dark:text-orange-100">{{ $kerja->jabatan ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-orange-800 dark:text-orange-200">Tanggal Mulai</p>
                                                            <p class="font-semibold text-orange-900 dark:text-orange-100">{{ $kerja->tgl_awal ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-orange-800 dark:text-orange-200">Tanggal Selesai</p>
                                                            <p class="font-semibold text-orange-900 dark:text-orange-100">{{ $kerja->tgl_akhir ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-orange-800 dark:text-orange-200">Alasan Berhenti</p>
                                                            <p class="font-semibold text-orange-900 dark:text-orange-100">{{ $kerja->alasan_berhenti ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data pekerjaan sebelumnya
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Keluarga -->
                            @if($activeTab === 'keluarga')
                                <div class="space-y-6">
                                    @if ($selectedKaryawan->keluarga && $selectedKaryawan->keluarga->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($selectedKaryawan->keluarga as $fam)
                                                <div class="bg-pink-50 dark:bg-pink-900 border-l-4 border-pink-500 p-4 rounded">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-pink-800 dark:text-pink-200">Nama</p>
                                                            <p class="font-semibold text-pink-900 dark:text-pink-100">{{ $fam->nama_anggota ?? 'N/A' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-pink-800 dark:text-pink-200">Hubungan</p>
                                                            <p class="font-semibold text-pink-900 dark:text-pink-100">{{ $fam->hubungan ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-pink-800 dark:text-pink-200">Tanggal Lahir</p>
                                                            <p class="font-semibold text-pink-900 dark:text-pink-100">
                                                                @if($fam->tgl_lahir)
                                                                    {{ \Carbon\Carbon::parse($fam->tgl_lahir)->format('d M Y') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-pink-800 dark:text-pink-200">Status Hidup</p>
                                                            <p class="font-semibold text-pink-900 dark:text-pink-100">{{ $fam->status_hidup ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data keluarga
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Bahasa -->
                            @if($activeTab === 'bahasa')
                                <div class="space-y-6">
                                    @if ($selectedKaryawan->bahasa && $selectedKaryawan->bahasa->count() > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach ($selectedKaryawan->bahasa as $lang)
                                                <div class="bg-cyan-50 dark:bg-cyan-900 border border-cyan-200 dark:border-cyan-700 p-4 rounded-lg">
                                                    <p class="font-semibold text-cyan-900 dark:text-cyan-100">{{ $lang->nama_bahasa ?? 'N/A' }}</p>
                                                    <p class="text-sm text-cyan-800 dark:text-cyan-200 mt-2">
                                                        <span class="font-medium">Tingkat:</span> {{ $lang->level_bahasa ?? '-' }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data bahasa
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Sertifikasi -->
                            @if($activeTab === 'sertifikasi')
                                <div class="space-y-6">
                                    @if ($selectedKaryawan->sertifikasi && $selectedKaryawan->sertifikasi->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($selectedKaryawan->sertifikasi as $sert)
                                                <div class="bg-emerald-50 dark:bg-emerald-900 border-l-4 border-emerald-500 p-4 rounded">
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">Nama Sertifikasi</p>
                                                            <p class="font-semibold text-emerald-900 dark:text-emerald-100">{{ $sert->nama_sertifikasi ?? 'N/A' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">Nomor Sertifikat</p>
                                                            <p class="font-semibold text-emerald-900 dark:text-emerald-100">{{ $sert->nomor_sertifikat ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">Tanggal Terbit</p>
                                                            <p class="font-semibold text-emerald-900 dark:text-emerald-100">
                                                                @if($sert->tgl_terbit)
                                                                    {{ \Carbon\Carbon::parse($sert->tgl_terbit)->format('d M Y') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data sertifikasi
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Pelatihan -->
                            @if($activeTab === 'pelatihan')
                                <div class="space-y-6">
                                    @if ($selectedKaryawan->pelatihan && $selectedKaryawan->pelatihan->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($selectedKaryawan->pelatihan as $pel)
                                                <div class="bg-rose-50 dark:bg-rose-900 border-l-4 border-rose-500 p-4 rounded">
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-rose-800 dark:text-rose-200">Nama Pelatihan</p>
                                                            <p class="font-semibold text-rose-900 dark:text-rose-100">{{ $pel->nama_pelatihan ?? 'N/A' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-rose-800 dark:text-rose-200">Tanggal Mulai</p>
                                                            <p class="font-semibold text-rose-900 dark:text-rose-100">
                                                                @if($pel->tgl_mulai)
                                                                    {{ \Carbon\Carbon::parse($pel->tgl_mulai)->format('d M Y') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-rose-800 dark:text-rose-200">Tanggal Selesai</p>
                                                            <p class="font-semibold text-rose-900 dark:text-rose-100">
                                                                @if($pel->tgl_selesai)
                                                                    {{ \Carbon\Carbon::parse($pel->tgl_selesai)->format('d M Y') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data pelatihan
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Prestasi -->
                            @if($activeTab === 'prestasi')
                                <div class="space-y-6">
                                    @if ($selectedKaryawan->prestasi && $selectedKaryawan->prestasi->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($selectedKaryawan->prestasi as $prest)
                                                <div class="bg-amber-50 dark:bg-amber-900 border-l-4 border-amber-500 p-4 rounded">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-amber-800 dark:text-amber-200">Nama Prestasi</p>
                                                            <p class="font-semibold text-amber-900 dark:text-amber-100">{{ $prest->nama_prestasi ?? 'N/A' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-amber-800 dark:text-amber-200">Tingkat</p>
                                                            <p class="font-semibold text-amber-900 dark:text-amber-100">{{ $prest->tingkat ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-amber-800 dark:text-amber-200">Tanggal</p>
                                                            <p class="font-semibold text-amber-900 dark:text-amber-100">
                                                                @if($prest->tanggal)
                                                                    {{ \Carbon\Carbon::parse($prest->tanggal)->format('d M Y') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data prestasi
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Dokumen -->
                            @if($activeTab === 'dokumen')
                                <div class="space-y-6">
                                    @if ($selectedKaryawan->dokumen && $selectedKaryawan->dokumen->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($selectedKaryawan->dokumen as $doc)
                                                <div class="bg-sky-50 dark:bg-sky-900 border-l-4 border-sky-500 p-4 rounded">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-sky-800 dark:text-sky-200">Nama Dokumen</p>
                                                            <p class="font-semibold text-sky-900 dark:text-sky-100">{{ $doc->nama_dokumen ?? 'N/A' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-sky-800 dark:text-sky-200">Jenis Dokumen</p>
                                                            <p class="font-semibold text-sky-900 dark:text-sky-100">{{ ucfirst(str_replace('_', ' ', $doc->jenis_dokumen ?? '-')) }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-sky-800 dark:text-sky-200">Status</p>
                                                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                                                @if($doc->status_dokumen === 'valid')
                                                                    bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                                @elseif($doc->status_dokumen === 'invalid')
                                                                    bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                                @else
                                                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                                @endif
                                                            ">{{ ucfirst($doc->status_dokumen ?? 'waiting') }}</span>
                                                        </div>
                                                        @if($doc->document_path)
                                                            <div class="md:col-span-2">
                                                                <p class="text-sm font-medium text-sky-800 dark:text-sky-200 mb-2">File</p>
                                                                @php
                                                                    $filePath = $doc->document_path;
                                                                    $fileUrl = asset('storage/' . $filePath);
                                                                @endphp
                                                                <a href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded font-semibold transition-colors">
                                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-4-2m4 2l4-2"></path>
                                                                    </svg>
                                                                    Lihat Dokumen
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="md:col-span-2">
                                                                <p class="text-sm text-sky-600 dark:text-sky-400 italic">File tidak tersedia</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data dokumen
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Tab: Bank -->
                            @if($activeTab === 'bank')
                                <div class="space-y-6">
                                    @if ($selectedKaryawan->bankaccount && $selectedKaryawan->bankaccount->count() > 0)
                                        <div class="space-y-3">
                                            @foreach ($selectedKaryawan->bankaccount as $bank)
                                                <div class="bg-green-50 dark:bg-green-900 border-l-4 border-green-500 p-4 rounded">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                        <div>
                                                            <p class="text-sm font-medium text-green-800 dark:text-green-200">Nama Bank</p>
                                                            <p class="font-semibold text-green-900 dark:text-green-100">{{ $bank->nama_bank ?? 'N/A' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-green-800 dark:text-green-200">No. Rekening</p>
                                                            <p class="font-semibold text-green-900 dark:text-green-100">{{ $bank->nomor_rekening ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-green-800 dark:text-green-200">Atas Nama</p>
                                                            <p class="font-semibold text-green-900 dark:text-green-100">{{ $bank->nama_pemilik ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-4 rounded-lg text-center text-gray-600 dark:text-gray-400">
                                            Tidak ada data rekening bank
                                        </div>
                                    @endif
                                </div>
                            @endif

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
