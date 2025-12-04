<div class="flex flex-col h-screen bg-gray-50 dark:bg-gray-900">
    <div class="flex-1 overflow-y-auto">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-40">
            <div class="px-6 py-4">
    <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Permission Management</h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Kelola permissions dan assign ke roles</p>
                    </div>
                    <div class="flex gap-2">
                        @can('permissions.create')
                            <button wire:click="openModalModule" 
                                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                Manage Modules
                            </button>
                        @endcan
                        
                        @can('permissions.create')
                            <button wire:click="openModal" 
                                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Permission
                            </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Compact Filter Section -->
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <div class="flex gap-3 items-center flex-wrap">
                    <!-- Search -->
                    <div class="flex-1 min-w-xs">
                        <input wire:model.live="search" 
                            type="text" 
                            placeholder="Cari permission..." 
                            class="w-full px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-1 focus:ring-blue-500 transition-all">
                    </div>

                    <!-- Module Filter -->
                    <select wire:model.live="filterModule"
                        class="px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-1 focus:ring-blue-500 transition-all">
                        <option value="">All Modules</option>
                        @foreach ($modules as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    <!-- Per Page -->
                    <select wire:model.live="perPage"
                        class="px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-1 focus:ring-blue-500 transition-all">
                        <option value="10">10/Page</option>
                        <option value="25">25/Page</option>
                        <option value="50">50/Page</option>
                        <option value="100">100/Page</option>
                    </select>

                    <!-- Clear Filters (optional) -->
                    @if ($search || $filterModule)
                        <button wire:click="$set('search', ''); $set('filterModule', '')"
                            class="px-3 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Compact Table Section -->
        <div class="px-6 py-4">
            @if ($permissions->count() > 0)
                <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                    wire:click="sortBy('id')">
                                    <div class="flex items-center gap-1">
                                        <span>#</span>
                                        @if ($sortField === 'id')
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                @if ($sortDirection === 'asc')
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V8z" clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd" d="M3 16a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm0-4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                    wire:click="sortBy('name')">
                                    <div class="flex items-center gap-1">
                                        <span>Permission</span>
                                        @if ($sortField === 'name')
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                @if ($sortDirection === 'asc')
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V8z" clip-rule="evenodd"></path>
                                                @else
                                                    <path fill-rule="evenodd" d="M3 16a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm0-4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z" clip-rule="evenodd"></path>
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Module</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Description</th>
                                <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Roles</th>
                                <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($permissions as $index => $permission)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                        {{ ($permissions->currentPage() - 1) * $permissions->perPage() + $index + 1 }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            {{ $permission->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                            {{ $this->extractModule($permission->name) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-xs text-gray-600 dark:text-gray-400">
                                        <span class="line-clamp-1">{{ $permission->description ?? '—' }}</span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        @php

                                            $roleCount = $permission->roles()->count();
                                        @endphp
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold 
                                            {{ $roleCount > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                            {{ $roleCount }} role(s)
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @can('permissions.view')
                                                <button wire:click="showDetail({{ $permission->id }})" 
                                                    class="p-1 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors" title="Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                            @endcan

                                            @can('permissions.assign_roles')
                                                <button wire:click="openAssignRoles({{ $permission->id }})" 
                                                    class="p-1 text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded transition-colors" title="Assign Roles">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                                                    </svg>
                                                </button>
                                            @endcan

                                            @can('permissions.edit')
                                                <button wire:click="edit({{ $permission->id }})" 
                                                    class="p-1 text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            @endcan

                                            @can('permissions.delete')
                                                <button wire:click="delete({{ $permission->id }})" wire:confirm="Yakin hapus permission ini?" 
                                                    class="p-1 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                    {{ $permissions->links('pagination::tailwind') }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada permission</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Create/Edit Permission -->
    @if ($showModal)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full animate-in fade-in zoom-in">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $isEdit ? 'Edit Permission' : 'Add New Permission' }}
                    </h3>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6">
                    <form wire:submit.prevent="save" class="space-y-5">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Permission Name <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="name" type="text" placeholder="e.g., users.view, users.create"
                                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            @error('name')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Module -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Module <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="selectedModule"
                                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="">Pilih Module...</option>
                                @foreach ($modules as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('selectedModule')
                                <p class="text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description
                            </label>
                            <textarea wire:model="description" placeholder="Deskripsi permission..."
                                rows="3"
                                class="w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                            @error('description')
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

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-2">
                    <button wire:click="closeModal()" 
                        class="flex-1 px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button wire:click="save()" 
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                        {{ $isEdit ? 'Update' : 'Create' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Detail Permission -->
    @if ($showModalDetail && $selectedPermission)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full animate-in fade-in zoom-in">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Permission Detail</h3>
                    <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 space-y-6">
                    <!-- Name -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Name</p>
                        <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $selectedPermission->name }}</p>
                    </div>

                    <!-- Module -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Module</p>
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            {{ $this->extractModule($selectedPermission->name) }}
                        </span>
                    </div>

                    <!-- Description -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Description</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $selectedPermission->description ?? '-' }}
                        </p>
                    </div>

                    <!-- Assigned Roles -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Assigned Roles</p>
                        @if ($selectedPermission->roles->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach ($selectedPermission->roles as $role)
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada role yang di-assign</p>
                        @endif
                    </div>

                    <!-- Created Info -->
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Created</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $selectedPermission->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Updated</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $selectedPermission->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-2">
                    <button wire:click="closeModal()" 
                        class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Assign Roles -->
    @if ($showModalAssignRoles && $selectedPermission)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full animate-in fade-in zoom-in">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Assign Roles to: <span class="text-purple-600">{{ $selectedPermission->name }}</span>
                    </h3>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6">
                    @if ($availableRoles->count() > 0)
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach ($availableRoles as $role)
                                <label class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors">
                                    <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}"
                                        class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $role->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $role->description ?? 'No description' }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-6">Belum ada role yang tersedia</p>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-2">
                    <button wire:click="closeModal()" 
                        class="flex-1 px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Batal
                    </button>
                    <button wire:click="assignRoles()" 
                        class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors">
                        Assign
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Manage Modules -->
    @if ($showModalModule)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full animate-in fade-in zoom-in max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Manage Modules</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola modul yang tersedia untuk permissions</p>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-6 space-y-6">
                    <!-- Add/Edit Module Form -->
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">
                            {{ $isEditModule ? 'Edit Module' : 'Add New Module' }}
                        </h4>

                        <form wire:submit.prevent="saveModule" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Module Key -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Module Key <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="moduleKey" 
                                        type="text" 
                                        placeholder="e.g., employees, contracts"
                                        class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                                    @error('moduleKey')
                                        <p class="text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Module Label -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Module Label <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="moduleLabel" 
                                        type="text" 
                                        placeholder="e.g., Employee Management"
                                        class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                                    @error('moduleLabel')
                                        <p class="text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex gap-2 pt-2">
                                <button type="submit" 
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium text-sm transition-colors">
                                    {{ $isEditModule ? 'Update' : 'Add' }}
                                </button>
                                @if ($isEditModule)
                                    <button type="button" 
                                        wire:click="$set('isEditModule', false); $set('moduleKey', ''); $set('moduleLabel', '')"
                                        class="px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        Reset
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- Modules List -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Existing Modules ({{ count($modules) }})</h4>
                        
                        @if (count($modules) > 0)
                            <div class="space-y-2 max-h-96 overflow-y-auto">
                                @foreach ($modules as $key => $label)
                                    @php
                                        $permissionCount = \Spatie\Permission\Models\Permission::where('name', 'like', $key . '.%')->count();
                                    @endphp
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-blue-500 rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-xs font-bold">
                                                        {{ strtoupper(substr($key, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $label }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        <span class="font-mono text-gray-600 dark:text-gray-300">{{ $key }}</span> 
                                                        • {{ $permissionCount }} permission(s)
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if ($permissionCount == 0)
                                                <button wire:click="deleteModule('{{ $key }}')" 
                                                    wire:confirm="Yakin hapus module '{{ $label }}'?"
                                                    class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                <div class="text-xs text-gray-500 dark:text-gray-400 px-2">
                                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 0 1 5.11 2.697a6 6 0 1 1 8.367 8.192zm-1.414-1.414a4 4 0 1 0-5.656-5.656 4 4 0 0 0 5.656 5.656z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <button wire:click="editModule('{{ $key }}')" 
                                                class="p-2 text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">Belum ada module</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 sticky bottom-0">
                    <button wire:click="closeModal()" 
                        class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
