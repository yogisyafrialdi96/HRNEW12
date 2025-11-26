<div>

    <flux:heading size="xl">Units</flux:heading>
    <flux:text class="mt-2">This Page Show List of Units</flux:text>

    <div class="relative overflow-x-auto bg-white rounded-lg shadow-md p-6 mb-4 mt-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex flex-col md:flex-row gap-4 flex-1">
                <div class="flex-1">
                    <input wire:model.live="search" type="text" placeholder="Search units..."
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
        <div class="relative overflow-x-auto mt-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- No - Fixed width -->
                        <th
                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 w-16">
                            <div class="flex items-center gap-2">
                                <span>No</span>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('unit')">
                            <div class="flex items-center gap-2">
                                <span>Unit</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'unit')
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <span>Jumlah Pegawai</span>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('kepala_unit')">
                            <div class="flex items-center gap-2">
                                <span>Pimpinan</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'kepala_unit')
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
                            wire:click="sortBy('status')">
                            <div class="flex items-center gap-2">
                                <span>Status</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'status')
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

                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($units as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $units->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4 w-72">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $unit->unit }}</div>
                                    {{-- @if ($department->company->singkatan) --}}
                                    <div class="text-sm text-gray-500 truncate max-w-md">
                                        {{ $unit->department->department ?: '-' }} | {{ $unit->kode_unit ?: '-' }}</div>
                                    {{-- @endif --}}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $this->getEmployeeCount($unit->id) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $unit->kepalaUnit->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $unit->id }})"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $unit->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $unit->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $unit->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">by {{ $unit->creator->name ?? 'System' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if ($showDeleted)
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="confirmRestore({{ $unit->id }})"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded transition duration-200"
                                            title="Reset Unit">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99">
                                                </path>
                                            </svg>

                                        </button>
                                        <button wire:click="confirmForceDelete({{ $unit->id }})"
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
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="edit({{ $unit->id }})"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded transition duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $unit->id }})"
                                            class="text-red-600 hover:text-red-900 p-1 rounded transition duration-200">
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
                                    <p class="text-lg font-medium">No Units found</p>
                                    <p class="text-sm">Get started by creating a new Units.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-3 px-4 text-xs">
                {{ $units->links() }}
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
                                {{ $isEdit ? 'Edit Unit' : 'Create Unit' }}
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
                            <!-- Department Selection -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">
                                    Department <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select wire:model="department_id"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all appearance-none">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->department }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                @error('department_id')
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

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Unit Name -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">
                                        Unit Name <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="unit" type="text" placeholder="Enter unit name"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all uppercase placeholder:normal-case">
                                    @error('unit')
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

                                <!-- Unit Code -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">Unit Cod</label>
                                    <input type="text"
                                        @if ($isEdit) wire:model="kode_unit" 
                                        @else 
                                            value="{{ $this->getNextCode() }}" @endif
                                        maxlength="10" disabled placeholder="e.g., IT, HR, FIN"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    @error('kode_unit')
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

                            <!-- Unit Head -->
                            <div class="space-y-2" x-data="{
                                open: false,
                                search: '',
                                users: @js($users),
                                selectedUserId: @entangle('kepala_unit'),
                                get selectedUser() {
                                    return this.users.find(u => u.id == this.selectedUserId) || null;
                                },
                                get filteredUsers() {
                                    if (!this.search) return this.users;
                                    return this.users.filter(user =>
                                        user.name.toLowerCase().includes(this.search.toLowerCase())
                                    );
                                },
                                selectUser(userId) {
                                    this.selectedUserId = userId;
                                    this.open = false;
                                },
                                clearSelection() {
                                    this.selectedUserId = null;
                                    this.open = false;
                                }
                            }">
                                <label class="text-sm font-medium text-gray-700">Unit Head</label>
                                <div class="relative">
                                    <!-- Display Input -->
                                    <div @click="open = !open"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer flex items-center justify-between">
                                        <span class="text-gray-500" x-show="!selectedUser">Select Unit
                                            Head</span>
                                        <span x-show="selectedUser"
                                            x-text="selectedUser?.name || 'Select Unit Head'"></span>
                                        <svg class="w-4 h-4 text-gray-400 transition-transform"
                                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>

                                    <!-- Dropdown -->
                                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95" @click.away="open = false"
                                        class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-hidden">

                                        <!-- Search Input -->
                                        <div class="p-3 border-b border-gray-100">
                                            <div class="relative">
                                                <input x-model="search" type="text"
                                                    placeholder="Search employees..."
                                                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Options List -->
                                        <div class="max-h-48 overflow-y-auto">
                                            <!-- Clear Selection Option -->
                                            <div @click="clearSelection()"
                                                class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors border-b border-gray-100">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm text-gray-500 italic">No Unit
                                                        Head</span>
                                                </div>
                                            </div>

                                            <!-- User Options -->
                                            <template x-for="user in filteredUsers" :key="user.id">
                                                <div @click="selectUser(user.id)"
                                                    class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors"
                                                    :class="{
                                                        'bg-blue-50 border-r-2 border-blue-500': selectedUserId == user
                                                            .id
                                                    }">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-medium"
                                                            x-text="user.name.charAt(0).toUpperCase()">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="text-sm font-medium text-gray-900"
                                                                x-text="user.name"></div>
                                                            <div class="text-xs text-gray-500" x-text="user.email"
                                                                x-show="user.email"></div>
                                                        </div>
                                                        <div x-show="selectedUserId == user.id">
                                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- No Results -->
                                            <div x-show="search && filteredUsers.length === 0"
                                                class="px-4 py-6 text-center">
                                                <svg class="mx-auto w-12 h-12 text-gray-300 mb-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                                <p class="text-sm text-gray-500">No employees found</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('kepala_unit')
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

                            <!-- Description -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="deskripsi" rows="3" placeholder="Brief description of the unit..."
                                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                                @error('deskripsi')
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
                                    {{ $isEdit ? 'Update Unit' : 'Create Unit' }}
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

    <!-- Modal Konfirmasi Floating (Tanpa overlay) -->
    <x-modal-confirmation.modal-confirm-delete wire:model="confirmingDelete" onConfirm="delete" />
    <x-modal-confirmation.modal-force-delete />
    <x-modal-confirmation.modal-restore />
    {{-- end modal --}}
</div>
