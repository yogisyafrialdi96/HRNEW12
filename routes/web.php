<?php

use App\Http\Controllers\KaryawanKontrakController;
use App\Livewire\Admin;
use App\Livewire\Admin\Karyawan\KaryawanForm;
use App\Livewire\Admin\Karyawan\KaryawanProfile;
use App\Livewire\Admin\Karyawan\KaryawanTable;
use App\Http\Controllers\KontrakPrintController;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Permissions\PermissionIndex;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    // Only admin bisa access
    Route::middleware('permission:permissions.view')->group(function () {
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', PermissionIndex::class)->name('index');
        });
    });

    Route::middleware('permission:roles.view')->group(function () {
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', RoleIndex::class)->name('index');
        });
    });

    Route::middleware('permission:users.view')->group(function () {
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', Admin\Users\UserIndex::class)->name('index');
        });
    });

    Route::middleware('permission:dashboard_admin.view')->group(function () {
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', Dashboard::class)->name('index');
        });
    });

    Route::middleware('permission:master_data.view')->group(function () {
        Route::prefix('department')->name('department.')->group(function () {
            Route::get('/', Admin\Master\Department\Index::class)->name('index');
        });
        Route::prefix('unit')->name('unit.')->group(function () {
            Route::get('/', Admin\Master\Unit\Index::class)->name('index');
        });
        Route::prefix('jabatan')->name('jabatan.')->group(function () {
            Route::get('/', Admin\Master\Jabatan\Index::class)->name('index');
        });
        Route::prefix('mapel')->name('mapel.')->group(function () {
            Route::get('/', Admin\Master\Mapel\Index::class)->name('index');
        });
        Route::prefix('status-kawin')->name('status-kawin.')->group(function () {
            Route::get('/', Admin\Master\StatusKawin\Index::class)->name('index');
        });
        Route::prefix('status-kontrak')->name('status-kontrak.')->group(function () {
            Route::get('/', Admin\Master\JenisKontrak\Index::class)->name('index');
        });
        Route::prefix('status-golongan')->name('status-golongan.')->group(function () {
            Route::get('/', Admin\Master\Golongan\Index::class)->name('index');
        });
        Route::prefix('status-pegawai')->name('status-pegawai.')->group(function () {
            Route::get('/', Admin\Master\Statuspegawai\Index::class)->name('index');
        });
        Route::prefix('tahun-ajaran')->name('tahun-ajaran.')->group(function () {
            Route::get('/', Admin\Master\TahunAjaran\Index::class)->name('index');
        });
    });

    Route::middleware('permission:pengurus.view')->group(function () {
        Route::prefix('pengurus')->name('pengurus.')->group(function () {
            Route::get('/', Admin\Yayasan\Pengurus\Index::class)->name('index');
        });
    });

    Route::middleware('permission:karyawan.view_list')->group(function () {
        Route::prefix('karyawan')->name('karyawan.')->group(function () {
            Route::get('/', KaryawanTable::class)->name('index');
            Route::get('/{karyawan}/edit/{tab?}', KaryawanProfile::class)->name('edit');
        });
    });

    Route::middleware('permission:kontrak_kerja.view')->group(function () {
        Route::prefix('kontrak')->name('kontrak.')->group(function () {
            Route::get('/', Admin\Karyawan\Kontrak\Index::class)->name('index');
            // Route untuk cetak PDF (stream/buka di browser)
            Route::get('/cetak/{id}', [KaryawanKontrakController::class, 'cetakKontrak'])->name('cetak');
        });
    });

    Route::middleware('permission:masakerja.view')->group(function () {
        Route::prefix('masakerja')->name('masakerja.')->group(function () {
            Route::get('/', Admin\Karyawan\Masakerja\Index::class)->name('index');
        });
    });
});

// Staff Routes - untuk karyawan mengakses profile mereka sendiri
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('permission:karyawan.view')->group(function () {
        Route::prefix('karyawan')->name('karyawan.')->group(function () {
            Route::get('{karyawan}/profile/{tab?}', KaryawanProfile::class)->name('profile');
        });
    });
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
