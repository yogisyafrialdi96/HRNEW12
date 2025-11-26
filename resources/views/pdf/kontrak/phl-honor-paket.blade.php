<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHL HONOR PAKET - {{ $kontrak->nomor_kontrak }}</title>
    <style>
        @page {
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 0.5cm;
            margin-top: 0.7cm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            text-align: justify;
            margin-bottom: 1cm;
            margin-top: 3.2cm;
            
        }
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 100px; /* atur sesuai tinggi kop */
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .namakontrak {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
        }
        .nomor {
            text-align: center;
            margin: 5px 0;
        }
        .content {
            text-align: justify;
        }
        .content p {
            margin: 10px 0;
        }
        .section-title {
            font-style: italic;
            margin-top: 15px;
            margin-bottom: 5px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        td {
            padding: 6px;
            vertical-align: top;
        }
        .label {
            width: 100px;
            width: 150px;
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
            padding: 0 15px;
        }
        .signature-space {
            height: 50px;
            margin: 20px 0 5px 0;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 5px;
            font-size: 10pt;
        }
        .footer {
            margin-top: 20px;
            font-size: 9pt;
            text-align: center;
            color: #666;
        }
        #footer {
            position: fixed;
            left: 0;
            right: 0;
            color: #aaa;
            font-size: 0.9em;
            bottom: 10px;
            border-top: 3px solid #87A922;
        }
        #footer table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        #footer td {
            padding: 0;
            width: 30%;
        }
        .text-footer {
            font-size: 9.5pt;
            color: #666;
            margin: 0;
            color: #87A922;
        }
        .page-number {
            text-align: right;
            font-size: 9.5pt;
        }

        .page-number:before {
            content: "Page " counter(page);
        }
        .tebusan {
            margin-top: 20%;
            font-size: 9pt;
            position: relative;
        }
    </style>
</head>
    <div class="header">
        <img src="{{ $kontrak->imagePath }}" style="width: 100%;">
    </div>
