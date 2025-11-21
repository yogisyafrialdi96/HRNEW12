<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perjanjian Kerja - {{ $kontrak->nomor_kontrak }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 3px 0 0 0;
            font-size: 11pt;
            font-weight: normal;
        }
        .nomor {
            text-align: center;
            margin: 12px 0;
            font-weight: bold;
            font-size: 10pt;
        }
        .content {
            text-align: justify;
        }
        .content p {
            margin: 8px 0;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 12px;
            margin-bottom: 8px;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 10pt;
        }
        td {
            padding: 5px;
            vertical-align: top;
        }
        .label {
            width: 90px;
            font-weight: bold;
        }
        .separator {
            width: 12px;
            text-align: center;
        }
        ol, ul {
            margin: 8px 0;
            padding-left: 25px;
            font-size: 10pt;
        }
        li {
            margin: 3px 0;
            text-align: justify;
        }
        .signature-section {
            margin-top: 25px;
            display: table;
            width: 100%;
            font-size: 10pt;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 10px;
        }
        .signature-space {
            height: 45px;
            margin: 15px 0 3px 0;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 3px;
        }
        .footer {
            margin-top: 15px;
            font-size: 8pt;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $kontrak->imagePath }}" style="width: 100%; max-width: 600px;">
        <h1>PERJANJIAN KERJA</h1>
        <h2>{{ $kontrak->kontrak->nama_kontrak }}</h2>
    </div>

    <div class="nomor">
        Nomor: {{ $kontrak->nomor_kontrak }}
    </div>

    <div class="content">
        <p>
            Pada hari ini, {{ \Carbon\Carbon::parse($kontrak->tglmulai_kontrak)->isoFormat('dddd, D MMMM Y') }}, 
            yang bertanda tangan di bawah ini:
        </p>

        <div class="section-title">1. PIHAK-PIHAK</div>

        <table style="margin-bottom: 8px;">
            <tr>
                <td colspan="3" style="font-weight: bold;">Pihak Pertama (Pemberi Kerja):</td>
            </tr>
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td>{{ config('app.name', 'YAYASAN YKPI') }}</td>
            </tr>
            <tr>
                <td class="label">Mewakili</td>
                <td class="separator">:</td>
                <td>
                    @if($kontrak->approved_2)
                        @php
                            $pengurus = \App\Models\Yayasan\Pengurus::find($kontrak->approved_2);
                        @endphp
                        {{ $pengurus->nama_pengurus ?? '[Nama Pejabat]' }}
                    @else
                        [Nama Pejabat]
                    @endif
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td colspan="3" style="font-weight: bold;">Pihak Kedua (Pekerja):</td>
            </tr>
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->karyawan->full_name }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->karyawan->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->karyawan->alamat ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">2. JENIS PEKERJAAN</div>
        <table>
            <tr>
                <td class="label">Jabatan</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->jabatan->nama_jabatan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Unit/Divisi</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->unit->unit ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Golongan</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->golongan->nama_golongan ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">3. MASA KERJA DAN UPAH</div>
        <table>
            <tr>
                <td class="label">Mulai Kerja</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($kontrak->tglmulai_kontrak)->format('d F Y') }}</td>
            </tr>
            @if($kontrak->tglselesai_kontrak)
            <tr>
                <td class="label">Berakhir</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($kontrak->tglselesai_kontrak)->format('d F Y') }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Status</td>
                <td class="separator">:</td>
                <td>{{ ucfirst($kontrak->status) }}</td>
            </tr>
        </table>

        <div class="section-title">4. KOMPENSASI</div>
        <table>
            @if($kontrak->gaji_pokok)
            <tr>
                <td class="label">Gaji Pokok</td>
                <td class="separator">:</td>
                <td>Rp {{ number_format($kontrak->gaji_pokok, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($kontrak->gaji_paket)
            <tr>
                <td class="label">Gaji Paket</td>
                <td class="separator">:</td>
                <td>Rp {{ number_format($kontrak->gaji_paket, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($kontrak->transport)
            <tr>
                <td class="label">Tunjangan Transport</td>
                <td class="separator">:</td>
                <td>Rp {{ number_format($kontrak->transport, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>

        <div class="section-title">5. HAK DAN KEWAJIBAN</div>
        <p style="margin: 5px 0;"><strong>Hak Pekerja:</strong></p>
        <ul style="margin: 5px 0;">
            <li>Menerima upah sesuai kesepakatan tepat waktu</li>
            <li>Perlindungan keselamatan dan kesehatan kerja</li>
            <li>Perlakuan yang adil dan tanpa diskriminasi</li>
        </ul>

        <p style="margin: 5px 0;"><strong>Kewajiban Pekerja:</strong></p>
        <ul style="margin: 5px 0;">
            <li>Melaksanakan pekerjaan dengan baik dan bertanggung jawab</li>
            <li>Mematuhi peraturan dan tata tertib perusahaan/yayasan</li>
            <li>Hadir tepat waktu sesuai jadwal kerja</li>
            <li>Menjaga kerahasiaan informasi perusahaan/yayasan</li>
        </ul>

        <div class="section-title">6. PEMUTUSAN HUBUNGAN KERJA</div>
        <ul>
            <li>Perjanjian berakhir pada tanggal yang telah ditetapkan atau sesuai kebutuhan bisnis</li>
            <li>Salah satu pihak dapat mengakhiri hubungan kerja dengan pemberitahuan tertulis minimal 14 hari</li>
            <li>Pekerja harus mengembalikan semua barang/dokumen milik perusahaan/yayasan</li>
        </ul>

        @if($kontrak->catatan)
        <div class="section-title">7. CATATAN KHUSUS</div>
        <p>{{ $kontrak->catatan }}</p>
        @endif

        <p style="margin-top: 12px;">
            Demikian perjanjian kerja ini dibuat dalam keadaan sadar dan tanpa ada paksaan dari pihak manapun, 
            untuk dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p style="margin: 0;">Pemberi Kerja</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $kontrak->approved2->name ?? '[Nama Pejabat]' }}</div>
        </div>
        <div class="signature-box">
            <p style="margin: 0;">Pekerja</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $kontrak->karyawan->full_name }}</div>
        </div>
    </div>

    <div class="footer">
        <p style="margin: 3px 0;">Perjanjian Kerja Resmi</p>
        <p style="margin: 3px 0;">Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>
</body>
</html>
