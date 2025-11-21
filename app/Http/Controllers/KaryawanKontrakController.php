<?php

namespace App\Http\Controllers;

use App\Models\Employee\KaryawanKontrak;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KaryawanKontrakController extends Controller
{
    /**
     * Cetak kontrak karyawan ke PDF dengan template sesuai jenis kontrak
     */
    public function cetakKontrak($id)
    {
        try {
            // Ambil data kontrak dengan semua relasi
            $kontrak = KaryawanKontrak::with([
                'karyawan',
                'kontrak',
                'golongan',
                'unit',
                'jabatan',
                'approved1',
                'approved2',
                'createdBy',
                'updatedBy'
            ])->findOrFail($id);

            // Siapkan path gambar untuk DomPDF (convert backslash ke forward slash)
            $imagePath = public_path('assets/img/kopsk.png');
            $imagePath = str_replace('\\', '/', $imagePath);
            
            // Pass ke view
            $kontrak->imagePath = $imagePath;

            $imageBismillah = public_path('assets/img/bismillah.png');
            $imageBismillah = str_replace('\\', '/', $imageBismillah);
            
            // Pass ke view
            $kontrak->imageBismillah = $imageBismillah;

            // Pilih view template berdasarkan jenis kontrak
            $view = $this->selectTemplate($kontrak->kontrak->nama_kontrak);

            // Generate PDF
            $pdf = Pdf::loadView($view, compact('kontrak'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => false,
                    'defaultFont' => 'sans-serif',
                    'debugCss' => false,
                    'debugKeepTemp' => false,
                    'chroot' => base_path(),
                    'enable_local' => true,
                    'enable_remote' => false,
                ]);

            // Buat nama file (sanitize nomor_kontrak untuk menghilangkan "/" dan "\")
            $safeNomorKontrak = str_replace(['/', '\\', ' '], '_', $kontrak->nomor_kontrak);
            $filename = 'Kontrak_' . 
                        $safeNomorKontrak . '_' . 
                        str_replace(' ', '_', $kontrak->karyawan->full_name) . '_' .
                        date('YmdHis') . 
                        '.pdf';

            // Stream PDF (buka di browser)
            return $pdf->stream($filename);

        } catch (\Exception $e) {
            // Log error
            Log::error('Error cetak kontrak: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Redirect dengan error message
            return redirect()
                ->back()
                ->with('error', 'Gagal mencetak kontrak: ' . $e->getMessage());
        }
    }

    /**
     * Pilih template view berdasarkan jenis kontrak
     * 
     * Jenis Kontrak:
     * 1. TETAP - Pekerja Tetap Yayasan
     * 2. PKWT - Pekerja Kontrak Waktu Tertentu
     * 3. PHL PERJAM - Pekerja Harian Lepas/Jam Ngajar
     * 4. PHL Honor Paket - Pekerja Harian Lepas Honor Paket Pengganti Guru
     * 5. PHL Operator BOS - Pekerja Harian Lepas Operator BOS
     * 6. PHL 40 JAM - Pekerja Harian Lepas Paket 40 jam seminggu
     * 7. PHL PENGASUH TPA - Pekerja Harian Lepas Pengasuh TPA
     * 8. PHL PETUGAS TAMAN - Pekerja Harian Lepas Petugas Taman
     */
    private function selectTemplate($namaKontrak)
    {
        $namaKontrak = strtoupper(trim($namaKontrak));

        return match ($namaKontrak) {
            'TETAP' => 'pdf.kontrak.tetap',
            'PKWT' => 'pdf.kontrak.pkwt',
            'PHL PERJAM' => 'pdf.kontrak.phl-perjam',
            'PHL HONOR PAKET' => 'pdf.kontrak.phl-honor-paket',
            'PHL OPERATOR BOS' => 'pdf.kontrak.phl-operator-bos',
            'PHL 40 JAM' => 'pdf.kontrak.phl-40-jam',
            'PHL PENGASUH TPA' => 'pdf.kontrak.phl-pengasuh-tpa',
            'PHL PETUGAS TAMAN' => 'pdf.kontrak.phl-petugas-taman',
            default => 'pdf.kontrak.default', // Fallback jika jenis kontrak tidak ditemukan
        };
    }
}

