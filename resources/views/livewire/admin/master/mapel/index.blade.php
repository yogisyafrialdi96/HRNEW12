<div>
    <flux:heading size="xl">Mata Pelajaran</flux:heading>
    <flux:text class="mt-2">This Page Show List of Mata Pelajarans</flux:text>

    <div class="relative overflow-x-auto bg-white rounded-lg shadow-md p-6 mb-4 mt-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex flex-col md:flex-row gap-4 flex-1">
                <div class="flex-1">
                    <input wire:model.live="search" type="text" placeholder="Search Mata Pelajaran..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="flex gap-2">
                    <select wire:model.live="statusFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>

                    <select wire:model.live="perPage"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="10">10/page</option>
                        <option value="25">25/page</option>
                        <option value="50">50/page</option>
                    </select>
                </div>
            </div>

            <!-- Add Button -->
            <div>
                <button wire:click="$toggle('showDeleted')"
                    class="bg-zinc-400 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    {{ $showDeleted ? 'Show Exist' : 'Show Deleted' }}
                </button>
            </div>
            <div>
                <button wire:click="create"
                    class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Unit
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
        <div class="relative overflow-x-auto mt-4 shadow-md sm:rounded-lg">
           
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- No - Fixed width -->
                        
                        <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('nama_mapel')">
                            <div class="flex items-center gap-2">
                                <span>Mata Pelajaran</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'nama_mapel')
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('kode_mapel')">
                            <div class="flex items-center gap-2">
                                <span>Kode</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'kode_mapel')
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('is_active')">
                            <div class="flex items-center gap-2">
                                <span>Status</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'is_active')
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('created_at')">
                            <div class="flex items-center gap-2">
                                created</span>
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

                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mapels as $mapel)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $mapels->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ $mapel->nama_mapel ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $mapel->kode_mapel ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $mapel->id }})"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $mapel->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $mapel->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $mapel->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">by {{ $mapel->creator->name ?? 'System' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if ($showDeleted)
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="confirmRestore({{ $mapel->id }})"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded transition duration-200"
                                            title="Reset Mapel">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99">
                                                </path>
                                            </svg>

                                        </button>
                                        <button wire:click="confirmForceDelete({{ $mapel->id }})"
                                            class="text-red-600 hover:text-red-900 p-1 rounded transition duration-200"
                                            title="Hard Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <div class="flex justify-center gap-2">
                                        <!-- Detail Button -->
                                        <button wire:click="showDetail({{ $mapel->id }})"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50"
                                            title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="edit({{ $mapel->id }})"
                                            class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-50 transition duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $mapel->id }})"
                                            class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">No Mapel found</p>
                                    <p class="text-sm">Get started by creating a new Mapel.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-3 px-4 text-xs">
                {{ $mapels->links() }}
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div
            class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg mx-auto">
                <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900">
                                {{ $isEdit ? 'Edit Mata Pelajaran' : 'Create Mata Pelajaran' }}
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
                                <!-- Mata Pelajaran Name -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">
                                        Nama Mata Pelajaran <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model.live="nama_mapel" type="text" placeholder="Enter Mata Pelajaran name"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all capitalize">
                                    @error('nama_mapel')
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

                                <!-- Mata Pelajaran Code -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">Mata Pelajaran Code <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="kode_mapel"
                                        maxlength="10" placeholder="e.g., MTK, PAI, BIN"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all uppercase placeholder:normal-case">
                                    @error('kode_mapel')
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

                            <!-- Tugas Pokok -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Tugas Pokok</label>
                                <textarea wire:model="tugas_pokok" rows="3" placeholder="sebutkan tugas pokok Mata Pelajaran..."
                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                                @error('tugas_pokok')
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

                            <!-- Requirements -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Requirements</label>
                                <textarea wire:model="requirements" rows="3" placeholder="Sebutkan persyaratan/kebutuhan Mata Pelajaran..."
                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                                @error('requirements')
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

                            <!-- Status -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model="is_active" type="radio" value="1"
                                            class="sr-only peer">
                                        <div
                                            class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-50 relative">
                                            <div
                                                class="absolute inset-0.5 bg-green-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity">
                                            </div>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input wire:model="is_active" type="radio" value="0"
                                            class="sr-only peer">
                                        <div
                                            class="w-4 h-4 border-2 border-gray-300 rounded-full peer-checked:border-red-500 peer-checked:bg-red-50 relative">
                                            <div
                                                class="absolute inset-0.5 bg-red-500 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity">
                                            </div>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                    </label>
                                </div>
                                @error('is_active')
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
                                    {{ $isEdit ? 'Update Mata Pelajaran' : 'Create Mata Pelajaran' }}
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

    <!-- Detail modal -->
    @if ($showModalDetail)
        <div
            class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div class="relative w-full max-w-lg mx-auto">
                <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                    <div class="flex flex-col h-full">
                        <!-- Header dengan Pagination Controls -->
                        <div
                            class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                    </svg>

                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detai Mata Pelajaran
                                    </h2>
                                </div>


                            </div>
                            <div class="flex items-center space-x-2">
                                <button wire:click="closeModal"
                                    class="p-2 bg-gray-100 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>


                        <!-- Content Area -->
                        <div class="flex-1 overflow-y-auto p-4">
                            @if (!empty($selectedMapel))
                                <!-- Company Header -->
                                <div
                                    class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3
                                                class="text-lg font-bold text-gray-900 dark:text-white mb-1 capitalize">
                                                {{ $selectedMapel->nama_mapel ?? 'Mapel tidak diketahui' }}
                                            </h3>
                                            <p class="text-blue-600 dark:text-blue-400 font-medium capitalize">
                                                Kode: {{ $selectedMapel->kode_mapel ?? 'tidak diketahui' }}
                                            </p>
                                        </div>
                                        <div>
                                            <span
                                                        class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                        {{ $selectedMapel->is_active ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
                                                        {{ $selectedMapel->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                    </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Requirements -->
                                @if ($selectedMapel->requirements)
                                    <div
                                        class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                        <div
                                            class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Requirements</h4>
                                        </div>
                                        <div class="p-4">
                                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                                {{ $selectedMapel->requirements }}</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Tugas Pokok -->
                                @if ($selectedMapel->tugas_pokok)
                                    <div
                                        class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                        <div
                                            class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">Tugas Pokok</h4>
                                        </div>
                                        <div class="p-4">
                                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                                {{ $selectedMapel->tugas_pokok }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div
                                    class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <div
                                        class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">Log Informasi</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 space-y-3">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedMapel->created_at ? \Carbon\Carbon::parse($selectedMapel->created_at)->format('d F Y') : 'N/A' }}
                                            </dd>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="inline text-sm text-gray-600">by</div>
                                                {{ $selectedMapel->creator?->name ?? '-' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Edited</dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedMapel->updated_at ? \Carbon\Carbon::parse($selectedMapel->updated_at)->format('d F Y') : 'N/A' }}
                                            </dd>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="inline text-sm text-gray-600">by</div>
                                                {{ $selectedMapel->updater?->name ?? '-' }}
                                            </dd>
                                        </div>

                                    </div>
                                </div>
                            @else
                                <div class="flex items-center justify-center py-12">
                                    <div class="text-center">
                                        <div
                                            class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400">Memuat data...</p>
                                    </div>
                                </div>
                            @endif
                        </div>


                    </div>
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
