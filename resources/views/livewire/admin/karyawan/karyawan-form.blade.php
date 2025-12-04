<div>
    <form wire:submit.prevent="save" class="space-y-5" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nama Lengkap -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input wire:model.live="full_name" type="text" placeholder="Enter Nama Lengkap"
                    class="w-full px-4 py-3 bg-white border rounded-lg text-sm focus:ring-2 transition-all @error('full_name') border-red-500 focus:border-red-500 focus:ring-red-500/20 @else border-gray-200 focus:border-blue-500 focus:ring-blue-500/20 @enderror">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Panggilan -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">
                        Panggilan
                    </label>
                    <input wire:model.live="panggilan" type="text" placeholder="Enter Panggilan"
                        class="w-full px-4 py-3 bg-white border rounded-lg text-sm focus:ring-2 transition-all @error('panggilan') border-red-500 focus:border-red-500 focus:ring-red-500/20 @else border-gray-200 focus:border-blue-500 focus:ring-blue-500/20 @enderror">
                    @error('panggilan')
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
                        Inisial <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="inisial" type="text" placeholder="Enter Inisial"
                        {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
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
            </div>
        </div>

        @can('karyawan.view_list')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <input wire:model="password_confirmation" type="password" placeholder="Enter Password Confirmation"
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
        </div>
        @endcan

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- NIP -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    NIP
                </label>
                <input wire:model="nip" type="text" placeholder="Enter Nomor Induk Pegawai"
                    {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
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

            <!-- Jenis Karyawan -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Karyawan <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model.live="jenis_karyawan"
                        {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
                        <option value="" class="text-gray-400">Pilih jenis</option>
                        <option value="Guru">Guru</option>
                        <option value="Pegawai">Pegawai</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('jenis_karyawan')
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

            @if ($jenis_karyawan === 'Guru'  )
                <!-- Mata Pelajaran -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Mata Pelajaran</label>
                    <div class="relative">
                        <select wire:model.live="mapel_id"
                            {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none disabled:bg-gray-100 disabled:cursor-not-allowed {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($masterMapel as $mapel)
                                <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    @error('mapel_id')
                        <p class="text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endif

            <!-- Status Karyawan -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Karyawan <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model="statuskaryawan_id"
                        {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
                        <option value="" class="text-gray-400">Pilih Status</option>
                        @foreach ($statusKaryawan as $status)
                            <option value="{{ $status->id }}">{{ $status->nama_status }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('statuskaryawan_id')
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

            <!-- Nested Grid for Golongan & Status Kawin -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Golongan -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Golongan <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model="golongan_id"
                            {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
                            <option value="" class="text-gray-400">Pilih Golongan</option>
                            @foreach ($golongan as $gol)
                                <option value="{{ $gol->id }}">{{ $gol->nama_golongan }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                    @error('golongan_id')
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
                
                <!-- Status Kawin -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kawin <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model="statuskawin_id"
                            {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
                            <option value="" class="text-gray-400">Pilih Status</option>
                            @foreach ($statusKawin as $kawin)
                                <option value="{{ $kawin->id }}">{{ $kawin->nama }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                    @error('statuskawin_id')
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

        
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    NPWP <span class="text-red-500">*</span>
                </label>
                <input wire:model.live="npwp" type="text" placeholder="Enter NPWP"
                    class="w-full px-4 py-3 bg-white border rounded-lg text-sm focus:ring-2 transition-all @error('npwp') border-red-500 focus:border-red-500 focus:ring-red-500/20 @else border-gray-200 focus:border-blue-500 focus:ring-blue-500/20 @enderror ">
                @error('npwp')
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

            <!-- Tanggal Efektif -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Tanggal Efektif <span class="text-red-500">*</span>
                </label>
                <input wire:model="tgl_masuk" type="date" placeholder="Enter Tanggal Masuk"
                    {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
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

            <!-- Tanggal karyawan Tetap -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Tanggal Tetap
                </label>
                <input wire:model="tgl_karyawan_tetap" type="date" placeholder="Enter Tanggal Tetap"
                    {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
                @error('tgl_karyawan_tetap')
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

            <!-- Tanggal karyawan Berhenti -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Tanggal Berhenti
                </label>
                <input wire:model="tgl_berhenti" type="date" placeholder="Enter Tanggal Berhenti"
                    {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'disabled' : '' }}
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all {{ !auth()->user()->hasPermissionTo('karyawan.view_list') ? 'bg-gray-100 cursor-not-allowed opacity-60' : '' }}">
                @error('tgl_berhenti')
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- NIK -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    NIK <span class="text-red-500">*</span>
                </label>
                <input wire:model="nik" type="text" placeholder="Enter Nomor Induk Kependudukan"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                @error('nik')
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

            <!-- NO KK -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    No KK <span class="text-red-500">*</span>
                </label>
                <input wire:model="nkk" type="text" placeholder="Enter Nomor Kartu Keluarga"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                @error('nkk')
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Hp -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    HP <span class="text-red-500">*</span>
                </label>
                <input wire:model="hp" type="text" placeholder="+62 999-9999-9999"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                    x-data="{
                        formatPhone(value) {
                            // Remove all non-digits
                            const digits = value.replace(/\D/g, '');
                    
                            // Ensure it starts with 62 if digits are provided
                            let formattedDigits = digits;
                            if (digits.length > 0 && !digits.startsWith('62')) {
                                if (digits.startsWith('0')) {
                                    formattedDigits = '62' + digits.substring(1);
                                } else {
                                    formattedDigits = '62' + digits;
                                }
                            }
                    
                            // Apply the mask pattern +62 999-9999-9999
                            let formatted = '+62';
                            if (formattedDigits.length > 2) {
                                formatted += ' ' + formattedDigits.substring(2, 5);
                            }
                            if (formattedDigits.length > 5) {
                                formatted += '-' + formattedDigits.substring(5, 9);
                            }
                            if (formattedDigits.length > 9) {
                                formatted += '-' + formattedDigits.substring(9, 13);
                            }
                    
                            return formatted;
                        }
                    }" x-init="$nextTick(() => {
                        if ($el.value) {
                            $el.value = formatPhone($el.value);
                            $wire.set('hp', $el.value);
                        }
                    })"
                    x-on:input="$event.target.value = formatPhone($event.target.value); $wire.set('hp', $event.target.value)"
                    maxlength="17">
                @error('hp')
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

            <!-- Whatsapp -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Whatsapp <span class="text-red-500">*</span>
                </label>
                <input wire:model="whatsapp" type="text" placeholder="+62 999-9999-9999"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                    x-data="{
                        formatPhone(value) {
                            // Remove all non-digits
                            const digits = value.replace(/\D/g, '');
                    
                            // Ensure it starts with 62 if digits are provided
                            let formattedDigits = digits;
                            if (digits.length > 0 && !digits.startsWith('62')) {
                                if (digits.startsWith('0')) {
                                    formattedDigits = '62' + digits.substring(1);
                                } else {
                                    formattedDigits = '62' + digits;
                                }
                            }
                    
                            // Apply the mask pattern +62 999-9999-9999
                            let formatted = '+62';
                            if (formattedDigits.length > 2) {
                                formatted += ' ' + formattedDigits.substring(2, 5);
                            }
                            if (formattedDigits.length > 5) {
                                formatted += '-' + formattedDigits.substring(5, 9);
                            }
                            if (formattedDigits.length > 9) {
                                formatted += '-' + formattedDigits.substring(9, 13);
                            }
                    
                            return formatted;
                        }
                    }" x-init="$nextTick(() => {
                        if ($el.value) {
                            $el.value = formatPhone($el.value);
                            $wire.set('whatsapp', $el.value);
                        }
                    })"
                    x-on:input="$event.target.value = formatPhone($event.target.value); $wire.set('whatsapp', $event.target.value)"
                    maxlength="17">
                @error('whatsapp')
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

            <!-- Jenis Kelamin -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model="gender"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                        <option value="" class="text-gray-400">Pilih jenis Kelamin</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('gender')
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

            <!-- Tempat Lahir -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Tempat Lahir <span class="text-red-500">*</span>
                </label>
                <input wire:model="tempat_lahir" type="text" placeholder="Enter Tempat Lahir"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                @error('tempat_lahir')
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

            <!-- Tanggal Lahir -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Tanggal Lahir <span class="text-red-500">*</span>
                </label>
                <input wire:model="tanggal_lahir" type="date" placeholder="Enter Tanggal Lahir"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                @error('tanggal_lahir')
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

            <!-- Agama -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Agama <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model="agama"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                        <option value="" class="text-gray-400">Pilih Agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Budha">Budha</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Protestan">Protestan</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('agama')
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

            <!-- Status Kawin -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Kawin <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model="status_kawin"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                        <option value="" class="text-gray-400">Pilih Status Kawin</option>
                        <option value="lajang">Lajang</option>
                        <option value="menikah">Menikah</option>
                        <option value="cerai">Cerai</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('status_kawin')
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

            <!-- Pendidikan Akhir -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Akhir <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model="pndk_akhir"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                        <option value="" class="text-gray-400">Pilih Pendidikan</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="D1">D1</option>
                        <option value="D2">D2</option>
                        <option value="D3">D3</option>
                        <option value="D4">D4</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('pndk_akhir')
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

            <!-- Gelar Depan Belakang -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Gelar Depan -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">
                        Gelar Depan
                    </label>
                    <input wire:model="gelar_depan" type="text" placeholder="Enter Gelar Depan"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('gelar_depan')
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
                <!-- Gelar Belakang -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">
                        Gelar Belakang
                    </label>
                    <input wire:model="gelar_belakang" type="text" placeholder="Enter Gelar Belakang"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('gelar_belakang')
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

            <!-- Golongan Darah -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah </label>
                <div class="relative">
                    <select wire:model="blood_type"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                        <option value="" class="text-gray-400">Pilih Golongan Darah</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('blood_type')
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

            <!-- Emergency Contact Name -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Nama Kontak Darurat
                </label>
                <input wire:model="emergency_contact_name" type="text" placeholder="Enter Nama Kontak Darurat"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                @error('emergency_contact_name')
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

            <!-- Emergency Contact HP -->
            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Kontak Darurat
                </label>
                <input wire:model="emergency_contact_phone" type="text" placeholder="+62 999-9999-9999"
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all"
                    x-data="{
                        formatPhone(value) {
                            // Remove all non-digits
                            const digits = value.replace(/\D/g, '');
                    
                            // Ensure it starts with 62 if digits are provided
                            let formattedDigits = digits;
                            if (digits.length > 0 && !digits.startsWith('62')) {
                                if (digits.startsWith('0')) {
                                    formattedDigits = '62' + digits.substring(1);
                                } else {
                                    formattedDigits = '62' + digits;
                                }
                            }
                    
                            // Apply the mask pattern +62 999-9999-9999
                            let formatted = '+62';
                            if (formattedDigits.length > 2) {
                                formatted += ' ' + formattedDigits.substring(2, 5);
                            }
                            if (formattedDigits.length > 5) {
                                formatted += '-' + formattedDigits.substring(5, 9);
                            }
                            if (formattedDigits.length > 9) {
                                formatted += '-' + formattedDigits.substring(9, 13);
                            }
                    
                            return formatted;
                        }
                    }" x-init="$nextTick(() => {
                        if ($el.value) {
                            $el.value = formatPhone($el.value);
                            $wire.set('emergency_contact_phone', $el.value);
                        }
                    })"
                    x-on:input="$event.target.value = formatPhone($event.target.value); $wire.set('emergency_contact_phone', $event.target.value)"
                    maxlength="17">
                @error('emergency_contact_phone')
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

        <!-- Alamat KTP -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-3">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">
                        Alamat KTP <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="alamat_ktp" type="text" placeholder="Enter Alamat KTP"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('alamat_ktp')
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
            <div class="col-span-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">
                            RT <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="rt_ktp" type="text" placeholder="Enter RT"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('rt_ktp')
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
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">
                            RW <span class="text-red-500">*</span>
                        </label>
                        <input wire:model="rw_ktp" type="text" placeholder="Enter RW"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('rw_ktp')
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
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Provinisi -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model.live="provktp_id"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                        <option value="" class="text-gray-400">Pilih Provinsi</option>
                        @foreach ($provinsiList as $provinsi)
                            <option value="{{ $provinsi->id }}">{{ $provinsi->nama }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('provktp_id')
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
            <!-- Kabupaten -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model.live="kabktp_id"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                        <option value="" class="text-gray-400">Pilih Kabupaten</option>
                        @foreach ($kabupatenList as $kabupaten)
                            <option value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('kabktp_id')
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
            <!-- Kecamatan -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model.live="kecktp_id"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                        <option value="" class="text-gray-400">Pilih Kecamatan</option>
                        @foreach ($kecamatanList as $kecamatan)
                            <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('kecktp_id')
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
            <!-- Desa -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Desa <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <select wire:model.live="desaktp_id"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                        <option value="" class="text-gray-400">Pilih Desa</option>
                        @foreach ($desaList as $desa)
                            <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </div>
                @error('desaktp_id')
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

        <div class="flex items-center mb-4">
            <input id="domisili_sama_ktp" wire:model.live="domisili_sama_ktp" type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="domisili_sama_ktp" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Alamat
                Domisili sama dengan Alamat KTP</label>
            @error('domisili_sama_ktp')
                <p class="text-xs text-red-500 flex items-center gap-1 ml-4">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Alamat Domisili -->
        @if (!$domisili_sama_ktp)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="col-span-3">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">
                            Alamat Domisili <span class="text-red-500">*</span>
                        </label>
                        <input wire:model.live="alamat_dom" type="text" placeholder="Enter Alamat Domisili"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        @error('alamat_dom')
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
                <div class="col-span-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">
                                RT <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="rt_dom" type="text" placeholder="Enter RT"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            @error('rt_dom')
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
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">
                                RW <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="rw_dom" type="text" placeholder="Enter RW"
                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            @error('rw_dom')
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
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Provinisi -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.live="provdom_id"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                            <option value="" class="text-gray-400">Pilih Provinsi</option>
                            @foreach ($provinsiList as $provinsi)
                                <option value="{{ $provinsi->id }}">{{ $provinsi->nama }}</option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                    @error('provdom_id')
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
                <!-- Kabupaten -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kabupaten <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.live="kabdom_id"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                            <option value="" class="text-gray-400">Pilih Kabupaten</option>
                            @foreach ($kabDomisiliList as $kabupaten)
                                <option value="{{ $kabupaten->id }}">{{ $kabupaten->nama }}</option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                    @error('kabdom_id')
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
                <!-- Kecamatan -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.live="kecdom_id"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                            <option value="" class="text-gray-400">Pilih Kecamatan</option>
                            @foreach ($kecDomisiliList as $kecamatan)
                                <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama }}</option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                    @error('kecdom_id')
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
                <!-- Desa -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Desa <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.live="desdom_id"
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                            <option value="" class="text-gray-400">Pilih Desa</option>
                            @foreach ($desaDomisiliList as $desa)
                                <option value="{{ $desa->id }}">{{ $desa->nama }}</option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                    @error('desdom_id')
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
        @endif

        <!-- Photo Upload Field -->
        <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">
                Foto <span class="text-red-500">*</span>
            </label>

            <div class="relative">
                <input wire:model="foto" type="file" accept="image/*"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    id="photoUpload">

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                    @if ($foto)
                        <div class="space-y-3">
                            <div class="mx-auto w-24 h-24 rounded-lg overflow-hidden border-2 border-gray-200">
                                @if (is_string($foto))
                                    <img src="{{ asset('storage/' . $foto) }}" alt="Preview" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ $foto->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                @if (is_string($foto))
                                    <p class="text-sm font-medium text-gray-700">{{ basename($foto) }}</p>
                                    <p class="text-xs text-gray-500">Foto tersimpan</p>
                                @else
                                    <p class="text-sm font-medium text-gray-700">{{ $foto->getClientOriginalName() }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($foto->getSize() / 1024, 1) }} KB</p>
                                @endif
                            </div>
                            <!-- PERBAIKAN: Gunakan method khusus -->
                            <button type="button" wire:click="removeFoto" 
                                class="text-xs text-red-600 hover:text-red-700 font-medium">
                                Hapus Foto
                            </button>
                        </div>
                    @else
                        <div class="space-y-3">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Upload Foto</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB</p>
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
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
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- TTD Upload Field -->
        <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">
                Tanda Tangan <span class="text-red-500">*</span>
            </label>

            <div class="relative">
                <input wire:model="ttd" type="file" accept="image/*"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    id="ttdUpload">

                <!-- Upload Area -->
                <div
                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                    @if ($ttd)
                        <!-- Preview Image -->
                        <div class="space-y-3">
                            <div
                                class="mx-auto w-24 h-24 rounded-lg overflow-hidden border-2 border-gray-200">
                                @if (is_string($ttd))
                                    <!-- Existing image from database -->
                                    <img src="{{ asset('storage/' . $ttd) }}" alt="Preview"
                                        class="w-full h-full object-cover">
                                @else
                                    <!-- New uploaded image -->
                                    <img src="{{ $ttd->temporaryUrl() }}" alt="Preview"
                                        class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                @if (is_string($ttd))
                                    <!-- Existing image info -->
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ basename($ttd) }}</p>
                                    <p class="text-xs text-gray-500">ttd tersimpan</p>
                                @else
                                    <!-- New uploaded image info -->
                                    <p class="text-sm font-medium text-gray-700">
                                        {{ $ttd->getClientOriginalName() }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ number_format($ttd->getSize() / 1024, 1) }} KB</p>
                                @endif
                            </div>
                            <button type="button" wire:click="$set('ttd', null)"
                                class="text-xs text-red-600 hover:text-red-700 font-medium">
                                Hapus ttd
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
                                <p class="text-sm font-medium text-gray-700">Upload Tanda Tangan</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB</p>
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
                                Pilih Tanda Tangan
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @error('ttd')
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
        <div class="py-4 ">
            <div class="flex gap-3">
                <button type="submit" wire:click="save"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <span wire:loading.remove wire:target="save">
                        Edit Karyawan
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 2v4m0 12v4m8-8h-4M6 12H2m15.364-6.364l-2.828 2.828M9.464 16.536l-2.828 2.828m9.192-9.192l-2.828 2.828M6.464 6.464L3.636 3.636">
                            </path>
                        </svg>
                        Updating...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
