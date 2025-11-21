<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perjanjian Kerja Waktu Tertentu - {{ $kontrak->nomor_kontrak }}</title>
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
            margin-top: 50%;
            font-size: 9pt;
            position: relative;
        }
    </style>
</head>
    <div class="header">
        <img src="{{ $kontrak->imagePath }}" style="width: 100%;">
    </div>
<body>
    <div class="namakontrak">
       PERJANJIAN KERJA WAKTU TERTENTU
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
            Kedua belah pihak telah sepakat melakukan <b>Perjanjian Kerja Waktu Tertentu</b> dengan ketentuan sebagai berikut;
        </p>

        <div class="section-title">Pasal 1</div>
        <div class="pasal">
            Pihak Pertama memakai jasa Pihak Kedua sebagai Janitor yang ditempatkan di {{ $kontrak->unit->unit }} YKPI Al-Ittihad dengan hari kerja Senin s/d Sabtu, jam kerja disesuaikan kebutuhan Unit dengan akumulasi total jam kerja 40 jam seminggu, terhitung {{ \Carbon\Carbon::parse($kontrak->tglmulai_kontrak)->format('d F Y') }} – {{ \Carbon\Carbon::parse($kontrak->tglselesai_kontrak)->format('d F Y') }}, dengan 4 (empat) kali penilaian, kecuali diputuskan terlebih dahulu sebagaimana diatur dalam pasal 9 di bawah ini.
        </div>

        <div class="section-title">Pasal 2</div>
        <div class="pasal">
            Pihak Kedua berkewajiban melaksanakan tugas-tugas yang diberikan oleh Pihak Pertama melalui Direktur Pendidikan, serta mampu dan layak menjalankan tugas dengan baik.
        </div>

        <div class="section-title">Pasal 3</div>
        <div class="pasal">
            Pihak Kedua berkewajiban mematuhi serta menta’ati tata tertib dan Peraturan Kepegawaian YKPI Al-Ittihad. 
        </div>

        <div class="section-title">Pasal 4</div>
        <div class="pasal">
            Pihak Kedua berkewajiban melaporkan perkembangan pekerjaannya kepada Pihak Pertama, seiring dengan itu Pihak Pertama melalui Direktur Pendidikan akan menilai kualitas pekerjaan dan melakukan Pembinaan untuk peningkatan Kinerja Pihak Kedua. 
        </div>

        <div class="section-title">Pasal 4</div>
        <div class="pasal">
            Pihak Kedua berkewajiban melaporkan perkembangan pekerjaannya kepada Pihak Pertama, seiring dengan itu Pihak Pertama melalui Direktur Pendidikan akan menilai kualitas pekerjaan dan melakukan Pembinaan untuk peningkatan Kinerja Pihak Kedua. 
        </div>

        <div class="section-title">Pasal 5</div>
        <div class="pasal">
            Atas pelaksanaan perjanjian ini oleh Pihak Kedua, maka Pihak Pertama berkewajiban memberi upah kepada Pihak Kedua dengan perincian sebagai berikut :
            <ol>
                <li>Gaji pokok sebesar Rp.{{ number_format($kontrak->gaji_pokok, 0, ',', '.') }},- (<i>{{ App\Helpers\TerbilangHelper::terbilangRupiah($kontrak->gaji_pokok) }}</i>) per bulan.</li>
                <li>Tunjangan operasional Rp.{{ number_format($kontrak->transport, 0, ',', '.') }},- (<i>{{ App\Helpers\TerbilangHelper::terbilangRupiah($kontrak->transport) }}</i>) per bulan atau sesuai dengan peraturan yang berlaku.</li>
                <li>Makan siang dan snack selama hari kerja (Senin s/d Jumat).</li>
                <li>Tunjangan Insentif, apabila ada tugas tambahan diluar jam kerja sesuai dengan aturan yang berlaku.</li>
                <li>THR diberikan sesuai dengan peraturan yang berlaku.</li>
                <li>Tidak masuk kerja tanpa izin yang telah ditentukan, gaji akan dikurangi (1/25 x Gaji Pokok x Jumlah hari Absen) sesuai dengan Peraturan Kepegawaian YKPI.</li>
            </ol>
        </div>
        
        <div class="section-title">Pasal 6</div>
        <div class="pasal">
            Pihak Pertama akan memberikan fasilitas Jaminan sosial tenaga kerja dengan mendaftarkan Pihak Kedua sebagai peserta BPJS Kesehatan dan Ketenagakerjaan Cabang Pekanbaru, sesuai dengan ketentuan yang berlaku.
        </div>

        <div class="section-title">Pasal 7</div>
        <div class="pasal">
            Pihak Pertama akan menyediakan fasilitas-fasilitas yang diperlukan oleh Pihak Kedua untuk melaksanakan tugasnya, tanpa biaya apapun yang dibebankan kepada Pihak Kedua. 
        </div>

        <div class="section-title">Pasal 8</div>
        <div class="pasal">
            Pihak Kedua harus sepenuhnya membela, melindungi, mengganti rugi dan membebaskan Pihak Pertama dan pegawai-pegawainya, dari setiap dan semua tuntutan, permintaan, gugatan, keputusan, setiap tanggung jawab, biaya, pengeluaran atau kewajiban lain (termasuk biaya-biaya pengacara, biaya pengadilan dan biaya yang dikeluarkan dalam membela YKPI), akibat kerusakan, kerugian harta benda, cedera atau kematian dari setiap orang, termasuk pengurus dan pegawai YKPI, yang timbul sehubungan dengan pelaksaan tugas Pihak Kedua sebagai pegawai/guru YKPI, kecuali yang mungkin diakibatkan semata-mata karena kelalaian Pihak Pertama. 
        </div>

        <div class="section-title">Pasal 9</div>
        <div class="pasal">
            Selama jangka waktu Perjanjian Kerja Waktu Tertentu ini, Pihak Pertama mempunyai hak untuk memutuskan perjanjian apabila Pihak Kedua terbukti telah melanggar peraturan sebagaimana dimaksud dalam Pasal 10 di bawah ini ataupun tidak cakap dalam melakukan tugas-tugas yang diberikan setelah melalui pembinaan yang cukup.
        </div>

        <div class="section-title">Pasal 10</div>
        <div class="pasal">
            Pihak Kedua menyetujui keadaan/ kejadian yang akan menyebabkan putusnya Perjanjian Kerja Waktu Tertentu ini, tanpa memerlukan putusan Pengadilan terlebih dahulu. <br><br>
            Keadaan/kejadian dimaksud adalah sebagai berikut :
            <ol type="a">
                <li>Pihak Kedua melakukan penipuan, pencurian, atau penggelapan barang dan/atau uang milik YKPI;</li>
                <li>Pihak Kedua memberikan keterangan palsu atau yang dipalsukan sehingga merugikan YKPI;</li>
                <li>Pihak Kedua mabuk, meminum minuman keras yang memabukkan, memakai dan atau mengedarkan narkotika, psikotropika, dan zat adiktif lainnya di lingkungan kerja;</li>
                <li>Pihak Kedua melakukan perbuatan asusila atau perjudian di lingkungan kerja;</li>
                <li>Pihak Kedua menyerang, menganiaya, mengancam, atau mengintimidasi teman sekerja/guru/pegawai  yayasan atau pengusaha/Yayasan di lingkungan kerja;</li>
                <li>Pihak Kedua membujuk teman sekerja/guru/pegawai atau pengusaha/yayasan untuk melakukan perbuatan yang bertentangan dengan peraturan perundang-undangan;</li>
                <li>Pihak Kedua dengan ceroboh atau dengan sengaja merusak atau membiarkan dalam keadaan bahaya barang milik  perusahaan/Yayasan yang menimbukan kerugian bagi YKPI;</li>
                <li>Pihak Kedua dengan ceroboh atau sengaja  membiarkan teman sekerja/guru/pegawai atau pengusaha/Yayasan dalam keadaan bahaya di tempat kerja;</li>
                <li>Pihak Kedua membongkar atau membocorkan rahasia YKPI yang seharusnya dirahasiakan kecuali untuk kepentingan negara; </li>
                <li>Pihak Kedua melakukan perbuatan lainnya di lingkungan YKPI yang diancam pidana penjara 5 (lima) tahun atau lebih ;</li> 
                <li>Pihak Kedua melihat/mengakses situs pornografi, membaca buku/komik porno, berkata-kata kasar  kepada anak/atau siswa, atau melakukan tindakan asusila lainnya yang tidak sesuai dengan pendidikan dan syari’at islam serta visi dan misi YKPI Al-Ittihad;</li>
                <li>Pihak Kedua melakukan tindakan kekarasan terhadap siswa/murid, mengadu domba sesama guru/murid, menjadi provokator, mengadakan mogok/demontrasi, </li>
                <li>Pihak Kedua mengadakan aktivitas suatu partai politik di lingkungan YKPI atau menjadi pengurus partai politik tertentu.</li>
                <li>Pihak kedua menjadi pegawai di instansi negeri maupun swasta lainnya.</li>
            </ol>
        </div>

        <div class="section-title">Pasal 11</div>
        <div class="pasal">
            Surat Perjanjian Kerja Waktu Tertentu ini mengandung seluruh pengertian antara kedua belah pihak dan menggantikan semua perundingan, surat menyurat dan setiap perjanjian apapun antara Pihak Pertama dengan Pihak Kedua, baik secara lisan maupun tulisan, berkenaan dengan masalah-masalah pokok dari Surat Perjanjian Kerja Waktu Tertentu ini. Surat Perjanjian Kerja Waktu Tertentu ini tidak dapat diubah, kecuali bila dinyatakan secara tertulis dan ditanda tangani oleh kedua belah Pihak.
        </div>

        <div class="section-title">Pasal 12</div>
        <div class="pasal">
            Perubahan dan hal-hal yang belum diatur dalam Perjanjian Kerja Waktu Tertentu ini akan disepakati dikemudian hari oleh kedua belah Pihak.
        </div>
        
        <br>

        <div class="pasal">
            Perjanjian Kerja Waktu Tertentu ini dibuat di Pekanbaru pada tanggal {{ \Carbon\Carbon::parse($kontrak->created_at)->format('d F Y') }} dalam rangkap dua yang berlaku sebagai asli dan mempunyai kekuatan pembuktian yang sama.
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
