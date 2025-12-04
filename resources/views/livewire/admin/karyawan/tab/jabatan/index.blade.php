<div>

    <div class="flex flex-column sm:flex-row flex-wrap sm:space-y-0 items-center justify-between">
        <div>
            <flux:heading size="lg">Riwayat Jabatan</flux:heading>
            <flux:text>This Page Show List of Position History</flux:text>
        </div>

        <div>
            @can('karyawan_jabatan.create')
                <button wire:click="create"
                    class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-1 rounded-lg flex items-center justify-center transition duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    <span>Create</span>
                </button>
            @else
                <div class="text-sm text-gray-500 italic">No permission to create Jabatan</div>
            @endcan
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
                        placeholder="Search Position...">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jabatan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Department/Unit
                        </th>
                        
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Periode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('hub_kerja')">
                            <div class="flex items-center gap-2">
                                <span>Hubungan Kerja</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'hub_kerja')
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
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('is_active')">
                            <div class="flex items-center justify-center gap-2">
                                <span>Status</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'is_active')
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
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jabatans as $jabatan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $jabatans->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $jabatan->jabatan->nama_jabatan ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div>{{ $jabatan->department->department ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $jabatan->unit->unit ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                <div>{{ $jabatan->tgl_mulai ? \Carbon\Carbon::parse($jabatan->tgl_mulai)->format('d M Y') : '-' }}</div>
                                @if($jabatan->tgl_selesai)
                                    <div class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($jabatan->tgl_selesai)->format('d M Y') }}</div>
                                @else
                                    <div class="text-xs text-blue-500">Sekarang</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($jabatan->hub_kerja === 'Promosi') bg-green-100 text-green-800
                                    @elseif($jabatan->hub_kerja === 'Mutasi') bg-blue-100 text-blue-800
                                    @elseif($jabatan->hub_kerja === 'Demosi') bg-red-100 text-red-800
                                    @elseif($jabatan->hub_kerja === 'Rotasi') bg-yellow-100 text-yellow-800
                                    @elseif($jabatan->hub_kerja === 'PJS') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $jabatan->hub_kerja }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $jabatan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $jabatan->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <button wire:click="showDetail({{ $jabatan->id }})"
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
                                    @can('karyawan_jabatan.edit')
                                    <button wire:click="edit({{ $jabatan->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-50 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    @endcan
                                    @can('karyawan_jabatan.delete')
                                    <button wire:click="confirmDelete({{ $jabatan->id }})"
                                        class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">No position history found</p>
                                    <p class="text-sm">Get started by creating a new position record.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-3 px-4 text-xs">
                {{ $jabatans->links() }}
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
                    class="relative w-full max-w-3xl mx-auto my-auto">

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <!-- Header -->
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $isEdit ? 'Edit Riwayat Jabatan' : 'Tambah Riwayat Jabatan' }}
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
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Department -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select wire:model.live="department_id"
                                                class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                                <option value="">Pilih Department</option>
                                                @foreach($masterDepartment as $dept)
                                                    <option value="{{ $dept->id }}">{{ $dept->department }}</option>
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
                                                @foreach($masterUnit as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
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
                                                @foreach($masterJabatan as $jab)
                                                    <option value="{{ $jab->id }}">{{ $jab->nama_jabatan }}</option>
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
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Hubungan Kerja -->
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-sm font-medium text-gray-700">Hubungan Kerja <span class="text-red-500">*</span></label>
                                        <div class="flex gap-3 flex-wrap">
                                            @foreach(['Mutasi', 'Promosi', 'Demosi', 'Rotasi', 'Default', 'PJS'] as $hub)
                                                <label class="flex items-center cursor-pointer">
                                                    <input wire:model.live="hub_kerja" type="radio" value="{{ $hub }}" class="sr-only peer">
                                                    <div class="w-4 h-4 border-2 border-gray-300 rounded-full 
                                                        @if($hub === 'Promosi') peer-checked:border-green-500 peer-checked:bg-green-50
                                                        @elseif($hub === 'Mutasi') peer-checked:border-blue-500 peer-checked:bg-blue-50
                                                        @elseif($hub === 'Demosi') peer-checked:border-red-500 peer-checked:bg-red-50
                                                        @elseif($hub === 'Rotasi') peer-checked:border-yellow-500 peer-checked:bg-yellow-50
                                                        @elseif($hub === 'PJS') peer-checked:border-purple-500 peer-checked:bg-purple-50
                                                        @else peer-checked:border-gray-500 peer-checked:bg-gray-50
                                                        @endif relative">
                                                        <div class="absolute inset-0.5 
                                                            @if($hub === 'Promosi') bg-green-500
                                                            @elseif($hub === 'Mutasi') bg-blue-500
                                                            @elseif($hub === 'Demosi') bg-red-500
                                                            @elseif($hub === 'Rotasi') bg-yellow-500
                                                            @elseif($hub === 'PJS') bg-purple-500
                                                            @else bg-gray-500
                                                            @endif rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-700">{{ $hub }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('hub_kerja')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Mulai -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                                        <input wire:model.live="tgl_mulai" type="date"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tgl_mulai')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Selesai -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                        <input wire:model.live="tgl_selesai" type="date"
                                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        @error('tgl_selesai')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    <!-- Is Active -->
                                    <div class="space-y-2 md:col-span-2">
                                        <label class="text-sm font-medium text-gray-700">Status Aktif <span class="text-red-500">*</span></label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center cursor-pointer">
                                                <input wire:model.live="is_active" type="radio" value="1" class="sr-only peer">
                                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-50 relative">
                                                    <div class="absolute inset-0.5 bg-green-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input wire:model.live="is_active" type="radio" value="0" class="sr-only peer">
                                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-red-500 peer-checked:bg-red-50 relative">
                                                    <div class="absolute inset-0.5 bg-red-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-700">Nonaktif</span>
                                            </label>
                                        </div>
                                        @error('is_active')
                                            <p class="text-xs text-red-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Keterangan -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Keterangan</label>
                                    <textarea wire:model.live="keterangan" rows="3" placeholder="Masukkan keterangan..."
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                                    @error('keterangan')
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
                                        {{ $isEdit ? 'Update Jabatan' : 'Simpan Jabatan' }}
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Riwayat Jabatan</h2>
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
                        @if (!empty($selectedJabatan))
                            <!-- Position Header -->
                            <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ $selectedJabatan->jabatan->nama_jabatan ?? 'N/A' }}
                                            </h3>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($selectedJabatan->hub_kerja === 'Promosi') bg-green-100 text-green-800
                                                @elseif($selectedJabatan->hub_kerja === 'Mutasi') bg-blue-100 text-blue-800
                                                @elseif($selectedJabatan->hub_kerja === 'Demosi') bg-red-100 text-red-800
                                                @elseif($selectedJabatan->hub_kerja === 'Rotasi') bg-yellow-100 text-yellow-800
                                                @elseif($selectedJabatan->hub_kerja === 'PJS') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $selectedJabatan->hub_kerja }}
                                            </span>
                                            @if($selectedJabatan->is_active)
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Aktif
                                                </span>
                                            @endif
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-blue-600 dark:text-blue-400 font-medium">
                                                {{ $selectedJabatan->department->nama_department ?? 'N/A' }} - {{ $selectedJabatan->unit->nama_unit ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Position Information -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Jabatan</h4>
                                    </div>
                                    <div class="p-4 space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $selectedJabatan->jabatan->nama_jabatan ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedJabatan->department->nama_department ?? 'N/A' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $selectedJabatan->unit->nama_unit ?? 'N/A' }}</dd>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Information -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Status & Hubungan Kerja</h4>
                                    </div>
                                    <div class="p-4 space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hubungan Kerja</dt>
                                            <dd class="mt-1">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($selectedJabatan->hub_kerja === 'Promosi') bg-green-100 text-green-800
                                                    @elseif($selectedJabatan->hub_kerja === 'Mutasi') bg-blue-100 text-blue-800
                                                    @elseif($selectedJabatan->hub_kerja === 'Demosi') bg-red-100 text-red-800
                                                    @elseif($selectedJabatan->hub_kerja === 'Rotasi') bg-yellow-100 text-yellow-800
                                                    @elseif($selectedJabatan->hub_kerja === 'PJS') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $selectedJabatan->hub_kerja }}
                                                </span>
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                            <dd class="mt-1">
                                                @if($selectedJabatan->is_active)
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
                            </div>

                            <!-- Period Information -->
                            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <h4 class="font-semibold text-gray-900 dark:text-white">Periode Jabatan</h4>
                                </div>
                                <div class="p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Mulai</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $selectedJabatan->tgl_mulai ? \Carbon\Carbon::parse($selectedJabatan->tgl_mulai)->format('d F Y') : 'N/A' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Selesai</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                @if($selectedJabatan->tgl_selesai)
                                                    {{ \Carbon\Carbon::parse($selectedJabatan->tgl_selesai)->format('d F Y') }}
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                                        Masih Berlangsung
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>
                                    </div>
                                    @if($selectedJabatan->tgl_mulai)
                                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                                <span class="font-medium">Durasi:</span>
                                                @if($selectedJabatan->tgl_selesai)
                                                    {{ \Carbon\Carbon::parse($selectedJabatan->tgl_mulai)->diffInDays(\Carbon\Carbon::parse($selectedJabatan->tgl_selesai)) }} hari
                                                    ({{ \Carbon\Carbon::parse($selectedJabatan->tgl_mulai)->diffForHumans(\Carbon\Carbon::parse($selectedJabatan->tgl_selesai), true) }})
                                                @else
                                                    {{ \Carbon\Carbon::parse($selectedJabatan->tgl_mulai)->diffForHumans(null, true) }}
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Notes -->
                            @if($selectedJabatan->keterangan)
                                <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Keterangan</h4>
                                    </div>
                                    <div class="p-4">
                                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                            {{ $selectedJabatan->keterangan }}
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
                                                {{ $selectedJabatan->created_at ? \Carbon\Carbon::parse($selectedJabatan->created_at)->format('d F Y, H:i') : 'N/A' }}
                                            </dd>
                                        </div>
                                        @if($selectedJabatan->created_by)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                                <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                    {{ $selectedJabatan->creator->name ?? $selectedJabatan->created_by }}
                                                </dd>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedJabatan->updated_at ? \Carbon\Carbon::parse($selectedJabatan->updated_at)->format('d F Y, H:i') : 'N/A' }}
                                            </dd>
                                        </div>
                                        @if($selectedJabatan->updated_by)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Diperbarui Oleh</dt>
                                                <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                    {{ $selectedJabatan->updater->name ?? $selectedJabatan->updated_by }}
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

</div>