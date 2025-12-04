<div>
    <!-- Header Section -->
    <div class="px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Manajemen Role
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Kelola role dan permission untuk pengguna sistem
                </p>
            </div>
            @can('roles.create')
                <button wire:click="openModal"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Role
                </button>
            @else
                <button disabled
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-gray-400 bg-gray-200 cursor-not-allowed transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Role
                </button>
            @endcan
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="px-4 sm:px-6 lg:px-8 pb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-12">
                <!-- Search -->
                <div class="sm:col-span-8">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cari Role
                    </label>
                    <input wire:model.live="search" type="text" placeholder="Cari berdasarkan nama atau deskripsi..."
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Per Page -->
                <div class="sm:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tampilkan per halaman
                    </label>
                    <select wire:model.live="perPage"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Table Section -->
    <div class="px-4 sm:px-6 lg:px-8 pb-8">
        @if ($roles->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Table Header -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <tr>
                                <th class="px-6 py-4 text-left">
                                    <button wire:click="sortBy('id')"
                                        class="text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white flex items-center gap-1">
                                        No
                                        @if ($sortField === 'id')
                                            <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left">
                                    <button wire:click="sortBy('name')"
                                        class="text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white flex items-center gap-1">
                                        Nama Role
                                        @if ($sortField === 'name')
                                            <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    Deskripsi
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    Jumlah Permission
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    Jumlah User
                                </th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach ($roles as $index => $role)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="flex items-center justify-center h-8 w-8 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600">
                                                    <svg class="h-5 w-5 text-white" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $role->guard_name ?? 'web' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $role->description ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                            {{ $role->permissions_count ?? $role->permissions()->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200">
                                            {{ $role->users()->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @can('roles.view')
                                                <button wire:click="showDetail({{ $role->id }})"
                                                    title="Lihat Detail"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                            @endcan

                                            @if (!in_array($role->name, ['super_admin', 'admin']))
                                                @can('roles.edit')
                                                    <button wire:click="edit({{ $role->id }})"
                                                        title="Edit"
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                @endcan

                                                @can('roles.delete')
                                                    <button wire:click="delete({{ $role->id }})"
                                                        wire:confirm="Apakah Anda yakin ingin menghapus role ini?"
                                                        title="Hapus"
                                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @endcan
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200">
                                                    Protected
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    {{ $roles->links() }}
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada role</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan membuat role baru</p>
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ open: @entangle('showModal') }">
            <!-- Backdrop with Blur -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-all duration-300" wire:click="closeModal">
            </div>

            <div class="flex items-center justify-center min-h-screen p-4 sm:p-0">
                <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-3xl transform transition-all duration-300 max-h-[90vh] overflow-y-auto"
                    @click.stop>

                    <!-- Header (Sticky) -->
                    <div class="sticky top-0 bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between z-10">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $isEdit ? '✏️ Edit Role' : '➕ Tambah Role Baru' }}
                        </h3>
                        <button wire:click="closeModal" type="button"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <div class="px-6 py-6">
                        <form wire:submit.prevent="save" class="space-y-6">

                            <!-- Nama Role -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nama Role <span class="text-red-500">*</span>
                                </label>
                                <input wire:model="name" type="text" placeholder="Contoh: moderator, editor"
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('name')
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

                            <!-- Deskripsi -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Deskripsi
                                </label>
                                <textarea wire:model="description" rows="3" placeholder="Deskripsi role (opsional)"
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                @error('description')
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

                            <!-- Permissions Section (Grouped by Module) -->
                            @if (!empty($permissionsByModule))
                                <div class="space-y-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H8a3 3 0 013 3v6h3a1 1 0 01.82.4l2.5 3.75H6a1 1 0 100 2h12a1 1 0 00.82-.4l2.5-3.75V5a3 3 0 00-3-3H4a2 2 0 00-2 2v10a2 2 0 002 2h6a1 1 0 100-2H4V5z" clip-rule="evenodd"></path>
                                            </svg>
                                            Pilih Permission
                                        </h4>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Total: {{ count($permissionsByModule) }} modules</span>
                                    </div>

                                    <!-- Permissions grouped by module -->
                                    <div class="space-y-5">
                                        @foreach ($permissionsByModule as $module => $permissions)
                                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                                <!-- Module Header -->
                                                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-blue-200 dark:border-blue-800">
                                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                                                        <span class="text-xs font-bold text-white">{{ strtoupper(substr($module, 0, 1)) }}</span>
                                                    </div>
                                                    <h5 class="text-sm font-semibold text-gray-900 dark:text-white capitalize">
                                                        {{ str_replace('_', ' ', $module) }} Module
                                                    </h5>
                                                    <span class="ml-auto text-xs font-medium text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-700 px-2 py-1 rounded">
                                                        {{ count($permissions) }} permission(s)
                                                    </span>
                                                </div>

                                                <!-- Module Permissions Grid -->
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    @foreach ($permissions as $permission)
                                                        <div class="flex items-start gap-3 p-2 rounded hover:bg-white/50 dark:hover:bg-gray-700/30 transition-colors cursor-pointer group">
                                                            <input type="checkbox" wire:model="selectedPermissions"
                                                                value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                                                class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                                            <label for="perm_{{ $permission->id }}"
                                                                class="text-sm text-gray-700 dark:text-gray-300 cursor-pointer flex-1 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                                                <span class="font-medium capitalize">{{ str_replace('_', ' ', explode('.', $permission->name)[1] ?? $permission->name) }}</span>
                                                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">{{ $permission->name }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </form>
                    </div>

                    <!-- Footer (Sticky) -->
                    <div class="sticky bottom-0 bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-3 z-10">
                        <button wire:click="closeModal" type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <button wire:click="save" type="button"
                            class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-lg">
                            {{ $isEdit ? '💾 Simpan Perubahan' : '✨ Buat Role' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Detail Modal -->
    @if ($showModalDetail && $selectedRole)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ open: @entangle('showModalDetail') }">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900/75 transition-opacity" wire:click="closeModal">
            </div>

            <div class="flex items-center justify-center min-h-screen p-4 sm:p-0">
                <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl transform transition-all"
                    @click.stop>

                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Detail Role
                        </h3>
                        <button wire:click="closeModal" type="button"
                            class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6 space-y-6">
                        <!-- Role Name -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Role</h4>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ ucfirst(str_replace('_', ' ', $selectedRole->name)) }}
                            </p>
                        </div>

                        <!-- Description -->
                        @if ($selectedRole->description)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</h4>
                                <p class="text-gray-600 dark:text-gray-400">{{ $selectedRole->description }}</p>
                            </div>
                        @endif

                        <!-- Permissions -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Permission ({{ $selectedRole->permissions->count() }})
                            </h4>
                            @if ($selectedRole->permissions->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach ($selectedRole->permissions as $permission)
                                        <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada permission yang diberikan</p>
                            @endif
                        </div>

                        <!-- Users with this role -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                User dengan Role ini ({{ $selectedRole->users->count() }})
                            </h4>
                            @if ($selectedRole->users->count() > 0)
                                <div class="space-y-2">
                                    @foreach ($selectedRole->users as $user)
                                        <div class="flex items-center gap-3 px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="flex items-center justify-center h-8 w-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600">
                                                    <span class="text-sm font-semibold text-white">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $user->name }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $user->email }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada user dengan role ini</p>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end">
                        <button wire:click="closeModal" type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
