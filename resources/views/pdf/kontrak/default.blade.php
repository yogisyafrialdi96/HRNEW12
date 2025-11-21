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
            line-height: 1.6;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .nomor {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
        }
        .content {
            text-align: justify;
        }
        .content p {
            margin: 10px 0;
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
            margin: 12px 0;
        }
        td {
            padding: 6px;
            vertical-align: top;
        }
        .label {
            width: 100px;
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
            text-align: justify;
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
            height: 50px;
            margin: 20px 0 5px 0;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 5px;
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
        <h1>PERJANJIAN KERJA</h1>
        <p style="margin: 5px 0;">{{ $kontrak->kontrak->nama_kontrak ?? 'Kontrak Karyawan' }}</p>
    </div>

    <div class="nomor">
        Nomor: {{ $kontrak->nomor_kontrak }}
    </div>

    <div class="content">
        <p>
            Pada hari ini, {{ \Carbon\Carbon::parse($kontrak->tglmulai_kontrak)->isoFormat('dddd, D MMMM Y') }}, 
            yang bertanda tangan di bawah ini:
        </p>

        <div class="section-title">I. PIHAK-PIHAK</div>

        <p style="font-weight: bold;">Pihak Pertama (Pemberi Kerja):</p>
        <table>
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td>{{ config('app.name', 'YAYASAN YKPI') }}</td>
            </tr>
            <tr>
                <td class="label">Mewakili</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->approved2->name ?? '[Nama Pejabat]' }}</td>
            </tr>
        </table>

        <p style="font-weight: bold; margin-top: 15px;">Pihak Kedua (Pekerja):</p>
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

        <p style="margin-top: 15px;">
            Kedua belah pihak setuju untuk mengadakan perjanjian kerja dengan ketentuan sebagai berikut:
        </p>

        <div class="section-title">II. JENIS PEKERJAAN DAN TEMPAT KERJA</div>
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
                <td class="label">Jenis Kontrak</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->kontrak->nama_kontrak }}</td>
            </tr>
        </table>

        <div class="section-title">III. PERIODE KERJA</div>
        <table>
            <tr>
                <td class="label">Tanggal Mulai</td>
                <td class="separator">:</td>
                <td>{{ \Carbon\Carbon::parse($kontrak->tglmulai_kontrak)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Berakhir</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->tglselesai_kontrak ? \Carbon\Carbon::parse($kontrak->tglselesai_kontrak)->format('d F Y') : 'Tidak Terbatas' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="separator">:</td>
                <td>{{ ucfirst($kontrak->status) }}</td>
            </tr>
        </table>

        <div class="section-title">IV. GAJI DAN TUNJANGAN</div>
        <table>
            @if($kontrak->gaji_pokok)
            <tr>
                <td class="label">Gaji Pokok</td>
                <td class="separator">:</td>
                <td>Rp {{ number_format($kontrak->gaji_pokok, 0, ',', '.') }}/bulan</td>
            </tr>
            @endif
            @if($kontrak->gaji_paket)
            <tr>
                <td class="label">Gaji Paket</td>
                <td class="separator">:</td>
                <td>Rp {{ number_format($kontrak->gaji_paket, 0, ',', '.') }}/bulan</td>
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

        <div class="section-title">V. HAK DAN KEWAJIBAN PEKERJA</div>
        <p><strong>Hak Pekerja:</strong></p>
        <ol>
            <li>Menerima upah sesuai yang telah disepakati dan tepat waktu;</li>
            <li>Mendapatkan perlindungan hukum dan keselamatan kerja;</li>
            <li>Mendapatkan izin kerja sesuai peraturan yang berlaku;</li>
            <li>Mendapatkan perlakuan yang adil dan tidak diskriminatif.</li>
        </ol>

        <p><strong>Kewajiban Pekerja:</strong></p>
        <ol>
            <li>Melaksanakan pekerjaan dengan baik dan penuh tanggung jawab;</li>
            <li>Mematuhi semua peraturan tata tertib dan disiplin kerja;</li>
            <li>Hadir tepat waktu sesuai dengan jadwal kerja yang ditetapkan;</li>
            <li>Menjaga kerahasiaan dan data-data perusahaan/yayasan;</li>
            <li>Tidak melakukan tindakan yang merugikan perusahaan/yayasan.</li>
        </ol>

        <div class="section-title">VI. PEMUTUSAN HUBUNGAN KERJA</div>
        <ol>
            <li>Hubungan kerja dapat diakhiri oleh salah satu pihak dengan pemberitahuan tertulis minimal 30 hari sebelumnya;</li>
            <li>Apabila kontrak berakhir pada tanggal yang telah ditentukan, hubungan kerja akan berakhir dengan sendirinya;</li>
            <li>Pekerja harus menyerahkan semua barang/dokumen milik perusahaan/yayasan pada akhir kontrak.</li>
        </ol>

        <div class="section-title">VII. KETENTUAN LAIN-LAIN</div>
        <ol>
            <li>Kontrak ini dapat diperpanjang atas persetujuan kedua belah pihak;</li>
            <li>Hal-hal yang belum diatur dalam kontrak ini akan diatur kemudian atas persetujuan kedua belah pihak;</li>
            <li>Kontrak ini berlaku dan mengikat kedua belah pihak setelah ditandatangani.</li>
        </ol>

        @if($kontrak->catatan)
        <div class="section-title">VIII. CATATAN KHUSUS</div>
        <p>{{ $kontrak->catatan }}</p>
        @endif

        <p style="margin-top: 15px; text-align: justify;">
            Demikian surat perjanjian kerja ini dibuat dengan sebenarnya dalam keadaan sadar dan tanpa paksaan 
            dari pihak manapun untuk dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p style="margin: 0;">Pihak Pertama</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $kontrak->approved2->name ?? '[Nama Pejabat]' }}</div>
            <p style="font-size: 9pt; margin: 0;">Ketua Yayasan / Direktur</p>
        </div>
        <div class="signature-box">
            <p style="margin: 0;">Pihak Kedua</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $kontrak->karyawan->full_name }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini adalah Perjanjian Kerja Resmi</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
    </div>
</body>
</html>
