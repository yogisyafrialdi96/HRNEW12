<div class="space-y-6">
    <!-- Welcome & Quick Stats -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ $userData['name'] }}</h1>
                <p class="text-blue-100 text-sm mt-1">{{ implode(', ', $userData['roles']) ?: 'No Role' }}</p>
            </div>
            <div class="text-5xl opacity-20">👤</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Summary Card -->
        <div class="lg:col-span-1 space-y-4">
            <!-- User Card -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr($userData['name'], 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 truncate">{{ $userData['name'] }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $userData['email'] }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Joined:</span>
                        <span class="font-medium text-gray-900">{{ $userData['created_at']->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Karyawan Summary (if exists) -->
            @if($karyawanData)
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="font-semibold text-gray-900 mb-3 text-sm">Karyawan Info</h3>
                    
                    <div class="space-y-2 text-sm">
                        <div>
                            <p class="text-gray-600">NIP</p>
                            <p class="font-medium text-gray-900">{{ $karyawanData['nip'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Jabatan</p>
                            <p class="font-medium text-gray-900 text-xs">{{ $karyawanData['jabatan'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Unit</p>
                            <p class="font-medium text-gray-900 text-xs">{{ $karyawanData['unit'] ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Status</p>
                            <div class="mt-1">
                                @php
                                    $statusColors = [
                                        'Aktif' => 'bg-green-100 text-green-700',
                                        'Tidak Aktif' => 'bg-red-100 text-red-700',
                                        'Cuti' => 'bg-yellow-100 text-yellow-700',
                                    ];
                                    $statusClass = $statusColors[$karyawanData['status']] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $statusClass }}">
                                    {{ $karyawanData['status'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t">
                        @if(auth()->user()->hasPermissionTo('karyawan.view_list'))
                            {{-- Admin/Manager can edit via admin route --}}
                            <a href="{{ route('karyawan.edit', ['karyawan' => $karyawanData['id'], 'tab' => 'profile']) }}" 
                               class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                        @else
                            {{-- Staff can view via staff route --}}
                            <a href="{{ route('karyawan.profile', ['karyawan' => $karyawanData['id'], 'tab' => 'profile']) }}" 
                               class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Links -->
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-semibold text-gray-900 mb-3 text-sm">Shortcuts</h3>
                <div class="space-y-2">
                    @if($karyawanData)
                        @if(auth()->user()->hasPermissionTo('karyawan.view_list'))
                            <a href="{{ route('karyawan.edit', ['karyawan' => $karyawanData['id'], 'tab' => 'profile']) }}" 
                               class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                My Profile
                            </a>
                        @else
                            <a href="{{ route('karyawan.profile', ['karyawan' => $karyawanData['id'], 'tab' => 'profile']) }}" 
                               class="flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50 text-sm text-gray-700 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                My Profile
                            </a>
                        @endif
                    @endif

                    @can('karyawan.view_list')
                        <a href="{{ route('karyawan.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded hover:bg-green-50 text-sm text-gray-700 hover:text-green-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a3 3 0 003-3v-2a3 3 0 00-3-3H6a3 3 0 00-3 3v2a3 3 0 003 3z"></path>
                            </svg>
                            Employees
                        </a>
                    @endcan

                    @can('roles.view')
                        <a href="{{ route('roles.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded hover:bg-purple-50 text-sm text-gray-700 hover:text-purple-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            Roles
                        </a>
                    @endcan

                    @can('permissions.view')
                        <a href="{{ route('permissions.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded hover:bg-orange-50 text-sm text-gray-700 hover:text-orange-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Permissions
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Karyawan Details (if exists) -->
        @if($karyawanData)
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h2 class="font-semibold text-gray-900">Karyawan Details</h2>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            <!-- Basic Info -->
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Full Name</p>
                                <p class="font-medium text-gray-900">{{ $karyawanData['full_name'] }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Gender</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $karyawanData['gender'] ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Type</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $karyawanData['jenis_karyawan'] ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Phone</p>
                                <p class="font-medium text-gray-900">{{ $karyawanData['hp'] ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Religion</p>
                                <p class="font-medium text-gray-900">{{ $karyawanData['agama'] ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Birth Date</p>
                                <p class="font-medium text-gray-900">
                                    @if($karyawanData['tanggal_lahir'])
                                        {{ \Carbon\Carbon::parse($karyawanData['tanggal_lahir'])->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Birth Place</p>
                                <p class="font-medium text-gray-900 text-sm">{{ $karyawanData['tempat_lahir'] ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Marital Status</p>
                                <p class="font-medium text-gray-900 capitalize">{{ $karyawanData['status_kawin'] ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Join Date</p>
                                <p class="font-medium text-gray-900">
                                    @if($karyawanData['tgl_masuk'])
                                        {{ \Carbon\Carbon::parse($karyawanData['tgl_masuk'])->format('M d, Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Years</p>
                                <p class="font-medium text-gray-900">
                                    @if($karyawanData['tgl_masuk'])
                                        {{ \Carbon\Carbon::parse($karyawanData['tgl_masuk'])->diffInYears(\Carbon\Carbon::now()) }} yrs
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Email</p>
                                <a href="mailto:{{ $karyawanData['email'] }}" class="font-medium text-blue-600 hover:underline text-sm break-all">
                                    {{ $karyawanData['email'] ?? '-' }}
                                </a>
                            </div>
                        </div>

                        @if($karyawanData['alamat'])
                            <div class="mt-6 pt-6 border-t">
                                <p class="text-xs font-semibold text-gray-600 uppercase mb-2">Address</p>
                                <p class="text-sm text-gray-900">{{ $karyawanData['alamat'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">No Employee Data</p>
                    <p class="text-gray-500 text-sm mt-1">Contact admin to link your account with employee data</p>
                </div>
            </div>
        @endif
    </div>
</div>


