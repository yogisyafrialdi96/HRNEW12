<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Employee\Karyawan;
use App\Models\Employee\KaryawanJabatan;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $karyawan = $user->karyawan;

        // Get user stats
        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'roles' => $user->roles->pluck('name')->toArray(),
        ];

        // Get karyawan data if exists
        $karyawanData = null;
        if ($karyawan) {
            $karyawanData = [
                'id' => $karyawan->id,
                'full_name' => $karyawan->full_name,
                'nip' => $karyawan->nip,
                'gender' => $karyawan->gender,
                'email' => $karyawan->user->email ?? null,
                'hp' => $karyawan->hp,
                'whatsapp' => $karyawan->whatsapp,
                'foto' => $karyawan->foto,
                'tempat_lahir' => $karyawan->tempat_lahir,
                'tanggal_lahir' => $karyawan->tanggal_lahir,
                'alamat' => $karyawan->alamat_ktp,
                'agama' => $karyawan->agama,
                'status_kawin' => $karyawan->status_kawin,
                'tgl_masuk' => $karyawan->tgl_masuk,
                'jenis_karyawan' => $karyawan->jenis_karyawan,
                'status' => $karyawan->statusPegawai->nama_status ?? 'Unknown',
            ];

            // Get current jabatan if exists
            $currentJabatan = $karyawan->activeJabatan;
            if ($currentJabatan) {
                $karyawanData['jabatan'] = $currentJabatan->jabatan->nama_jabatan ?? null;
                $karyawanData['unit'] = $currentJabatan->unit->unit ?? null;
            }
        }

        return view('livewire.dashboard', compact('userData', 'karyawanData'));
    }
}

