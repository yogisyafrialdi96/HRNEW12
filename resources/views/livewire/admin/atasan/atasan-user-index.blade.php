<div>
    <!-- Header Section with Title and Add Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <flux:heading size="xl">Atasan User</flux:heading>
            <flux:text class="mt-2">Kelola hirarki atasan dan approval level untuk setiap user</flux:text>
        </div>
        <button wire:click="create"
            class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition duration-200 whitespace-nowrap text-sm font-medium h-fit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Tambah Atasan</span>
        </button>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6 border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari User/Atasan</label>
                <div class="relative">
                    <input type="text" wire:model.live="search" placeholder="Masukkan nama user atau atasan..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                    <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Level Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Level</label>
                <select wire:model.live="filterLevel"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                    <option value="">Semua Level</option>
                    <option value="1">Level 1</option>
                    <option value="2">Level 2</option>
                    <option value="3">Level 3</option>
                    <option value="4">Level 4</option>
                </select>
            </div>

            <!-- Clear Filters Button -->
            <div class="flex items-end">
                <button wire:click="clearFilters"
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Widget Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
        <!-- Total Atasan User -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Atasan</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $atasanUsers->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Level 1 Count -->
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border border-purple-200 dark:border-purple-700">
            <div class="flex items-center gap-3">
                <div class="bg-purple-600 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Level 1-4</p>
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $atasanUsers->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Active Count -->
        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-4 border border-emerald-200 dark:border-emerald-700">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-600 rounded-lg p-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">Aktif</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="relative overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
        <!-- Toolbar with Mass Delete -->
        @if (count($selectedIds) > 0)
            <div class="px-4 py-3 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-200 dark:border-blue-700 flex items-center justify-between">
                <span class="text-sm font-medium text-blue-900 dark:text-blue-100">{{ count($selectedIds) }} item dipilih</span>
                <button wire:click="massDelete"
                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium transition">
                    Hapus Dipilih
                </button>
            </div>
        @endif
        
        <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
            <thead class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="px-4 py-3 w-12">
                        <input type="checkbox" wire:model.live="selectAll" wire:click="toggleSelectAll" class="w-4 h-4 rounded">
                    </th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Atasan</th>
                    <th class="px-4 py-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 select-none" wire:click="sortBy('level')">
                        <div class="flex items-center gap-2 w-fit">
                            Level
                            @if ($sortBy === 'level')
                                @if ($sortDirection === 'asc')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                                        <path d="M3.293 9.707a1 1 0 011.414 0L10 15.414l5.293-5.707a1 1 0 111.414 1.414l-6 6.5a1 1 0 01-1.414 0l-6-6.5a1 1 0 010-1.414z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                                        <path d="M16.707 10.293a1 1 0 010 1.414l-6 6.5a1 1 0 01-1.414 0l-6-6.5a1 1 0 111.414-1.414L10 14.586l5.293-5.293a1 1 0 011.414 0z"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th class="px-4 py-3 cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 select-none" wire:click="sortBy('is_active')">
                        <div class="flex items-center gap-2 w-fit">
                            Status
                            @if ($sortBy === 'is_active')
                                @if ($sortDirection === 'asc')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                                        <path d="M3.293 9.707a1 1 0 011.414 0L10 15.414l5.293-5.707a1 1 0 111.414 1.414l-6 6.5a1 1 0 01-1.414 0l-6-6.5a1 1 0 010-1.414z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                                        <path d="M16.707 10.293a1 1 0 010 1.414l-6 6.5a1 1 0 01-1.414 0l-6-6.5a1 1 0 111.414-1.414L10 14.586l5.293-5.293a1 1 0 011.414 0z"></path>
                                    </svg>
                                @endif
                            @else
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                                </svg>
                            @endif
                        </div>
                    </th>
                    <th class="px-4 py-3">Mulai</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($atasanUsers as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" :checked="$wire.selectedIds.includes({{ $item->id }}.toString())" wire:change="toggleSelected({{ $item->id }})" class="w-4 h-4 rounded">
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->user?->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->user?->email ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-900 dark:text-white">{{ $item->atasan?->name ?? 'N/A' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300">
                                L{{ $item->level }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($item->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white font-medium">
                            @if ($item->effective_from)
                                <div class="flex flex-col">
                                    <span>{{ \Carbon\Carbon::parse($item->effective_from)->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($item->effective_from)->diffForHumans(null, true) }} ago</span>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button wire:click="edit({{ $item->id }})" title="Edit"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button wire:click="viewHistory({{ $item->id }})" title="Lihat History"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-purple-100 dark:bg-purple-900/30 hover:bg-purple-200 dark:hover:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <button wire:click="toggleActive({{ $item->id }})" :title="'{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}'"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs {{ $item->is_active ? 'bg-yellow-100 dark:bg-yellow-900/30 hover:bg-yellow-200 dark:hover:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300' : 'bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300' }} rounded transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $item->id }})" title="Hapus"
                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 rounded transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="font-semibold text-gray-900 dark:text-white mb-1">Tidak ada data</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Mulai tambahkan atasan user untuk hirarki approval</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $atasanUsers->links() }}
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
                                    {{ $isEdit ? 'Edit Atasan User' : 'Tambah Atasan User Baru' }}
                                </h3>
                                <button wire:click="closeModal"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Form Body -->
                        <div class="px-6 py-6 space-y-4">
                            <!-- User Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">User *</label>
                                <select wire:model="user_id"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition @error('user_id') border-red-500 @enderror">
                                    <option value="">Pilih User...</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Atasan Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Atasan *</label>
                                <select wire:model="atasan_id"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition @error('atasan_id') border-red-500 @enderror">
                                    <option value="">Pilih Atasan...</option>
                                    @foreach ($users as $user)
                                        @if ($user->id !== $this->user_id)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('atasan_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Level Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Level *</label>
                                <select wire:model="level"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition @error('level') border-red-500 @enderror">
                                    <option value="">Pilih Level...</option>
                                    <option value="1">Level 1 - Direktur/Kepala</option>
                                    <option value="2">Level 2 - Manager/Supervisor</option>
                                    <option value="3">Level 3 - Team Lead</option>
                                    <option value="4">Level 4 - Staff</option>
                                </select>
                                @error('level')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dates Row -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Start Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai *</label>
                                    <input type="date" wire:model="start_date"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition @error('start_date') border-red-500 @enderror">
                                    @error('start_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Berakhir</label>
                                    <input type="date" wire:model="end_date"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition @error('end_date') border-red-500 @enderror">
                                    @error('end_date')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status Checkbox -->
                            <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <input type="checkbox" wire:model="is_active" id="is_active" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktif</label>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan</label>
                                <textarea wire:model="notes" rows="3" placeholder="Tambahkan catatan..."
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white transition resize-none @error('notes') border-red-500 @enderror"></textarea>
                                @error('notes')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 flex items-center justify-end gap-3">
                            <button wire:click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 rounded-lg transition">
                                Batal
                            </button>
                            <button wire:click="save" wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 rounded-lg transition inline-flex items-center gap-2">
                                <span wire:loading.remove>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                                <span wire:loading>
                                    <svg class="animate-spin h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                {{ $isEdit ? 'Perbarui' : 'Simpan' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endteleport
    @endif

    <!-- Modal Confirmation -->
    @if ($confirmingDelete)
        @if (!empty($selectedIdsToDelete))
            <x-modal-confirmation.modal-confirm-delete onConfirm="confirmMassDelete" isMassDelete="true" :itemCount="count($selectedIdsToDelete)" />
        @else
            <x-modal-confirmation.modal-confirm-delete onConfirm="confirmDelete" />
        @endif
    @endif

    <!-- History Modal (Read-Only) -->
    @if ($showHistoryModal && $historyModel)
        @teleport('body')
            <div x-data x-transition class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div x-data x-transition class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-t-xl px-6 py-4 sticky top-0 z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-white">Audit Trail - Riwayat Perubahan</h2>
                                <p class="text-sm text-purple-100 mt-1">
                                    User: <strong>{{ $historyModel->user?->name ?? 'N/A' }}</strong> 
                                    | Atasan: <strong>{{ $historyModel->atasan?->name ?? 'N/A' }}</strong>
                                </p>
                            </div>
                            <button wire:click="closeHistoryModal()" class="text-white hover:text-purple-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-4">
                        @forelse ($histories as $history)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50">
                                <!-- Timeline Header -->
                                <div class="flex items-start justify-between mb-3 pb-3 border-b border-gray-200 dark:border-gray-600">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex px-2 py-1 rounded text-xs font-semibold text-white {{ match($history['action']) {
                                                'created' => 'bg-green-600',
                                                'updated' => 'bg-blue-600',
                                                'deactivated' => 'bg-yellow-600',
                                                'deleted' => 'bg-red-600',
                                                default => 'bg-gray-600',
                                            } }}">
                                                {{ ucfirst($history['action']) }}
                                            </span>
                                            <span class="text-xs text-gray-600 dark:text-gray-400">Level {{ $history['level'] }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                                            <strong>{{ $history['changed_by'] }}</strong>
                                        </p>
                                    </div>
                                    <span class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $history['created_at']->format('d M Y H:i') }}
                                    </span>
                                </div>

                                <!-- Reason -->
                                @if ($history['reason'])
                                    <div class="mb-3">
                                        <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan:</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 italic">{{ $history['reason'] }}</p>
                                    </div>
                                @endif

                                <!-- Data Changes -->
                                @if (is_array($history['old_data']) || is_array($history['new_data']))
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @if (is_array($history['old_data']) && count($history['old_data']) > 0)
                                            <div class="bg-red-50 dark:bg-red-900/20 rounded p-3 border border-red-200 dark:border-red-700">
                                                <p class="text-xs font-semibold text-red-700 dark:text-red-300 mb-2">Data Lama:</p>
                                                <div class="text-xs text-gray-700 dark:text-gray-300 space-y-1">
                                                    @foreach ($history['old_data'] as $key => $value)
                                                        @if (!in_array($key, ['created_at', 'updated_at']))
                                                            <p><span class="font-medium">{{ $key }}:</span> 
                                                                @if (is_array($value))
                                                                    <code class="bg-red-100 dark:bg-red-900 px-1 rounded text-xs">{{ json_encode($value) }}</code>
                                                                @else
                                                                    {{ $value ?? '-' }}
                                                                @endif
                                                            </p>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if (is_array($history['new_data']) && count($history['new_data']) > 0)
                                            <div class="bg-green-50 dark:bg-green-900/20 rounded p-3 border border-green-200 dark:border-green-700">
                                                <p class="text-xs font-semibold text-green-700 dark:text-green-300 mb-2">Data Baru:</p>
                                                <div class="text-xs text-gray-700 dark:text-gray-300 space-y-1">
                                                    @foreach ($history['new_data'] as $key => $value)
                                                        @if (!in_array($key, ['created_at', 'updated_at']))
                                                            <p><span class="font-medium">{{ $key }}:</span> 
                                                                @if (is_array($value))
                                                                    <code class="bg-green-100 dark:bg-green-900 px-1 rounded text-xs">{{ json_encode($value) }}</code>
                                                                @else
                                                                    {{ $value ?? '-' }}
                                                                @endif
                                                            </p>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-8 text-center">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400">Belum ada riwayat perubahan</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 rounded-b-xl flex justify-end border-t border-gray-200 dark:border-gray-700 sticky bottom-0">
                        <button wire:click="closeHistoryModal()"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition text-sm font-medium">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endteleport
    @endif
</div>
