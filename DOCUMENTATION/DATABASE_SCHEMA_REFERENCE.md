# DATABASE SCHEMA REFERENCE - HRNEW12

*Last Updated: 2025-12-10*

Dokumentasi lengkap struktur database untuk memudahkan pembuatan relations, seeders, dan migrations di masa depan.

---

## 📋 DAFTAR ISI

1. [Core Tables](#core-tables)
2. [Leave Management Tables](#leave-management-tables)
3. [Master Data Tables](#master-data-tables)
4. [Employee Related Tables](#employee-related-tables)
5. [Approval Workflow Tables](#approval-workflow-tables)
6. [Permission & Role Tables](#permission--role-tables)
7. [Foreign Key Relationships](#foreign-key-relationships)

---

## CORE TABLES

### 1. `users` TABLE
**Purpose:** Tabel user system untuk authentication

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| name | varchar(255) | NO | - | - | Nama user |
| email | varchar(255) | NO | UNI | - | Email (unique) |
| email_verified_at | timestamp | YES | - | NULL | Email verification timestamp |
| password | varchar(255) | NO | - | - | Password hash |
| remember_token | varchar(100) | YES | - | NULL | Remember me token |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |
| deleted_at | timestamp | YES | - | NULL | Soft delete timestamp |

**Relations:**
- Has many: `Karyawan`, `CutiPengajuan`, `IzinPengajuan`, `CutiSaldo`, `AtasanUser`
- Has many through: Permissions, Roles

**Usage Notes:**
- Primary user authentication table
- NIP dan data tambahan disimpan di tabel `karyawan`
- Supports soft deletion

---

### 2. `karyawan` TABLE
**Purpose:** Data detail karyawan/pegawai

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| user_id | bigint unsigned | NO | FK | - | Foreign key ke users |
| nip | varchar(20) | NO | UNI | - | NIP (unique) |
| full_name | varchar(255) | NO | - | - | Nama lengkap |
| inisial | varchar(3) | NO | UNI | - | Inisial (unique) |
| gender | enum('laki-laki','perempuan') | NO | - | - | Jenis kelamin |
| tanggal_lahir | date | YES | - | NULL | Tanggal lahir |
| tempat_lahir | varchar(255) | YES | - | NULL | Tempat lahir |
| agama | enum('Islam','Hindu','Budha','Katolik','Protestan','Konghucu') | NO | - | - | Agama |
| status_kawin | enum('lajang','menikah','cerai') | NO | - | lajang | Status perkawinan |
| nik | varchar(255) | YES | - | NULL | Nomor induk kependudukan |
| nkk | varchar(255) | YES | - | NULL | Nomor kartu keluarga |
| npwp | varchar(255) | YES | - | NULL | Nomor pokok wajib pajak |
| gelar_depan | varchar(10) | YES | - | NULL | Gelar depan (Dr., Prof., dll) |
| gelar_belakang | varchar(10) | YES | - | NULL | Gelar belakang (S.Pd, M.M, dll) |
| hp | varchar(15) | YES | - | NULL | Nomor HP |
| whatsapp | varchar(15) | YES | - | NULL | Nomor WhatsApp |
| panggilan | varchar(255) | YES | - | NULL | Nama panggilan |
| foto | varchar(255) | YES | - | NULL | Path foto profil |
| ttd | varchar(255) | YES | - | NULL | Path file tanda tangan |
| blood_type | enum('A','B','AB','O') | YES | - | NULL | Tipe darah |
| statuskaryawan_id | bigint unsigned | NO | FK | - | Status karyawan (tetap/kontrak/dll) |
| jenis_karyawan | enum('Guru','Pegawai') | YES | - | NULL | Tipe karyawan |
| golongan_id | bigint unsigned | YES | FK | - | Golongan karyawan |
| mapel_id | bigint unsigned | YES | FK | - | Mata pelajaran (untuk guru) |
| pndk_awal | enum('SD','SMP','SMA','D1','D2','D3','D4','S1','S2','S3') | NO | - | - | Pendidikan awal |
| pndk_akhir | enum('SD','SMP','SMA','D1','D2','D3','D4','S1','S2','S3') | NO | - | - | Pendidikan akhir |
| tgl_masuk | date | NO | FK | - | Tanggal masuk |
| tgl_karyawan_tetap | date | YES | - | NULL | Tanggal menjadi karyawan tetap |
| tgl_berhenti | date | YES | - | NULL | Tanggal berhenti/keluar |
| **Address (KTP)** | | | | |
| alamat_ktp | varchar(255) | YES | - | NULL | Alamat KTP |
| rt_ktp | varchar(255) | YES | - | NULL | RT KTP |
| rw_ktp | varchar(255) | YES | - | NULL | RW KTP |
| prov_id | bigint unsigned | YES | FK | - | Provinsi KTP |
| kab_id | bigint unsigned | YES | FK | - | Kabupaten KTP |
| kec_id | bigint unsigned | YES | FK | - | Kecamatan KTP |
| desa_id | bigint unsigned | YES | FK | - | Desa KTP |
| **Address (Domisili)** | | | | |
| domisili_sama_ktp | tinyint(1) | NO | - | 0 | Flag domisili sama dengan KTP |
| alamat_dom | varchar(255) | YES | - | NULL | Alamat domisili |
| rt_dom | varchar(255) | YES | - | NULL | RT domisili |
| rw_dom | varchar(255) | YES | - | NULL | RW domisili |
| provdom_id | bigint unsigned | YES | FK | - | Provinsi domisili |
| kabdom_id | bigint unsigned | YES | FK | - | Kabupaten domisili |
| kecdom_id | bigint unsigned | YES | FK | - | Kecamatan domisili |
| desdom_id | bigint unsigned | YES | FK | - | Desa domisili |
| **Emergency Contact** | | | | |
| emergency_contact_name | varchar(255) | YES | - | NULL | Nama kontak darurat |
| emergency_contact_phone | varchar(15) | YES | - | NULL | Nomor kontak darurat |
| **Meta** | | | | |
| created_by | bigint unsigned | YES | FK | - | User ID yang membuat record |
| updated_by | bigint unsigned | YES | FK | - | User ID yang update record |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |
| deleted_at | timestamp | YES | - | NULL | Soft delete timestamp |

**Foreign Keys:**
- `user_id` → `users.id`
- `statuskaryawan_id` → `master_statuspegawai.id`
- `golongan_id` → `master_golongan.id`
- `mapel_id` → `master_mapel.id`
- `prov_id`, `kab_id`, `kec_id`, `desa_id` → Location tables
- `created_by`, `updated_by` → `users.id`

**Relations:**
- Belongs to: `User`, `StatusKaryawan`, `Golongan`, `Mapel`, location tables
- Has many: `KaryawanPendidikan`, `KaryawanSertifikasi`, `KaryawanBahasa`, etc.

---

## LEAVE MANAGEMENT TABLES

### 3. `cuti_pengajuan` TABLE
**Purpose:** Pengajuan cuti/leave request dari karyawan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| nomor_cuti | varchar(255) | YES | UNI | NULL | Nomor cuti (auto-generated: 001/YKPI-CUTI/XII/2025) |
| user_id | bigint unsigned | NO | FK | - | Karyawan yang mengajukan |
| cuti_saldo_id | bigint unsigned | YES | FK | NULL | Referensi cuti saldo |
| unit_approval_setting_id | bigint unsigned | YES | FK | NULL | Setting approval untuk unit |
| tahun_ajaran_id | bigint unsigned | YES | FK | NULL | Tahun ajaran terkait |
| tanggal_mulai | date | NO | - | - | Tanggal mulai cuti |
| jam_mulai | time | YES | - | NULL | Jam mulai (jika jam-jaman) |
| tanggal_selesai | date | NO | - | - | Tanggal selesai cuti |
| jam_selesai | time | YES | - | NULL | Jam selesai (jika jam-jaman) |
| jumlah_hari | int | YES | - | NULL | Jumlah hari cuti yang diambil |
| jenis_cuti | enum(...) | YES | - | NULL | Jenis cuti (cuti tahunan, sakit, dll) |
| keterangan | text | YES | - | NULL | Alasan/keterangan pengajuan |
| approval_status | enum('pending_approval','approved_level_1','approved_level_2','approved_level_3','rejected_level_1','rejected_level_2','rejected_level_3') | NO | - | pending_approval | Status approval |
| status | enum(...) | YES | - | NULL | Status pengajuan (pending, approved, rejected, dll) |
| created_by | bigint unsigned | YES | FK | - | User yang membuat |
| updated_by | bigint unsigned | YES | FK | - | User yang update |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |
| deleted_at | timestamp | YES | - | NULL | Soft delete timestamp |

**Foreign Keys:**
- `user_id` → `users.id`
- `cuti_saldo_id` → `cuti_saldo.id`
- `unit_approval_setting_id` → `unit_approval_settings.id`
- `tahun_ajaran_id` → `master_tahunajaran.id`
- `created_by`, `updated_by` → `users.id`

**Relations:**
- Belongs to: `User`, `CutiSaldo`, `UnitApprovalSetting`, `TahunAjaran`
- Has many: `CutiApproval`, `CutiApprovalHistory`

**Special Features:**
- Auto-generates `nomor_cuti` via `CutiNumberGenerator` service
- Tracks approval workflow via `approval_status`
- Calculates working days via `CutiCalculationService`

---

### 4. `cuti_saldo` TABLE
**Purpose:** Saldo/balance cuti per karyawan per tahun

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| user_id | bigint unsigned | NO | FK | - | Karyawan |
| tahun_ajaran_id | bigint unsigned | NO | FK | - | Tahun ajaran |
| jumlah_cuti | int | NO | - | - | Total hari cuti yang didapat |
| cuti_terpakai | int | NO | - | - | Cuti yang sudah dipakai |
| cuti_sisa | int | NO | - | - | Sisa cuti (jumlah - terpakai) |
| carryover | int | YES | - | NULL | Saldo carry over dari tahun lalu |
| updated_by | bigint unsigned | YES | FK | - | User ID yang update |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

**Foreign Keys:**
- `user_id` → `users.id`
- `tahun_ajaran_id` → `master_tahunajaran.id`
- `updated_by` → `users.id`

**Relations:**
- Belongs to: `User`, `TahunAjaran`
- Has many: `CutiPengajuan`

---

### 5. `cuti_setup` TABLE
**Purpose:** Konfigurasi global cuti

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| h_min_cuti | int | NO | - | - | Jumlah hari minimum cuti |
| h_per_tahun | int | NO | - | - | Jumlah hari cuti per tahun |
| perlu_approval | tinyint(1) | NO | - | 1 | Perlu approval atau tidak |
| updated_by | bigint unsigned | YES | FK | - | User ID yang update |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 6. `jam_kerja_unit` TABLE
**Purpose:** Jadwal kerja per unit/hari untuk perhitungan hari efektif

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| unit_id | bigint unsigned | NO | FK | - | Unit/Divisi |
| hari | enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') | NO | - | - | Hari kerja |
| jam_mulai | time | YES | - | NULL | Jam mulai kerja |
| jam_selesai | time | YES | - | NULL | Jam selesai kerja |
| jam_istirahat | int | YES | - | NULL | Durasi istirahat (dalam menit) |
| is_libur | tinyint(1) | NO | - | 0 | Apakah hari libur (1=libur, 0=kerja) |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

**Foreign Keys:**
- `unit_id` → `master_unit.id`

**Relations:**
- Belongs to: `Unit`

**Data Example:**
```
Unit: 1 (Admin), Senin-Jumat: 08:00-17:00, istirahat 60 min, Sabtu-Minggu: libur
```

**Usage:**
- Digunakan `CutiCalculationService` untuk hitung working days
- Exclude days dengan `is_libur = 1`

---

### 7. `libur_nasional` TABLE
**Purpose:** Hari libur nasional dan regional

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| tanggal_libur | date | NO | - | - | Tanggal mulai libur |
| tanggal_libur_akhir | date | YES | - | NULL | Tanggal akhir libur (multi-day) |
| nama_libur | varchar(255) | NO | - | - | Nama hari libur |
| tipe | enum('nasional','lokal','cuti_bersama') | NO | - | - | Tipe libur |
| provinsi_id | bigint unsigned | YES | FK | NULL | Provinsi (untuk libur lokal) |
| created_by | bigint unsigned | YES | FK | NULL | User ID yang buat |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

**Foreign Keys:**
- `provinsi_id` → `provinsi.id`
- `created_by` → `users.id`

**Data Example:**
```
- 2025-01-01: Tahun Baru Masehi (nasional)
- 2025-04-10 to 2025-04-11: Lebaran (cuti_bersama)
- 2025-03-29: Nyepi (lokal, provinsi_id: Bali)
```

**Usage:**
- Digunakan `CutiCalculationService` untuk exclude dari working days
- Supports multi-day holidays via `tanggal_libur_akhir`

---

### 8. `cuti_approval` TABLE
**Purpose:** Approval workflow untuk cuti pengajuan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| cuti_pengajuan_id | bigint unsigned | NO | FK | - | Referensi pengajuan cuti |
| atasan_user_id | bigint unsigned | YES | FK | NULL | Atasan yang approve |
| level_approval | int | YES | - | NULL | Level approval (1, 2, 3) |
| status_approval | enum(...) | YES | - | NULL | Status (pending, approved, rejected) |
| keterangan | text | YES | - | NULL | Catatan approval |
| approved_by | bigint unsigned | YES | FK | NULL | User yang approve |
| approved_at | timestamp | YES | - | NULL | Waktu approval |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

**Foreign Keys:**
- `cuti_pengajuan_id` → `cuti_pengajuan.id`
- `atasan_user_id` → `atasan_user.id`
- `approved_by` → `users.id`

---

### 9. `izin_pengajuan` TABLE
**Purpose:** Pengajuan izin (berbeda dengan cuti)

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| user_id | bigint unsigned | NO | FK | - | Karyawan yang mengajukan |
| unit_approval_setting_id | bigint unsigned | YES | FK | NULL | Setting approval |
| tahun_ajaran_id | bigint unsigned | YES | FK | NULL | Tahun ajaran |
| tanggal_izin | date | NO | - | - | Tanggal izin |
| jam_mulai | time | YES | - | NULL | Jam mulai |
| jam_selesai | time | YES | - | NULL | Jam selesai |
| jenis_izin | varchar(255) | YES | - | NULL | Jenis izin (sakit, keperluan, dll) |
| keterangan | text | YES | - | NULL | Alasan/keterangan |
| status | enum(...) | YES | - | NULL | Status izin |
| created_by | bigint unsigned | YES | FK | - | User yang buat |
| updated_by | bigint unsigned | YES | FK | - | User yang update |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |
| deleted_at | timestamp | YES | - | NULL | Soft delete |

---

---

## MASTER DATA TABLES

### 10. `master_unit` TABLE
**Purpose:** Master unit/divisi/departemen kerja

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| unit | varchar(255) | NO | - | - | Nama unit |
| kode_unit | varchar(10) | YES | - | NULL | Kode unit |
| department_id | bigint unsigned | NO | FK | - | Department parent |
| kepala_unit | bigint unsigned | YES | FK | NULL | User ID kepala unit |
| deskripsi | text | YES | - | NULL | Deskripsi unit |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_by | bigint unsigned | YES | FK | - | User yang buat |
| updated_by | bigint unsigned | YES | FK | - | User yang update |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |
| deleted_at | timestamp | YES | - | NULL | Soft delete |

---

### 11. `master_jabatan` TABLE
**Purpose:** Master jabatan/posisi

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| jabatan | varchar(255) | NO | - | - | Nama jabatan |
| level | int | YES | - | NULL | Level jabatan |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |
| deleted_at | timestamp | YES | - | NULL | Soft delete |

---

### 12. `master_department` TABLE
**Purpose:** Master departemen

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| department | varchar(255) | NO | - | - | Nama departemen |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |
| deleted_at | timestamp | YES | - | NULL | Soft delete |

---

### 13. `master_tahunajaran` TABLE
**Purpose:** Master tahun ajaran

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| tahun_ajaran | varchar(20) | NO | UNI | - | Format tahun ajaran (2024/2025) |
| tahun_mulai | int | NO | - | - | Tahun mulai |
| tahun_akhir | int | NO | - | - | Tahun akhir |
| tgl_mulai | date | NO | - | - | Tanggal mulai tahun ajaran |
| tgl_akhir | date | NO | - | - | Tanggal akhir tahun ajaran |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 14. `master_statuspegawai` TABLE
**Purpose:** Master status karyawan (tetap, kontrak, honorer, dll)

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| status_pegawai | varchar(255) | NO | - | - | Nama status |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 15. `master_golongan` TABLE
**Purpose:** Master golongan/grade karyawan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| golongan | varchar(10) | NO | - | - | Golongan (I, II, III, IV) |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 16. `master_kontrak` TABLE
**Purpose:** Master tipe kontrak

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| kontrak | varchar(255) | NO | - | - | Tipe kontrak |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 17. `master_mapel` TABLE
**Purpose:** Master mata pelajaran (untuk guru)

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| mapel | varchar(255) | NO | - | - | Nama mata pelajaran |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 18. `master_statuskawin` TABLE
**Purpose:** Master status perkawinan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| status_kawin | varchar(255) | NO | - | - | Status kawin |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 19. `master_educationlevel` TABLE
**Purpose:** Master tingkat pendidikan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| nama_pendidikan | varchar(255) | NO | - | - | Tingkat pendidikan |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

---

## EMPLOYEE RELATED TABLES

### 20. `karyawan_pendidikan` TABLE
**Purpose:** Riwayat pendidikan karyawan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| jenjang | varchar(255) | YES | - | NULL | Jenjang pendidikan |
| institusi | varchar(255) | YES | - | NULL | Institusi/Universitas |
| program_studi | varchar(255) | YES | - | NULL | Program studi |
| tahun_lulus | int | YES | - | NULL | Tahun lulus |
| ipk | varchar(255) | YES | - | NULL | IPK |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 21. `karyawan_sertifikasi` TABLE
**Purpose:** Sertifikasi dan lisensi karyawan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| nama_sertifikasi | varchar(255) | YES | - | NULL | Nama sertifikasi |
| pengguna_sertifikasi | varchar(255) | YES | - | NULL | Penerbit |
| no_sertifikat | varchar(255) | YES | - | NULL | Nomor sertifikat |
| tgl_peroleh | date | YES | - | NULL | Tanggal peroleh |
| tgl_expired | date | YES | - | NULL | Tanggal expired |
| file_sertifikat | varchar(255) | YES | - | NULL | Path file |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 22. `karyawan_bahasa` TABLE
**Purpose:** Kemampuan bahasa karyawan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| bahasa | varchar(255) | YES | - | NULL | Nama bahasa |
| tingkat_kemampuan | enum('Dasar','Menengah','Mahir') | YES | - | NULL | Tingkat kemampuan |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 23. `karyawan_kontrak` TABLE
**Purpose:** Riwayat kontrak karyawan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| master_kontrak_id | bigint unsigned | NO | FK | - | Tipe kontrak |
| tgl_mulai_kontrak | date | YES | - | NULL | Tanggal mulai |
| tgl_selesai_kontrak | date | YES | - | NULL | Tanggal selesai |
| no_kontrak | varchar(255) | YES | - | NULL | Nomor kontrak |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 24. `karyawan_jabatan` TABLE
**Purpose:** Riwayat jabatan karyawan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| jabatan_id | bigint unsigned | NO | FK | - | Referensi jabatan |
| tgl_mulai_jabatan | date | YES | - | NULL | Tanggal mulai |
| tgl_selesai_jabatan | date | YES | - | NULL | Tanggal selesai |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 25. `karyawan_pengalamankerja` TABLE
**Purpose:** Pengalaman kerja sebelumnya

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| nama_perusahaan | varchar(255) | YES | - | NULL | Nama perusahaan |
| jabatan | varchar(255) | YES | - | NULL | Jabatan |
| tgl_mulai | date | YES | - | NULL | Tanggal mulai |
| tgl_selesai | date | YES | - | NULL | Tanggal selesai |
| alasan_keluar | text | YES | - | NULL | Alasan keluar |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 26. `karyawan_pelatihan` TABLE
**Purpose:** Riwayat pelatihan/training

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| nama_pelatihan | varchar(255) | YES | - | NULL | Nama pelatihan |
| institusi | varchar(255) | YES | - | NULL | Institusi penyelenggara |
| tgl_mulai | date | YES | - | NULL | Tanggal mulai |
| tgl_selesai | date | YES | - | NULL | Tanggal selesai |
| lokasi | varchar(255) | YES | - | NULL | Lokasi pelatihan |
| no_sertifikat | varchar(255) | YES | - | NULL | Nomor sertifikat |
| file_sertifikat | varchar(255) | YES | - | NULL | Path file sertifikat |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 27. `karyawan_prestasi` TABLE
**Purpose:** Prestasi/penghargaan karyawan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| nama_prestasi | varchar(255) | YES | - | NULL | Nama prestasi |
| tingkat | varchar(255) | YES | - | NULL | Tingkat (nasional, lokal, dll) |
| tgl_peroleh | date | YES | - | NULL | Tanggal peroleh |
| penyelenggara | varchar(255) | YES | - | NULL | Penyelenggara |
| keterangan | text | YES | - | NULL | Keterangan |
| file_prestasi | varchar(255) | YES | - | NULL | Path file bukti |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 28. `karyawan_organisasi` TABLE
**Purpose:** Keikutsertaan organisasi/kegiatan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| nama_organisasi | varchar(255) | YES | - | NULL | Nama organisasi |
| jabatan | varchar(255) | YES | - | NULL | Jabatan dalam organisasi |
| tgl_mulai | date | YES | - | NULL | Tanggal mulai |
| tgl_selesai | date | YES | - | NULL | Tanggal selesai |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 29. `karyawan_anggotakeluarga` TABLE
**Purpose:** Data anggota keluarga

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| nama_anggota | varchar(255) | YES | - | NULL | Nama anggota keluarga |
| hubungan | varchar(255) | YES | - | NULL | Hubungan (istri, anak, ayah, dll) |
| tanggal_lahir | date | YES | - | NULL | Tanggal lahir |
| pekerjaan | varchar(255) | YES | - | NULL | Pekerjaan |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 30. `karyawan_akunbank` TABLE
**Purpose:** Data rekening bank untuk gaji

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| nama_bank | varchar(255) | YES | - | NULL | Nama bank |
| nomor_rekening | varchar(255) | YES | - | NULL | Nomor rekening |
| nama_pemilik | varchar(255) | YES | - | NULL | Nama pemilik rekening |
| is_default | tinyint(1) | NO | - | 0 | Rekening utama |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

### 31. `karyawan_dokumen` TABLE
**Purpose:** Dokumen/file karyawan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| karyawan_id | bigint unsigned | NO | FK | - | Referensi karyawan |
| jenis_dokumen | varchar(255) | YES | - | NULL | Jenis dokumen (KTP, NPWP, dll) |
| nomor_dokumen | varchar(255) | YES | - | NULL | Nomor dokumen |
| file_dokumen | varchar(255) | YES | - | NULL | Path file |
| tgl_berlaku | date | YES | - | NULL | Tanggal berlaku |
| tgl_expired | date | YES | - | NULL | Tanggal expired |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

---

## APPROVAL WORKFLOW TABLES

### 32. `pengurus` TABLE
**Purpose:** Data pengurus organisasi

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| user_id | bigint unsigned | NO | FK | - | Referensi user |
| jabatan_id | bigint unsigned | NO | FK | - | Jabatan pengurus |
| nama_pengurus | varchar(255) | NO | - | - | Nama |
| inisial | varchar(3) | NO | - | - | Inisial |
| hp | varchar(15) | YES | - | NULL | No HP |
| jenis_kelamin | enum('laki-laki','perempuan') | NO | - | - | Jenis kelamin |
| gelar_depan | varchar(255) | YES | - | NULL | Gelar depan |
| gelar_belakang | varchar(255) | YES | - | NULL | Gelar belakang |
| tempat_lahir | varchar(255) | YES | - | NULL | Tempat lahir |
| tanggal_lahir | date | YES | - | NULL | Tanggal lahir |
| alamat | varchar(255) | YES | - | NULL | Alamat |
| foto | varchar(255) | YES | - | NULL | Path foto |
| ttd | varchar(255) | YES | - | NULL | Path tanda tangan |
| tanggal_masuk | date | NO | - | - | Tanggal mulai menjadi pengurus |
| tanggal_keluar | date | YES | - | NULL | Tanggal berhenti |
| posisi | varchar(255) | NO | - | - | Posisi (ketua, anggota, dll) |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

**Foreign Keys:**
- `user_id` → `users.id`
- `jabatan_id` → `master_jabatan.id`

---

### 33. `atasan_user` TABLE
**Purpose:** Relasi user dengan atasannya (untuk approval workflow)

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| user_id | bigint unsigned | NO | FK | - | User/karyawan |
| atasan_id | bigint unsigned | YES | FK | NULL | User atasan level 1 |
| atasan_level2_id | bigint unsigned | YES | FK | NULL | User atasan level 2 |
| atasan_level3_id | bigint unsigned | YES | FK | NULL | User atasan level 3 |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_by | bigint unsigned | YES | FK | - | User yang buat |
| updated_by | bigint unsigned | YES | FK | - | User yang update |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

**Foreign Keys:**
- `user_id` → `users.id`
- `atasan_id` → `users.id`
- `atasan_level2_id` → `users.id`
- `atasan_level3_id` → `users.id`
- `created_by`, `updated_by` → `users.id`

**Relations:**
- Belongs to: `User`, `User` (atasan)
- Multiple hierarchy levels (3 levels)

---

### 34. `atasan_user_history` TABLE
**Purpose:** Riwayat perubahan atasan

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| atasan_user_id | bigint unsigned | NO | FK | - | Referensi atasan_user |
| atasan_lama_id | bigint unsigned | YES | FK | NULL | Atasan sebelumnya |
| atasan_baru_id | bigint unsigned | YES | FK | NULL | Atasan baru |
| tgl_perubahan | date | NO | - | - | Tanggal perubahan |
| alasan_perubahan | text | YES | - | NULL | Alasan perubahan |
| changed_by | bigint unsigned | YES | FK | - | User yang membuat perubahan |
| created_at | timestamp | YES | - | NULL | Created timestamp |

---

### 35. `unit_approval_settings` TABLE
**Purpose:** Konfigurasi approval per unit

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| unit_id | bigint unsigned | NO | FK | - | Referensi unit |
| level_approval | int | NO | - | - | Jumlah level approval |
| approval_level_1_id | bigint unsigned | YES | FK | NULL | Approver level 1 |
| approval_level_2_id | bigint unsigned | YES | FK | NULL | Approver level 2 |
| approval_level_3_id | bigint unsigned | YES | FK | NULL | Approver level 3 |
| is_active | tinyint(1) | NO | - | 1 | Status aktif |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

---

---

## PERMISSION & ROLE TABLES

### 36. `permissions` TABLE (Spatie Permission)
**Purpose:** Daftar permissions/akses

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| name | varchar(125) | NO | UNI | - | Nama permission |
| guard_name | varchar(125) | NO | - | - | Guard name (web, api) |
| description | text | YES | - | NULL | Deskripsi permission |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

**Common Permissions:**
```
cuti.create - Buat pengajuan cuti
cuti.view - Lihat pengajuan cuti
cuti.edit - Edit pengajuan cuti
cuti.delete - Hapus pengajuan cuti
cuti.approve - Approve pengajuan cuti

pengurus.create - Buat data pengurus
pengurus.view - Lihat data pengurus
pengurus.edit - Edit data pengurus
pengurus.delete - Hapus data pengurus
pengurus.export - Export data pengurus
pengurus.import - Import data pengurus
```

---

### 37. `roles` TABLE (Spatie Permission)
**Purpose:** Daftar roles/peran

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| id | bigint unsigned | NO | PRI | - | Primary Key |
| name | varchar(125) | NO | UNI | - | Nama role |
| guard_name | varchar(125) | NO | - | - | Guard name |
| created_at | timestamp | YES | - | NULL | Created timestamp |
| updated_at | timestamp | YES | - | NULL | Updated timestamp |

**Common Roles:**
- super_admin
- HR Manager
- Staff
- Kepala Unit
- Approver

---

### 38. `model_has_permissions` TABLE
**Purpose:** Relasi direct model ke permissions

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| permission_id | bigint unsigned | NO | FK | - | Referensi permission |
| model_type | varchar(255) | NO | - | - | Model type (User, dll) |
| model_id | bigint unsigned | NO | - | - | Model ID |

---

### 39. `model_has_roles` TABLE
**Purpose:** Relasi model ke roles

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| role_id | bigint unsigned | NO | FK | - | Referensi role |
| model_type | varchar(255) | NO | - | - | Model type |
| model_id | bigint unsigned | NO | - | - | Model ID |

---

### 40. `role_has_permissions` TABLE
**Purpose:** Relasi role ke permissions

| Column | Type | Nullable | Key | Default | Description |
|--------|------|----------|-----|---------|-------------|
| permission_id | bigint unsigned | NO | FK | - | Referensi permission |
| role_id | bigint unsigned | NO | FK | - | Referensi role |

---

---

## LOCATION/WILAYAH TABLES

### 41. `provinsi` TABLE
| Column | Type | Key | Description |
|--------|------|-----|-------------|
| id | bigint | PRI | Primary Key |
| nama_provinsi | varchar | - | Nama provinsi |

### 42. `kabupaten` TABLE
| Column | Type | Key | Description |
|--------|------|-----|-------------|
| id | bigint | PRI | Primary Key |
| provinsi_id | bigint | FK | Referensi provinsi |
| nama_kabupaten | varchar | - | Nama kabupaten |

### 43. `kecamatan` TABLE
| Column | Type | Key | Description |
|--------|------|-----|-------------|
| id | bigint | PRI | Primary Key |
| kabupaten_id | bigint | FK | Referensi kabupaten |
| nama_kecamatan | varchar | - | Nama kecamatan |

### 44. `desa` TABLE
| Column | Type | Key | Description |
|--------|------|-----|-------------|
| id | bigint | PRI | Primary Key |
| kecamatan_id | bigint | FK | Referensi kecamatan |
| nama_desa | varchar | - | Nama desa |

---

---

## FOREIGN KEY RELATIONSHIPS

### Key Relationships Map

```
users (1) ──────► Karyawan (*)
  ├──► CutiPengajuan (*)
  ├──► CutiSaldo (*)
  ├──► IzinPengajuan (*)
  └──► Pengurus (*)
  
karyawan (1) ──────► [Multiple Detail Tables]
  ├──► KaryawanPendidikan (*)
  ├──► KaryawanSertifikasi (*)
  ├──► KaryawanBahasa (*)
  ├──► KaryawanKontrak (*)
  ├──► KaryawanJabatan (*)
  ├──► KaryawanPengalamanKerja (*)
  ├──► KaryawanPelatihan (*)
  ├──► KaryawanPrestasi (*)
  ├──► KaryawanOrganisasi (*)
  ├──► KaryawanAnggotaKeluarga (*)
  ├──► KaryawanAkunBank (*)
  └──► KaryawanDokumen (*)

CutiPengajuan (1) ──────► CutiApproval (*)
CutiPengajuan (1) ──────► CutiApprovalHistory (*)

CutiSaldo (1) ──────► User (*)
CutiSaldo (1) ──────► TahunAjaran (*)

JamKerjaUnit (1) ──────► Unit (*)

LiburNasional (0..*) ──────► Provinsi (1)

AtasanUser (1) ──────► User [user_id, atasan_id, atasan_level2_id, atasan_level3_id]

UnitApprovalSettings (1) ──────► Unit (*)
UnitApprovalSettings (1) ──────► User [approval_level_1/2/3_id]

Pengurus (1) ──────► User (1)
Pengurus (1) ──────► MasterJabatan (1)

roles (1) ──────► role_has_permissions (*)
permissions (1) ──────► role_has_permissions (*)
```

---

## TIPS UNTUK MEMBUAT SEEDER

### 1. **Order of Seeder Execution**
```
1. PermissionSeeder (buat permissions)
2. RoleSeeder (buat roles dan assign permissions)
3. WilayahSeeder (wilayah/location)
4. MasterDataSeeder (master_unit, master_jabatan, dll)
5. UserSeeder (buat users)
6. KaryawanSeeder (buat karyawan records)
7. CutiSaldoSeeder (buat saldo cuti)
8. PengurusSeeder (buat pengurus)
9. AtasanUserSeeder (relasi atasan)
```

### 2. **Dependency Check**
Sebelum insert ke tabel, pastikan:
- Foreign key reference sudah ada
- Constraints dipenuhi
- Enum values valid

### 3. **Example: Basic Seeder Pattern**
```php
$user = User::updateOrCreate(
    ['email' => 'user@example.com'],
    [
        'name' => 'User Name',
        'password' => Hash::make('password123'),
    ]
);

$user->syncRoles(['Role Name']);

if (!$user->karyawan) {
    Karyawan::create([
        'user_id' => $user->id,
        'nip' => 'NIP-001',
        'full_name' => 'User Name',
        'gender' => 'perempuan',
        'tgl_masuk' => now(),
        'statuskaryawan_id' => 1,
        'agama' => 'Islam',
        'status_kawin' => 'lajang',
    ]);
}
```

### 4. **Useful Queries for Verification**
```php
// Check user created
User::with('karyawan', 'roles')->find(1);

// Check role permissions
Role::find(1)->permissions;

// Check atasan hierarchy
AtasanUser::with(['user', 'atasan', 'atasanLevel2', 'atasanLevel3'])->find(1);

// Check cuti saldo
CutiSaldo::with('user', 'tahunAjaran')->get();
```

---

## SEEDER CHECKLIST

- [ ] Permissions created via PermissionSeeder
- [ ] Roles created and assigned permissions via RoleSeeder
- [ ] Master data tables seeded (units, jabatan, etc.)
- [ ] Users created via UserSeeder
- [ ] Each user has corresponding Karyawan record
- [ ] Roles assigned to users via syncRoles()
- [ ] Atasan relationships set up
- [ ] CutiSaldo records created for each user
- [ ] Test data verified with queries

---

## NOTES

- **Soft Delete:** Tabel dengan `deleted_at` column support soft deletion
- **Audit Trail:** Banyak tabel punya `created_by` dan `updated_by` untuk tracking
- **Multi-Level Hierarchy:** AtasanUser supports 3 levels of approval
- **Enum Constraints:** Ensure enum values match exactly with database definition
- **Unique Constraints:** `email`, `nip`, `inisial` harus unique (watch out saat seeding ulang)

---

**Last Updated:** 2025-12-10
**Database:** hrnew12
**Total Tables:** 56
**Key Tables Documented:** 44