<body>
    <div class="namakontrak" style="text-transform: uppercase;">
       PERJANJIAN KERJA PEGAWAI HARIAN LEPAS ANTARA YKPI AL-ITTIHAD DENGAN {{ $kontrak->karyawan->full_name ?? '[Nama Karyawan]' }}

    </div>

    <div class="nomor">
        Nomor: {{ $kontrak->nomor_kontrak }}
    </div>

    <div class="bismillah" style="text-align: center; margin: 10px 0;">
        <img src="{{ $kontrak->imageBismillah }}" style="width: 30%;">
    </div>

    <div class="content">
        <p>
            Yang bertanda tangan di bawah ini;
        </p>

        <table>
            <tr>
                <td style="width: 10px;">1.</td>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td>
                    @if($kontrak->approved_1)
                        @php
                            $approver1 = \App\Models\Employee\Karyawan::with('user')->find($kontrak->approved_1);
                        @endphp
                        {{ $approver1->full_name ?? $approver1->user->name ?? 'DEWINTA UNTARI' }}
                    @else
                        DEWINTA UNTARI
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td>Kompleks Damar No. 608  Rumbai</td>
            </tr>
            <tr>
                <td></td>
                <td class="label">Jabatan</td>
                <td class="separator">:</td>
                <td>
                    @if($kontrak->approved_1)
                        @php
                            $approver1 = \App\Models\Employee\Karyawan::with(['activeJabatan.jabatan'])->find($kontrak->approved_1);
                        @endphp
                        {{ $approver1->activeJabatan?->jabatan?->nama_jabatan ?? 'Manager HR-GS YKPI Al-Ittihad' }}
                    @else
                        Manager HR-GS YKPI Al-Ittihad
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Selanjutnya disebut PIHAK PERTAMA</td>
            </tr>
        </table>

        <table>
            <tr>
                <td style="width: 10px;">2.</td>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->karyawan->full_name ?? '[Nama Karyawan]' }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="label">Tempat dan Tgl Lahir</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->karyawan->tempat_lahir ?? '[Tempat Lahir]' }}, {{ \Carbon\Carbon::parse($kontrak->karyawan->tanggal_lahir)->format('d F Y') ?? '[Tanggal Lahir]' }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->karyawan->alamat_ktp ?? '[Alamat KTP]' }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="label">Jenis Kelamin</td>
                <td class="separator">:</td>
                <td>{{ $kontrak->karyawan->gender ?? '[Jenis Kelamin]' }}</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Selanjutnya disebut PIHAK KEDUA</td>
            </tr>
        </table>

        <p style="margin-top: 15px;">
            Kedua belah pihak telah sepakat melakukan <b>Perjanjian Kerja Pegawai Harian Lepas</b> dengan ketentuan sebagai berikut;
        </p>

        <div class="section-title">Pasal 1</div>
        <div class="pasal">
            Pihak Pertama memakai jasa Pihak Kedua sebagai {{ $kontrak->jabatan->nama_jabatan ?? '[Jabatan]' }} Al-Ittihad terhitung {{ \Carbon\Carbon::parse($kontrak->tglmulai_kontrak)->format('d F Y') }} sampai dengan {{ \Carbon\Carbon::parse($kontrak->tglselesai_kontrak)->format('d F Y') }}, dengan hari kerja dan jam kerja sesuai dengan jadwal kurikulum {{ $kontrak->unit->unit }} Al-Ittihad
        </div>

        <div class="section-title">Pasal 2</div>
        <div class="pasal">
            Pihak kedua berkewajiban melaksanakan tugas-tugas yang diberikan oleh Pihak Pertama dan mematuhi peraturan  yang berlaku di lingkungan YKPI Al Ittihad.
        </div>

        <div class="section-title">Pasal 3</div>
        <div class="pasal">
            Pihak Kedua berkewajiban melaporkan perkembangan pekerjaannya kepada Pihak Pertama, seiring dengan itu Pihak Pertama menilai pihak kedua dan memberikan masukan untuk peningkatan Kinerja Pihak Kedua.
        </div>

        <div class="section-title">Pasal 4</div>
        <div class="pasal">
            Pihak Pertama berkewajiban memberi kompensasi kepada Pihak Kedua sebagai berikut : 
            <ol type="a">
                <li>Honor  paket  Rp.{{ number_format($kontrak->gaji_paket, 0, ',', '.') }},- (<i>{{ App\Helpers\TerbilangHelper::terbilangRupiah($kontrak->gaji_paket) }}</i>) per bulan. Honor Paket ini mencakup semua kompensasi dan maslah-maslah Pihak Kedua. Tidak ada pembayaran lain yang terhutang dari Pihak Pertama kepada Pihak Kedua.</li>
                <li>Makan siang dan snack selama hari kerja (Senin s/d Jumat).</li>
                <li>Tidak masuk kerja tanpa izin yang telah ditentukan, gaji akan dikurangi (1/22 x Gaji Pokok x Jumlah hari Absen) sesuai dengan Peraturan Kepegawaian YKPI.</li>
            </ol>
        </div>

        <div class="section-title">Pasal 5</div>
        <div class="pasal">
            Pihak Pertama akan menyediakan fasilitas-fasilitas sesuai dengan ketentuan yayasan yang diperlukan oleh Pihak Kedua untuk melaksanakan tugas sebagai {{ $kontrak->jabatan->nama_jabatan ?? '[Jabatan]' }} Al-Ittihad, tanpa biaya apapun yang dibebankan kepada Pihak Kedua.
        </div>
        
        <div class="section-title">Pasal 6</div>
        <div class="pasal">
            Pihak Kedua harus sepenuhnya membela, melindungi, mengganti rugi dan membebaskan Pihak Pertama dan pegawai-pegawainya, dari setiap dan semua tuntutan, permintaan, gugatan, keputusan, setiap tanggung jawab, biaya, pengeluaran atau kewajiban lain (termasuk biaya-biaya pengacara, biaya pengadilan dan biaya yang  dikeluarkan dalam membela YKPI), akibat kerusakan, kerugian harta benda, cedera atau kematian dari setiap orang, termasuk pengurus dan pegawai YKPI, yang timbul sehubungan dengan pelaksaan tugas Pihak Kedua sebagai {{ $kontrak->jabatan->nama_jabatan ?? '[Jabatan]' }} Al-Ittihad, kecuali yang mungkin diakibatkan semata-mata karena kelalaian Pihak Pertama.
        </div>

        <div class="section-title">Pasal 7</div>
        <div class="pasal">
            Pihak Pertama mempunyai hak untuk memutuskan perjanjian apabila Pihak Kedua terbukti telah melanggar peraturan yang diberlakukan Pihak Pertama. Dalam hal pemutusan yang demikian, maka pihak Pertama hanya akan bertanggungjawab untuk membayar upah yang harus dilakukan kepada pihak Kedua sampai tanggal pemutusan perjanjian ini. Pasal-pasal 1266 dan 1267 Kitab Undang-undang Hukum Perdata yang masing-masing mewajibkan campur tangan pengadilan guna memutuskan suatu perjanjian dan hak menuntut ganti rugi atas kerugian yang timbul sebagai akibat pemutusan Perjanjian, dengan ini secara khusus disetujui tidak diberlakukan oleh kedua belah pihak berkenaan dengan suatu pemutusan yang berlaku di sini atau menurut ketentuan-ketentuan lain dari surat Perjanjian ini. 
        </div>

        <div class="section-title">Pasal 8</div>
        <div class="pasal">
            Pihak Kedua harus selalu merupakan pihak yang mandiri dan tidak akan mempunyai wewenang untuk bertindak sebagai agen Pihak Pertama atau mempunyai kuasa untuk mengikat Pihak Pertama pada seseorang dengan cara apapun.
        </div>

        <div class="section-title">Pasal 9</div>
        <div class="pasal">
            Surat Perjanjian ini mengandung seluruh pengertian antara kedua belah pihak dan menggantikan semua perundingan, surat menyurat dan setiap perjanjian apapun antara Pihak Pertama dengan Pihak Kedua, baik secara lisan maupun tulisan, berkenaan dengan masalah-masalah pokok dari Surat Perjanjian ini. Surat Perjanjian ini tidak dapat diubah, kecuali bila dinyatakan secara tertulis dan ditanda tangani oleh kedua belah pihak.
        </div>

        <div class="section-title">Pasal 10</div>
        <div class="pasal">
            Ketentuan-ketentuan tersebut dalam pasal 6,  akan tetap berlaku setelah berakhirnya Surat Perjanjian ini.
        </div>

        <div class="section-title">Pasal 11</div>
        <div class="pasal">
            Perubahan dan hal-hal yang belum diatur dalam perjanjian ini akan disepakati di kemudian hari oleh kedua belah pihak.
        </div>
        
        <br>

        <div class="pasal">
            Perjanjian ini dibuat di Pekanbaru pada tanggal {{ \Carbon\Carbon::parse($kontrak->created_at)->format('d F Y') }} dalam rangkap dua yang berlaku sebagai asli dan mempunyai kekuatan pembuktian yang sama.
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p style="margin: 0;">Pihak Pertama,</p>
            <div class="signature-space"></div>
            @if($kontrak->approved_1)
                @php
                    $approver1 = \App\Models\Employee\Karyawan::with(['activeJabatan.jabatan'])->find($kontrak->approved_1);
                @endphp
                <div class="signature-name" style="text-decoration: underline">{{ $approver1->full_name ?? '[Nama Pejabat]' }}</div>
                <p style="font-size: 9pt; margin: 0;">{{ $approver1->activeJabatan?->jabatan?->nama_jabatan ?? 'Manager HR-GS YKPI Al-Ittihad' }}</p>
            @else
                <div class="signature-name" style="text-decoration: underline">[Nama Pejabat]</div>
                <p style="font-size: 9pt; margin: 0;">Manager HR-GS YKPI Al-Ittihad</p>
            @endif
        </div>
        <div class="signature-box">
            <p style="margin: 0;">Pihak Kedua,</p>
            <div class="signature-space"></div>
            <div class="signature-name">{{ $kontrak->karyawan->full_name }}</div>
        </div>
    </div>

    <div id="footer">
        <table>
            <tr>
                <td style="width: 100%;">
                    <p class="text-footer">Kompleks Kampus Al-Ittihad Kel. Lembah Damai, Kec. Rumbai, Pekanbaru - Riau. <br> Kode Pos: 28263. Telp/Fax: (0761) 559029. Email: mgmt_support@al-ittihad.org</p>
                    <div class="sub-footer" style="color: #666; font-size: 7pt;">
                        <p>Dokumen ini adalah Perjanjian Kerja Waktu Tertentu resmi. Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
                    </div>
                </td>
                <td>
                    <div class="page-number"></div>
                </td>
            </tr>
        </table>
    </div>

    <div class="tebusan">
        <div class="sub-tebusan" style="text-decoration: underline">Tembusan:</div>
        <ol style="margin-top: 0; margin-left: 10px;">
            <li>Pimpinan Unit</li>
            <li>Kord. Keuangan</li>
            <li>Arsip</li>
        </ol>
    </div>

</body>
</html>
