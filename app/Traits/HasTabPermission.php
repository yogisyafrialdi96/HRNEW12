<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait HasTabPermission
{
    /**
     * Get the permission prefix for this tab
     * Override this in component class if needed
     * 
     * @return string
     */
    protected function getTabName(): string
    {
        // Infer from class name if not explicitly set
        $class = class_basename($this::class);
        $namespace = explode('\\', $this::class);
        
        // Find the tab name from namespace
        foreach ($namespace as $part) {
            if (in_array($part, ['Pendidikan', 'Organisasi', 'Pekerjaan', 'Keluarga', 'Bahasa', 'Sertifikasi', 'Pelatihan', 'Prestasi', 'Dokumen', 'Bank', 'Kontrak', 'Jabatan'])) {
                return strtolower($part);
            }
        }
        
        return strtolower($class);
    }

    /**
     * Check if user can view this tab
     * 
     * @return bool
     */
    public function canView(): bool
    {
        return auth()->user()->hasPermissionTo("karyawan_{$this->getTabName()}.view");
    }

    /**
     * Check if user can create in this tab
     * 
     * @return bool
     */
    public function canCreate(): bool
    {
        return auth()->user()->hasPermissionTo("karyawan_{$this->getTabName()}.create");
    }

    /**
     * Check if user can edit in this tab
     * 
     * @return bool
     */
    public function canEdit(): bool
    {
        return auth()->user()->hasPermissionTo("karyawan_{$this->getTabName()}.edit");
    }

    /**
     * Check if user can delete in this tab
     * 
     * @return bool
     */
    public function canDelete(): bool
    {
        return auth()->user()->hasPermissionTo("karyawan_{$this->getTabName()}.delete");
    }

    /**
     * Authorize view access - throw 403 if not authorized
     * 
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorizeView(): void
    {
        if (!$this->canView()) {
            abort(403, "Anda tidak memiliki akses untuk melihat {$this->getTabName()}.");
        }
    }

    /**
     * Authorize create action - throw ValidationException if not authorized
     * 
     * @return void
     * @throws ValidationException
     */
    public function authorizeCreate(): void
    {
        if (!$this->canCreate()) {
            throw ValidationException::withMessages([
                'error' => "Anda tidak memiliki akses untuk membuat data {$this->getTabName()}.",
            ]);
        }
    }

    /**
     * Authorize edit action - throw ValidationException if not authorized
     * 
     * @return void
     * @throws ValidationException
     */
    public function authorizeEdit(): void
    {
        if (!$this->canEdit()) {
            throw ValidationException::withMessages([
                'error' => "Anda tidak memiliki akses untuk mengedit {$this->getTabName()}.",
            ]);
        }
    }

    /**
     * Authorize delete action - throw ValidationException if not authorized
     * 
     * @return void
     * @throws ValidationException
     */
    public function authorizeDelete(): void
    {
        if (!$this->canDelete()) {
            throw ValidationException::withMessages([
                'error' => "Anda tidak memiliki akses untuk menghapus {$this->getTabName()}.",
            ]);
        }
    }
}
