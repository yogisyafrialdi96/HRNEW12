<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                @can('dashboard.view')
                    <flux:navlist.group :heading="__('Platform')" class="grid">
                        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                        @if (auth()->user()->karyawan)
                            <flux:navlist.item icon="home" :href="route('karyawan.profile', auth()->user()->karyawan->id)" :current="request()->routeIs('karyawan.profile', auth()->user()->karyawan->id)" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
                        @endif
                    </flux:navlist.group>
                @endcan

                @can('master_data.view')
                    <flux:navlist.group :heading="__('Master Data')" expandable
                        :expanded="request()->routeIs('companies.*') || request()->routeIs('department.*') || request()->routeIs('unit.*') || request()->routeIs('jabatan.*') || request()->routeIs('mapel.*') || request()->routeIs('status-kawin.*') || request()->routeIs('status-pegawai.*') || request()->routeIs('status-golongan.*') || request()->routeIs('status-kontrak.*') || request()->routeIs('tahun-ajaran.*')">
                        <flux:navlist.item :href="route('department.index')" :current="request()->routeIs('department.*')" wire:navigate>Departments</flux:navlist.item>
                        <flux:navlist.item :href="route('unit.index')" :current="request()->routeIs('unit.*')" wire:navigate>Unit</flux:navlist.item>
                        <flux:navlist.item :href="route('jabatan.index')" :current="request()->routeIs('jabatan.*')" wire:navigate>Jabatan</flux:navlist.item>
                        <flux:navlist.item :href="route('mapel.index')" :current="request()->routeIs('mapel.*')" wire:navigate>Mapel</flux:navlist.item>
                        <flux:navlist.item :href="route('status-kawin.index')" :current="request()->routeIs('-kawin.*')" wire:navigate>Status Kawin</flux:navlist.item>
                        <flux:navlist.item :href="route('status-kontrak.index')" :current="request()->routeIs('status-kontrak.*')" wire:navigate>Status Kontrak</flux:navlist.item>
                        <flux:navlist.item :href="route('status-golongan.index')" :current="request()->routeIs('status-golongan.*')" wire:navigate>Status Golongan</flux:navlist.item>
                        <flux:navlist.item :href="route('status-pegawai.index')" :current="request()->routeIs('status-pegawai.*')" wire:navigate>Status Pegawai</flux:navlist.item>
                        <flux:navlist.item :href="route('tahun-ajaran.index')" :current="request()->routeIs('tahun-ajaran.*')" wire:navigate>Tahun Ajaran</flux:navlist.item>
                    </flux:navlist.group>
                @endcan

                @if (auth()->user()->can('dashboard_admin.view') || auth()->user()->can('pengurus.view') || auth()->user()->can('karyawan.view_list') || auth()->user()->can('kontrak_kerja.view') || auth()->user()->can('masakerja.view'))
                <flux:navlist.group :heading="__('Human Resources')" class="grid">
                    @can('dashboard_admin.view')
                        <flux:navlist.item icon="home" :href="route('dashboard.index')" :current="request()->routeIs('dashboard.*')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    @endcan
                    @can('pengurus.view')
                        <flux:navlist.item icon="user-group" :href="route('pengurus.index')" :current="request()->routeIs('pengurus.*')" wire:navigate>{{ __('Pengurus') }}</flux:navlist.item>
                    @endcan
                    @can('karyawan.view_list')
                        <flux:navlist.item icon="user-group" :href="route('karyawan.index')" :current="request()->routeIs('karyawan.*')" wire:navigate>{{ __('Karyawan') }}</flux:navlist.item>
                    @endcan
                    @can('kontrak_kerja.view')
                        <flux:navlist.item icon="document-text" :href="route('kontrak.index')" :current="request()->routeIs('kontrak.*')" wire:navigate>{{ __('Kontrak Kerja') }}</flux:navlist.item>
                    @endcan
                    @can('masakerja.view')
                        <flux:navlist.item icon="document-minus" :href="route('masakerja.index')" :current="request()->routeIs('masakerja.*')" wire:navigate>{{ __('Masa Kerja') }}</flux:navlist.item>
                    @endcan
                </flux:navlist.group>
                @endif

                @if (auth()->user()->can('users.view') || auth()->user()->can('roles.view') || auth()->user()->can('permissions.view'))
                    <flux:navlist.group :heading="__('Administration')" class="grid">
                        @can('users.view')
                            <flux:navlist.item icon="user-group" :href="route('users.index')" :current="request()->routeIs('users.*')" wire:navigate>{{ __('Users') }}</flux:navlist.item>
                        @endcan
                        @can('roles.view')
                            <flux:navlist.item icon="cog-6-tooth" :href="route('roles.index')" :current="request()->routeIs('roles.*')" wire:navigate>{{ __('Roles') }}</flux:navlist.item>
                        @endcan
                        @can('permissions.view')
                            <flux:navlist.item icon="cog-6-tooth" :href="route('permissions.index')" :current="request()->routeIs('permissions.*')" wire:navigate>{{ __('Permissions') }}</flux:navlist.item>
                        @endcan
                    </flux:navlist.group>
                @endif

                @if (auth()->user()->can('cuti.view') || auth()->user()->can('izin.view') || auth()->user()->can('cuti.approve') || auth()->user()->can('izin.approve'))
                    <flux:navlist.group :heading="__('Leave & Permission')" expandable
                        :expanded="request()->routeIs('cuti.*') || request()->routeIs('izin.*') || request()->routeIs('cuti-approval.*') || request()->routeIs('izin-approval.*')">
                        @can('cuti.view')
                            <flux:navlist.item icon="document-plus" :href="route('cuti.index')" :current="request()->routeIs('cuti.*')" wire:navigate>{{ __('Pengajuan Cuti') }}</flux:navlist.item>
                        @endcan
                        @can('izin.view')
                            <flux:navlist.item icon="document-plus" :href="route('izin.index')" :current="request()->routeIs('izin.*')" wire:navigate>{{ __('Pengajuan Izin') }}</flux:navlist.item>
                        @endcan
                        @can('cuti.approve')
                            <flux:navlist.item icon="document-check" :href="route('cuti-approval.index')" :current="request()->routeIs('cuti-approval.*')" wire:navigate>{{ __('Approval Cuti') }}</flux:navlist.item>
                        @endcan
                        @can('izin.approve')
                            <flux:navlist.item icon="document-check" :href="route('izin-approval.index')" :current="request()->routeIs('izin-approval.*')" wire:navigate>{{ __('Approval Izin') }}</flux:navlist.item>
                        @endcan
                    </flux:navlist.group>
                @endif

                @can('master_data.view')
                    <flux:navlist.group :heading="__('Leave & Permission Setup')" expandable
                        :expanded="request()->routeIs('setup.*')">
                        <flux:navlist.item icon="cog" :href="route('setup.cuti')" :current="request()->routeIs('setup.cuti')" wire:navigate>{{ __('Setup Cuti') }}</flux:navlist.item>
                        <flux:navlist.item icon="cog" :href="route('setup.izin')" :current="request()->routeIs('setup.izin')" wire:navigate>{{ __('Setup Izin') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar" :href="route('setup.libur')" :current="request()->routeIs('setup.libur')" wire:navigate>{{ __('Libur Nasional') }}</flux:navlist.item>
                        <flux:navlist.item icon="clock" :href="route('setup.jam-kerja')" :current="request()->routeIs('setup.jam-kerja')" wire:navigate>{{ __('Jam Kerja Unit') }}</flux:navlist.item>
                    </flux:navlist.group>
                @endcan

                @can('users.view')
                    <flux:navlist.group :heading="__('Approval Hierarchy')" expandable
                        :expanded="request()->routeIs('atasan.*')">
                        <flux:navlist.item icon="chart-bar" :href="route('atasan.users.index')" :current="request()->routeIs('atasan.users.*')" wire:navigate>{{ __('Atasan User') }}</flux:navlist.item>
                    </flux:navlist.group>
                @endcan
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        @if (auth()->user()->karyawan)
                            <flux:menu.item :href="route('karyawan.profile', auth()->user()->karyawan->id)" icon="user" wire:navigate>{{ __('My Profile') }}</flux:menu.item>
                        @endif
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        @if (auth()->user()->karyawan)
                            <flux:menu.item :href="route('karyawan.profile', auth()->user()->karyawan->id)" icon="user" wire:navigate>{{ __('My Profile') }}</flux:menu.item>
                        @endif
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
