<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keputusan - {{ $kontrak->nomor_kontrak }}</title>
    <style>
        @page {
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 0.5cm;
            margin-top: 0.5cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14pt;
            font-weight: normal;
        }
        .nomor-sk {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        .content p {
            margin: 10px 0;
        }
        .menimbang, .mengingat, .memutuskan {
            margin: 20px 0;
        }
        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        td {
            padding: 8px;
            vertical-align: top;
        }
        .label {
            width: 120px;
            font-weight: bold;
        }
        .separator {
            width: 15px;
            text-align: center;
        }
        ol, ul {
            margin: 10px 0;
            padding-left: 30px;
        }
        li {
            margin: 5px 0;
        }
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }
        .signature-space {
            height: 60px;
            margin: 20px 0;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 20px;
            font-size: 9pt;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ $kontrak->imagePath }}" style="width: 100%; max-width: 600px;">
        <h1>SURAT KEPUTUSAN</h1>
        <h2>Penetapan Pekerja Tetap</h2>
    </div>

    <div class="nomor-sk">
        Nomor: {{ $kontrak->nomor_kontrak }}
    </div>

    <div class="content">
        <div class="menimbang">
            <div class="section-title">MENIMBANG:</div>
            <ol>
                <li>Bahwa untuk kelancaran operasional dan kebutuhan organisasi, perlu ditetapkan tenaga kerja tetap;</li>
                <li>Bahwa calon pekerja telah melewati masa percobaan/kontrak dan dianggap layak menjadi pekerja tetap;</li>
                <li>Bahwa perlu diterbitkan Surat Keputusan ini sebagai dasar hukum pengangkatan pekerja tetap.</li>
            </ol>
        </div>

        <div class="mengingat">
            <div class="section-title">MENGINGAT:</div>
            <ol>
                <li>Peraturan Perburuhan dan Ketenagakerjaan yang berlaku;</li>
                <li>Peraturan internal yayasan/organisasi;</li>
                <li>Hasil evaluasi kinerja pekerja selama masa kontrak.</li>
            </ol>
        </div>

        <div class="memutuskan">
            <div class="section-title">MEMUTUSKAN:</div>
            <p style="text-align: center; font-weight: bold; margin-bottom: 15px;">Menetapkan:</p>

            <div class="section-title" style="margin-top: 10px;">Pasal 1: Pengangkatan Pekerja Tetap</div>
            <p>Dengan ini kami mengangkat:</p>
            
            <table>
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
                    <td class="label">Tempat/Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td>{{ $kontrak->karyawan->tempat_lahir ?? '-' }} / {{ $kontrak->karyawan->tanggal_lahir ? \Carbon\Carbon::parse($kontrak->karyawan->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td>{{ $kontrak->karyawan->jenis_kelamin ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat</td>
                    <td class="separator">:</td>
                    <td>{{ $kontrak->karyawan->alamat ?? '-' }}</td>
                </tr>
            </table>

            <p style="margin-top: 15px;"><strong>SEBAGAI PEKERJA TETAP</strong></p>

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
                <tr>
                    <td class="label">Terhitung Mulai</td>
                    <td class="separator">:</td>
                    <td>{{ \Carbon\Carbon::parse($kontrak->tglmulai_kontrak)->format('d F Y') }}</td>
                </tr>
            </table>

            <div class="section-title" style="margin-top: 20px;">Pasal 2: Gaji dan Tunjangan</div>
            <table>
                @if($kontrak->gaji_paket)
                <tr>
                    <td class="label">Gaji Paket</td>
                    <td class="separator">:</td>
                    <td>Rp {{ number_format($kontrak->gaji_paket, 0, ',', '.') }}/bulan</td>
                </tr>
                @endif
                @if($kontrak->gaji_pokok)
                <tr>
                    <td class="label">Gaji Pokok</td>
                    <td class="separator">:</td>
                    <td>Rp {{ number_format($kontrak->gaji_pokok, 0, ',', '.') }}/bulan</td>
                </tr>
                @endif
                @if($kontrak->transport)
                <tr>
                    <td class="label">Tunjangan Transport</td>
                    <td class="separator">:</td>
                    <td>Rp {{ number_format($kontrak->transport, 0, ',', '.') }}/bulan</td>
                </tr>
                @endif
            </table>

            <div class="section-title" style="margin-top: 20px;">Pasal 3: Ketentuan Umum</div>
            <ol>
                <li>Pekerja tetap berhak atas semua hak dan tunjangan sesuai dengan peraturan internal yayasan/organisasi;</li>
                <li>Pekerja tetap berkewajiban menjalankan tugas dengan baik dan disiplin sesuai dengan peraturan yang berlaku;</li>
                <li>Surat Keputusan ini berlaku mulai tanggal {{ \Carbon\Carbon::parse($kontrak->tglmulai_kontrak)->format('d F Y') }};</li>
                <li>Pemberhentian pekerja tetap hanya dapat dilakukan sesuai dengan peraturan yang berlaku.</li>
            </ol>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Penerbit SK,</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $kontrak->approved2->name ?? '[Nama Pejabat]' }}</div>
            <p style="font-size: 9pt;">Ketua Yayasan / Direktur</p>
        </div>
        <div class="signature-box">
            <p>Yang Bersangkutan,</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $kontrak->karyawan->full_name }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini adalah Surat Keputusan resmi pengangkatan Pekerja Tetap</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>
</body>
</html>
