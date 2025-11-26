<?php

namespace App\Exports;

use App\Models\Employee\Karyawan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KaryawanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $statusFilter = '';
    protected $unitFilter = '';
    protected $jabatanFilter = '';
    protected $tgl_masuk_dari = '';
    protected $tgl_masuk_sampai = '';

    /**
     * Constructor to receive filter parameters
     */
    public function __construct(
        $statusFilter = '',
        $unitFilter = '',
        $jabatanFilter = '',
        $tgl_masuk_dari = '',
        $tgl_masuk_sampai = ''
    ) {
        $this->statusFilter = $statusFilter;
        $this->unitFilter = $unitFilter;
        $this->jabatanFilter = $jabatanFilter;
        $this->tgl_masuk_dari = $tgl_masuk_dari;
        $this->tgl_masuk_sampai = $tgl_masuk_sampai;
    }

    /**
     * Build the query with applied filters
     */
    public function query()
    {
        $query = Karyawan::query()
            ->with([
                'user',
                'statusPegawai',
                'statusKawin',
                'golongan',
                'provKtp',
                'kabKtp',
                'kecKtp',
                'desaKtp',
                'provDom',
                'kabDom',
                'kecDom',
                'desaDom',
                'contracts',
                'pendidikan',
                'activeJabatan' => function ($q) {
                    $q->with(['jabatan', 'unit']);
                }
            ]);

        // Filter by status pegawai
        if ($this->statusFilter !== '') {
            $query->where('statuskaryawan_id', $this->statusFilter);
        }

        // Filter by jabatan aktif
        if ($this->jabatanFilter !== '') {
            $query->whereHas('activeJabatan.jabatan', function ($sub) {
                $sub->where('id', $this->jabatanFilter);
            });
        }

        // Filter by unit aktif (excluding YAYASAN)
        if ($this->unitFilter !== '') {
            $query->whereHas('activeJabatan.unit', function ($sub) {
                $sub->where('id', $this->unitFilter)
                    ->whereHas('department', function ($dept) {
                        $dept->where('department', '!=', 'YAYASAN');
                    });
            });
        }

        // Filter by date range
        if ($this->tgl_masuk_dari !== '') {
            $query->whereDate('tgl_masuk', '>=', $this->tgl_masuk_dari);
        }

        if ($this->tgl_masuk_sampai !== '') {
            $query->whereDate('tgl_masuk', '<=', $this->tgl_masuk_sampai);
        }

        return $query->orderBy('full_name', 'asc');
    }

    /**
     * Define the headings for the spreadsheet
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIP',
            'Email',
            'HP/WhatsApp',
            'Gender',
            'Jabatan',
            'Unit',
            'Status Pegawai',
            'Jenis Kontrak Aktif',
            'Tanggal Masuk',
            'Panggilan',
            'Gelar Depan',
            'Gelar Belakang',
            'Jurusan',
            'Nama Sekolah/Institusi',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Pendidikan Akhir',
            'Agama',
            'Status Kawin',
            'Golongan Darah',
            'NIK',
            'NKK',
            'NPWP',
            'Alamat KTP',
            'RT KTP',
            'RW KTP',
            'Prov KTP',
            'Kab KTP',
            'Kec KTP',
            'Desa KTP',
            'Alamat Domisili',
            'RT Domisili',
            'RW Domisili',
            'Prov Domisili',
            'Kab Domisili',
            'Kec Domisili',
            'Desa Domisili',
            'Contact Emergency',
            'No Contact Emergency',
            'Jenis Karyawan',
            'Tgl Karyawan Tetap',
            'Tgl Berhenti',
        ];
    }

    /**
     * Map the data to be exported
     */
    public function map($karyawan): array
    {
        static $row = 0;
        $row++;

        // Get wilayah data
        $provKtp = $karyawan->provKtp?->name ?? '-';
        $kabKtp = $karyawan->kabKtp?->name ?? '-';
        $kecKtp = $karyawan->kecKtp?->name ?? '-';
        $desaKtp = $karyawan->desaKtp?->name ?? '-';
        
        $provDom = $karyawan->provDom?->name ?? '-';
        $kabDom = $karyawan->kabDom?->name ?? '-';
        $kecDom = $karyawan->kecDom?->name ?? '-';
        $desaDom = $karyawan->desaDom?->name ?? '-';

        // Get status kawin
        $statusKawin = $karyawan->statusKawin?->nama_status ?? '-';

        // Get golongan
        $golongan = $karyawan->golongan?->nama ?? '-';

        return [
            $row,
            $karyawan->full_name ?? '-',
            $karyawan->nip ?? '-',
            $karyawan->user?->email ?? '-',
            $karyawan->hp ?? $karyawan->whatsapp ?? '-',
            ucfirst($karyawan->gender ?? '-'),
            $karyawan->activeJabatan?->jabatan?->nama_jabatan ?? '-',
            $karyawan->activeJabatan?->unit?->unit ?? '-',
            $karyawan->statusPegawai?->nama_status ?? '-',
            $karyawan->contracts->last()?->kontrak?->nama_kontrak ?? '-',
            \Carbon\Carbon::parse($karyawan->tgl_masuk)->format('d-m-Y') ?? '-',
            $karyawan->panggilan ?? '-',
            $karyawan->gelar_depan ?? '-',
            $karyawan->gelar_belakang ?? '-',
            $karyawan->pendidikan->last()?->jurusan ?? '-',
            $karyawan->pendidikan->last()?->nama_sekolah ?? '-',
            $karyawan->tempat_lahir ?? '-',
            $karyawan->tanggal_lahir ? \Carbon\Carbon::parse($karyawan->tanggal_lahir)->format('d-m-Y') : '-',
            $karyawan->pndk_akhir ?? '-',
            $karyawan->agama ?? '-',
            $statusKawin,
            $karyawan->blood_type ?? '-',
            $karyawan->nik ?? '-',
            $karyawan->nkk ?? '-',
            $karyawan->npwp ?? '-',
            $karyawan->alamat_ktp ?? '-',
            $karyawan->rt_ktp ?? '-',
            $karyawan->rw_ktp ?? '-',
            $provKtp,
            $kabKtp,
            $kecKtp,
            $desaKtp,
            $karyawan->alamat_dom ?? '-',
            $karyawan->rt_dom ?? '-',
            $karyawan->rw_dom ?? '-',
            $provDom,
            $kabDom,
            $kecDom,
            $desaDom,
            $karyawan->emergency_contact_name ?? '-',
            $karyawan->emergency_contact_phone ?? '-',
            $karyawan->jenis_karyawan ?? '-',
            $karyawan->tgl_karyawan_tetap ? \Carbon\Carbon::parse($karyawan->tgl_karyawan_tetap)->format('d-m-Y') : '-',
            $karyawan->tgl_berhenti ? \Carbon\Carbon::parse($karyawan->tgl_berhenti)->format('d-m-Y') : '-',
        ];
    }

    /**
     * Style the spreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6'], // Blue background
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Data rows styling
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $sheet->getStyle("A{$row}:AO{$row}")->applyFromArray([
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                ],
            ]);

            // Alternate row colors
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:AO{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F3F4F6'],
                    ],
                ]);
            }
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(15);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(15);
        $sheet->getColumnDimension('Q')->setWidth(12);
        $sheet->getColumnDimension('R')->setWidth(12);
        $sheet->getColumnDimension('S')->setWidth(12);
        $sheet->getColumnDimension('T')->setWidth(12);
        $sheet->getColumnDimension('U')->setWidth(12);
        $sheet->getColumnDimension('V')->setWidth(12);
        $sheet->getColumnDimension('W')->setWidth(15);
        $sheet->getColumnDimension('X')->setWidth(8);
        $sheet->getColumnDimension('Y')->setWidth(8);
        $sheet->getColumnDimension('Z')->setWidth(12);
        $sheet->getColumnDimension('AA')->setWidth(12);
        $sheet->getColumnDimension('AB')->setWidth(12);
        $sheet->getColumnDimension('AC')->setWidth(12);
        $sheet->getColumnDimension('AD')->setWidth(15);
        $sheet->getColumnDimension('AE')->setWidth(8);
        $sheet->getColumnDimension('AF')->setWidth(8);
        $sheet->getColumnDimension('AG')->setWidth(12);
        $sheet->getColumnDimension('AH')->setWidth(12);
        $sheet->getColumnDimension('AI')->setWidth(12);
        $sheet->getColumnDimension('AJ')->setWidth(12);
        $sheet->getColumnDimension('AK')->setWidth(18);
        $sheet->getColumnDimension('AL')->setWidth(18);
        $sheet->getColumnDimension('AM')->setWidth(15);
        $sheet->getColumnDimension('AN')->setWidth(15);
        $sheet->getColumnDimension('AO')->setWidth(15);

        // Freeze header row
        $sheet->freezePane('A2');

        return [];
    }
}
