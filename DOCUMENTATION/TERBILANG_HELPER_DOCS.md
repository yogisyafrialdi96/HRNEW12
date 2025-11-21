# TerbilangHelper - Dokumentasi

Helper untuk mengkonversi angka menjadi teks Indonesia (terbilang).

## Lokasi File
`app/Helpers/TerbilangHelper.php`

## Method yang Tersedia

### 1. `terbilangRupiah($number)`
Mengkonversi angka ke teks Indonesia dengan menambahkan "rupiah" di akhir.

**Contoh:**
```blade
{{ App\Helpers\TerbilangHelper::terbilangRupiah(1500000) }}
```
**Output:** `satu juta lima ratus ribu rupiah`

### 2. `terbilangAngka($number)`
Mengkonversi angka ke teks Indonesia tanpa menambahkan "rupiah".

**Contoh:**
```blade
{{ App\Helpers\TerbilangHelper::terbilangAngka(1500000) }}
```
**Output:** `satu juta lima ratus ribu`

### 3. `terbilang($number, $addRupiah = true)`
Method utama dengan parameter untuk menentukan apakah menambahkan "rupiah" atau tidak.

**Contoh:**
```blade
{{ App\Helpers\TerbilangHelper::terbilang(2500000, true) }}
```
**Output:** `dua juta lima ratus ribu rupiah`

## Use Cases dalam Template

### Untuk menampilkan gaji dengan terbilang:
```blade
<li>Gaji pokok sebesar Rp.{{ number_format($kontrak->gaji_pokok, 0, ',', '.') }}- 
    (<i>{{ App\Helpers\TerbilangHelper::terbilangRupiah($kontrak->gaji_pokok) }}</i>) per bulan.</li>
```

### Untuk nilai lainnya:
```blade
<!-- Dengan rupiah -->
{{ App\Helpers\TerbilangHelper::terbilangRupiah($kontrak->transport) }}

<!-- Tanpa rupiah -->
{{ App\Helpers\TerbilangHelper::terbilangAngka($kontrak->tunjangan) }}
```

## Format Output

| Input | Output |
|-------|--------|
| 0 | nol rupiah |
| 1 | satu rupiah |
| 10 | sepuluh rupiah |
| 11 | sebelas rupiah |
| 20 | dua puluh rupiah |
| 100 | satu ratus rupiah |
| 1000 | satu ribu rupiah |
| 1500000 | satu juta lima ratus ribu rupiah |
| 5000000 | lima juta rupiah |

## Catatan
- Helper mendukung bilangan negatif (akan menambahkan prefix "minus")
- Helper otomatis diload melalui PSR-4 autoload di composer.json
- Dapat digunakan di semua blade template tanpa import tambahan
