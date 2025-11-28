<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GenerateKaryawanTemplate extends Command
{
    protected $signature = 'generate:karyawan-template';
    protected $description = 'Generate Excel template for Karyawan import';

    public function handle()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Karyawan');

            // Define columns
            $columns = [
                'NIP',
                'Full Name',
                'Email',
                'Gender',
                'Pndk Akhir',
                'Status Pegawai',
                'Status Kawin',
                'Golongan',
                'HP',
                'WhatsApp',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Agama',
                'Blood Type',
                'NIK',
                'NPWP',
                'Alamat KTP',
                'Tgl Masuk',
                'Jenis Karyawan',
                'Tgl Karyawan Tetap'
            ];

            // Add headers
            for ($col = 1; $col <= count($columns); $col++) {
                $sheet->setCellValueByColumnAndRow($col, 1, $columns[$col - 1]);
            }

            // Set column widths
            $widths = [12, 20, 25, 12, 12, 18, 15, 15, 15, 15, 15, 15, 12, 12, 18, 18, 25, 15, 18, 18];
            for ($col = 1; $col <= count($widths); $col++) {
                $sheet->getColumnDimensionByColumn($col)->setWidth($widths[$col - 1]);
            }

            // Add sample data
            $sampleData = [
                ['12345', 'John Doe', 'john.doe@example.com', 'laki-laki', 'S1', '1', 'menikah', '1', '08123456789', '08123456789', 'Jakarta', '01/01/1990', 'Islam', 'A', '1234567890123456', '12.345.678.9-123.456', 'Jl. Merdeka No. 1', '01/01/2020', 'Guru', '15/01/2022'],
                ['12346', 'Jane Smith', 'jane.smith@example.com', 'perempuan', 'S1', '1', 'lajang', '2', '08198765432', '08198765432', 'Bandung', '15/05/1992', 'Kristen', 'B', '9876543210987654', '98.765.432.1-234.567', 'Jl. Sudirman No. 25', '15/06/2021', 'Pegawai', ''],
                ['12347', 'Ahmad Hassan', 'ahmad.hassan@example.com', 'laki-laki', 'S1', '1', 'cerai', '3', '08111111111', '08111111111', 'Surabaya', '20/03/1988', 'Islam', 'AB', '1111111111111111', '11.111.111.1-111.111', 'Jl. Ahmad Yani No. 50', '01/09/2022', 'Pegawai', ''],
            ];

            // Add data rows
            for ($row = 0; $row < count($sampleData); $row++) {
                for ($col = 0; $col < count($sampleData[$row]); $col++) {
                    $sheet->setCellValueByColumnAndRow($col + 1, $row + 2, $sampleData[$row][$col]);
                }
            }

            // Freeze header row
            $sheet->freezePane('A2');

            // Create instructions sheet
            $instructionSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Instruksi');
            $spreadsheet->addSheet($instructionSheet);
            $instructionSheet->getColumnDimensionByColumn(1)->setWidth(120);

            $instructions = [
                'PETUNJUK IMPORT KARYAWAN',
                '',
                'KOLOM YANG DIPERLUKAN:',
                'NIP (wajib) - Nomor Identitas Pegawai, harus unik dan tidak duplikat',
                'Full Name (wajib) - Nama lengkap karyawan',
                'Email (opsional) - Email karyawan untuk akun login otomatis',
                'Gender - Laki-laki atau Perempuan (bisa M/F)',
                'Pndk Akhir (opsional, default S1) - SD, SMP, SMA, D1, D2, D3, D4, S1, S2, S3',
                'Status Pegawai - Harus sesuai database (Tetap, Kontrak, PHL, dll)',
                'Status Kawin - Single, Menikah, Cerai, Janda/Duda',
                'Golongan - Harus sesuai database (Golongan IIa, IIb, IIc, dll)',
                'HP / WhatsApp - Nomor telepon',
                'Tempat Lahir - Kota/tempat lahir',
                'Tanggal Lahir - Format: dd/mm/yyyy, dd-mm-yyyy, atau yyyy-mm-dd',
                'Agama - Islam, Kristen, Katholik, Hindu, Buddha, Konghucu',
                'Blood Type - A, B, O, AB, atau tidak diketahui',
                'NIK - Nomor Induk Kependudukan (16 digit)',
                'NPWP - Nomor Pokok Wajib Pajak',
                'Alamat KTP - Alamat sesuai KTP',
                'Tgl Masuk (wajib) - Tanggal mulai kerja, Format: dd/mm/yyyy',
                'Jenis Karyawan - PNS, PPPK, Kontrak, atau PHL',
                'Tgl Karyawan Tetap - Tanggal menjadi karyawan tetap (opsional)',
                '',
                'CATATAN PENTING:',
                '1. Format tanggal yang didukung: dd/mm/yyyy, dd-mm-yyyy, atau yyyy-mm-dd',
                '2. NIP harus unik dan tidak boleh ada duplikat di database',
                '3. Master data (Status Pegawai, Golongan) harus ada di database terlebih dahulu',
                '4. Jangan menghapus atau mengubah baris header (baris pertama)',
                '5. Gunakan nilai yang sesuai dengan master data di sistem',
                '6. File maksimal 5MB',
                '7. Format file: .xlsx, .xls, atau .csv',
                '8. Jika ada error saat import, sistem akan menampilkan baris mana yang gagal',
                '9. Jika email diberikan, sistem akan membuat akun login otomatis',
                '10. Pndk Akhir akan otomatis jadi S1 jika kosong',
            ];

            for ($i = 0; $i < count($instructions); $i++) {
                $instructionSheet->setCellValueByColumnAndRow(1, $i + 1, $instructions[$i]);
            }

            // Write file
            $path = public_path('template_import_karyawan.xlsx');
            $writer = new Xlsx($spreadsheet);
            $writer->save($path);

            $this->info('✓ Template berhasil dibuat: ' . $path);
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
