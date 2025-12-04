<div>

    <flux:heading size="xl">Users</flux:heading>
    <flux:text class="mt-2">This Page Show List of Users</flux:text>

    <!-- Statistics Widget -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mt-4 mb-4">
        <!-- Total Users -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
            <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Total Users</div>
            <div class="text-2xl font-bold text-blue-900 mt-1">{{ $totalUsers }}</div>
        </div>

        <!-- Karyawan -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
            <div class="text-xs font-semibold text-green-600 uppercase tracking-wide">Karyawan</div>
            <div class="text-2xl font-bold text-green-900 mt-1">{{ $karyawanUsers }}</div>
        </div>

        <!-- Pengurus -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
            <div class="text-xs font-semibold text-purple-600 uppercase tracking-wide">Pengurus</div>
            <div class="text-2xl font-bold text-purple-900 mt-1">{{ $pengurusUsers }}</div>
        </div>

        <!-- No Status -->
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
            <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide">No Status</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $noStatusUsers }}</div>
        </div>

        <!-- Active vs Inactive -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-4 border border-amber-200">
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wide">Active / Inactive</div>
            <div class="text-2xl font-bold text-amber-900 mt-1">{{ $activeUsers }}/{{ $inactiveUsers }}</div>
        </div>
    </div>

    <div class="relative overflow-x-auto bg-white rounded-lg shadow-md p-6 mb-4 mt-4">
        <div class="space-y-4">

    <!-- Filters and Actions Row -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                <!-- Filters Grid (3 columns) -->
                <div class="lg:col-span-3 grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <select wire:model.live="statusFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <select wire:model.live="typeFilter"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">All Types</option>
                        <option value="karyawan">Karyawan</option>
                        <option value="pengurus">Pengurus</option>
                    </select>

                    <select wire:model.live="perPage"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="10">10/page</option>
                        <option value="25">25/page</option>
                        <option value="50">50/page</option>
                    </select>

                    <input type="text" wire:model.live="search"
                        class="block p-2 ps-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Search users...">
                </div>

                <!-- Action Buttons (2 columns) -->
                <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button wire:click="$toggle('showDeleted')"
                        class="bg-zinc-400 hover:bg-zinc-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition duration-200 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <span class="hidden sm:inline">{{ $showDeleted ? 'Show Exist' : 'Show Deleted' }}</span>
                        <span class="sm:hidden">{{ $showDeleted ? 'Exist' : 'Deleted' }}</span>
                    </button>

                    @can('users.create')
                        <button wire:click="create"
                            class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition duration-200 whitespace-nowrap">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Add User</span>
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
        <div class="relative overflow-x-auto mt-4 shadow-md sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 w-16">
                            <div class="flex items-center gap-2">
                                <span>No</span>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                            wire:click="sortBy('name')">
                            <div class="flex items-center gap-2">
                                <span>Nama</span>
                                <div class="sort-icon">
                                    @if ($sortField === 'name')
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-2">
                                <span>Email</span>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-2">
                                <span>Type</span>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center gap-2">
                                <span>Role</span>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            wire:click="sortBy('created_at')">
                            <div class="flex items-center gap-2">
                                <span>Created</span>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>

                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-4 whitespace-nowrap text-center text-sm">
                                {{ $users->firstItem() + $loop->index }}.
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <div class="text-sm">
                                        <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($user->karyawan)
                                    <span class="inline-flex px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Karyawan
                                    </span>
                                @elseif ($user->pengurus)
                                    <span class="inline-flex px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Pengurus
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        N/A
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-indigo-100 text-indigo-800 whitespace-nowrap">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-500">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $user->created_at?->format('d M Y') ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $user->id }})"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->karyawan)
                                    {{ $user->karyawan->statuskaryawan_id == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}
                                @elseif($user->pengurus)
                                    {{ $user->pengurus->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}
                                @else
                                    bg-gray-100 text-gray-800
                                @endif
                                ">
                                    @if($user->karyawan)
                                        {{ $user->karyawan->statuskaryawan_id == 1 ? 'Aktif' : 'Nonaktif' }}
                                    @elseif($user->pengurus)
                                        {{ $user->pengurus->is_active ? 'Aktif' : 'Nonaktif' }}
                                    @else
                                        N/A
                                    @endif
                                </button>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if ($showDeleted)
                                    <div class="flex justify-end gap-2">
                                        @can('users.restore')
                                        <button wire:click="confirmRestore({{ $user->id }})"
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded transition duration-200"
                                            title="Restore user">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99">
                                                </path>
                                            </svg>
                                        </button>
                                        @endcan
                                        @can('users.force_delete')
                                        <button wire:click="confirmForceDelete({{ $user->id }})"
                                            class="text-red-600 hover:text-red-900 p-1 rounded transition duration-200"
                                            title="Hard Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                        @endcan
                                    </div>
                                @else
                                    <div class="flex justify-end gap-2">
                                        @can('users.view')
                                            <button wire:click="showDetail({{ $user->id }})"
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
                                        @endcan
                                        @can('users.assign_roles')
                                            <button wire:click="openModalRoles({{ $user->id }})"
                                                class="text-purple-600 hover:text-purple-900 p-1 rounded-md hover:bg-purple-50 transition duration-200"
                                                title="Assign Roles">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('users.edit')
                                            <button wire:click="edit({{ $user->id }})"
                                                class="text-yellow-600 hover:text-yellow-900 p-1 rounded-md hover:bg-yellow-50 transition duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('users.delete')
                                            <button wire:click="confirmDelete({{ $user->id }})"
                                                class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endcan
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
                                    <p class="text-lg font-medium">No user found</p>
                                    <p class="text-sm">Get started by creating a new user.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="py-3 px-4 text-xs">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <div
            class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900">
                                {{ $isEdit ? 'Edit User' : 'Create User' }}
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

                            <div class="grid grid-cols-1 gap-4">
                                <!-- Nama -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">
                                        Nama <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="name" type="text"
                                        placeholder="Enter user name"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
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
                            </div>

                            <div class="grid grid-cols-2 gap-4">
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

                                <!-- User Type -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">
                                        Tipe User <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model="userType"
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        <option value="">Pilih Tipe...</option>
                                        <option value="karyawan">Karyawan</option>
                                        <option value="pengurus">Pengurus</option>
                                    </select>
                                    @error('userType')
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

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Password -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-700">
                                        Password {{ !$isEdit ? '*' : '' }}
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
                                        Confirm Password {{ !$isEdit ? '*' : '' }}
                                    </label>
                                    <input wire:model="password_confirmation" type="password"
                                        placeholder="Enter password confirmation"
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
                                    {{ $isEdit ? 'Update User' : 'Create User' }}
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

                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Pengurus
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
                            @if (!empty($selectedUser))
                                <!-- Company Header -->
                                <div
                                    class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3
                                                class="text-lg font-bold text-gray-900 dark:text-white mb-1 capitalize">
                                                @if ($selectedUser->karyawan)
                                                    {{ $selectedUser->karyawan->full_name ?? 'Karyawan tidak diketahui' }}
                                                @elseif ($selectedUser->pengurus)
                                                    {{ $selectedUser->pengurus->gelar_depan ?? '' }}
                                                    {{ $selectedUser->pengurus->nama_pengurus ?? 'Pengurus tidak diketahui' }},
                                                    {{ $selectedUser->pengurus->gelar_belakang ?? '' }}
                                                @else
                                                    {{ $selectedUser->name }}
                                                @endif
                                            </h3>
                                            <p class="text-blue-600 dark:text-blue-400 font-medium capitalize">
                                                @if ($selectedUser->karyawan)
                                                    {{ $selectedUser->karyawan->jabatan?->nama_jabatan ?? 'Belum ada jabatan' }}
                                                @elseif ($selectedUser->pengurus)
                                                    {{ $selectedUser->pengurus->jabatan?->nama_jabatan ?? 'Belum ada jabatan' }}
                                                @else
                                                    User
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                Email: {{ $selectedUser->email }}
                                            </p>
                                        </div>
                                        <div class="flex-shrink-0 ml-4">
                                            <div
                                                class="w-20 h-20 rounded-lg overflow-hidden border-2 border-gray-200 dark:border-gray-700">
                                                @if ($selectedUser->pengurus && $selectedUser->pengurus->foto)
                                                    <img src="{{ asset('storage/' . $selectedUser->pengurus->foto) }}"
                                                        alt="Foto {{ $selectedUser->pengurus->nama_pengurus }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-400">
                                                        <svg class="w-10 h-10" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Type Badge -->
                                <div class="mb-6 flex items-center gap-2">
                                    @if ($selectedUser->karyawan)
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Karyawan
                                        </span>
                                    @elseif ($selectedUser->pengurus)
                                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Pengurus
                                        </span>
                                    @endif
                                </div>

                                <!-- Data Berdasarkan Tipe User -->
                                @if ($selectedUser->karyawan)
                                    <!-- Data Karyawan -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                            <div
                                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                                <h4 class="font-semibold text-gray-900 dark:text-white">Data Karyawan</h4>
                                            </div>
                                            <div class="p-4 space-y-3">
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Jenis Karyawan
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 capitalize">
                                                        {{ $selectedUser->karyawan->jenis_karyawan ?? 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Status Karyawan
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        <span
                                                            class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                                    {{ $selectedUser->karyawan->statuskaryawan_id == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $selectedUser->karyawan->statuskaryawan_id == 1 ? 'Aktif' : 'Nonaktif' }}
                                                        </span>
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Tanggal Mulai
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->karyawan->tanggal_mulai ? \Carbon\Carbon::parse($selectedUser->karyawan->tanggal_mulai)->format('d F Y') : 'N/A' }}
                                                    </dd>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                            <div
                                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                                <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Pribadi</h4>
                                            </div>
                                            <div class="p-4 space-y-3">
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Jenis Kelamin
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->karyawan->jenis_kelamin ?? 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Tempat Lahir
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->karyawan->tempat_lahir ?? 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Tanggal Lahir
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->karyawan->tanggal_lahir ? \Carbon\Carbon::parse($selectedUser->karyawan->tanggal_lahir)->format('d F Y') : 'N/A' }}
                                                    </dd>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($selectedUser->pengurus)
                                    <!-- Data Pengurus -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                            <div
                                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                                <h4 class="font-semibold text-gray-900 dark:text-white">Data Kepengurusan</h4>
                                            </div>
                                            <div class="p-4 space-y-3">
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        HP
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->pengurus->hp ?? 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Tanggal Masuk
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->pengurus->tanggal_masuk ? \Carbon\Carbon::parse($selectedUser->pengurus->tanggal_masuk)->format('d F Y') : 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Tanggal Keluar
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->pengurus->tanggal_keluar ? \Carbon\Carbon::parse($selectedUser->pengurus->tanggal_keluar)->format('d F Y') : 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        <span
                                                            class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                                    {{ $selectedUser->pengurus->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $selectedUser->pengurus->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                        </span>
                                                    </dd>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                            <div
                                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                                <h4 class="font-semibold text-gray-900 dark:text-white">Informasi Pribadi</h4>
                                            </div>
                                            <div class="p-4 space-y-3">
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Jenis Kelamin
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->pengurus->jenis_kelamin ?? 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Tempat Lahir
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->pengurus->tempat_lahir ?? 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div>
                                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Tanggal Lahir
                                                    </dt>
                                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ $selectedUser->pengurus->tanggal_lahir ? \Carbon\Carbon::parse($selectedUser->pengurus->tanggal_lahir)->format('d F Y') : 'N/A' }}
                                                    </dd>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alamat -->
                                    @if ($selectedUser->pengurus->alamat)
                                        <div
                                            class="mt-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                            <div
                                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                                <h4 class="font-semibold text-gray-900 dark:text-white">Alamat</h4>
                                            </div>
                                            <div class="p-4">
                                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                                    {{ $selectedUser->pengurus->alamat }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <!-- Log Informasi -->
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
                                                {{ $selectedUser->created_at ? \Carbon\Carbon::parse($selectedUser->created_at)->format('d F Y H:i') : 'N/A' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Edited
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-600 dark:text-gray-100">
                                                {{ $selectedUser->updated_at ? \Carbon\Carbon::parse($selectedUser->updated_at)->format('d F Y H:i') : 'N/A' }}
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

    <!-- Assign Roles Modal -->
    @if ($showModalRoles)
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                    <!-- Header -->
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900">
                                Assign Roles to User
                            </h3>
                            <button wire:click="closeModalRoles"
                                class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <!-- User Info -->
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm text-gray-600">
                                    Assigning roles to: <span class="font-semibold text-gray-900">{{ optional(\App\Models\User::find($selectedUserId))->name ?? 'User' }}</span>
                                </p>
                            </div>

                            <!-- Roles List -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-700">Available Roles</label>
                                <div class="space-y-2 max-h-96 overflow-y-auto">
                                    @forelse($roles as $role)
                                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                                            <input type="checkbox" 
                                                class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                                                wire:model.live="selectedRoles"
                                                value="{{ $role->id }}">
                                            <div class="ml-3 flex-1">
                                                <span class="font-medium text-gray-900">
                                                    {{ $role->name }}
                                                </span>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $role->permissions_count ?? $role->permissions()->count() }} permissions
                                                </p>
                                            </div>
                                            @if (is_array($selectedRoles) && in_array($role->id, $selectedRoles))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Selected
                                                </span>
                                            @endif
                                        </label>
                                    @empty
                                        <div class="text-center py-6">
                                            <p class="text-gray-500 text-sm">No roles available</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Current Roles Display -->
                            @if(is_array($selectedRoles) && !empty($selectedRoles))
                                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                    <p class="text-sm text-gray-700 mb-2">
                                        <span class="font-semibold">Selected Roles ({{ count($selectedRoles) }}):</span>
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($selectedRoles as $roleId)
                                            @php
                                                $role = $roles->find($roleId);
                                            @endphp
                                            @if($role)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $role->name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-500">No roles selected yet</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        <div class="flex gap-3 justify-end">
                            <button type="button" wire:click="closeModalRoles"
                                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-500/20 transition-all">
                                Cancel
                            </button>
                            <button type="submit" wire:click="saveRoles"
                                class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                <span wire:loading.remove wire:target="saveRoles">
                                    Assign Roles
                                </span>
                                <span wire:loading wire:target="saveRoles" class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 2v4m0 12v4m8-8h-4M6 12H2m15.364-6.364l-2.828 2.828M9.464 16.536l-2.828 2.828m9.192-9.192l-2.828 2.828M6.464 6.464L3.636 3.636">
                                        </path>
                                    </svg>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
