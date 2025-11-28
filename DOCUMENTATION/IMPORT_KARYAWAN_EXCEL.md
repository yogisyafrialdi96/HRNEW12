# Import Karyawan Excel - Documentation

## Overview
The import Excel feature allows bulk importing of employee data from Excel files (.xlsx, .xls, .csv) into the system using Laravel Excel library (Maatwebsite Excel).

## Files Created/Modified

### 1. **KaryawanImport Class** (`app/Imports/KaryawanImport.php`)
The main import handler class that processes Excel data.

**Features:**
- Implements `ToCollection`, `WithHeadingRow`, `WithValidation` interfaces
- Supports multiple date formats (d/m/Y, d-m-Y, Y-m-d, Y/m/d, d M Y)
- Auto-generates initials from employee name
- Maps master data (Status Pegawai, Status Kawin, Golongan) automatically
- Handles file validation and error tracking
- Creates User account automatically if email is provided
- Tracks success and error rows with detailed error messages

**Key Methods:**
- `collection(Collection $rows)` - Processes the Excel rows
- `rules()` - Validation rules for fields
- `parseDate($dateString)` - Parses dates in multiple formats
- `generateInisial($name)` - Generates employee initials
- `getSuccessCount()` - Returns count of successfully imported records
- `getErrorRows()` - Returns array of rows with errors

### 2. **KaryawanTable Component** (`app/Livewire/Admin/Karyawan/KaryawanTable.php`)
Updated Livewire component with import functionality.

**New Properties:**
```php
public $showImportModal = false;
public $importFile = null;
public $importProgress = 0;
public $importResult = null;
```

**New Methods:**
- `openImportModal()` - Opens the import modal
- `closeImportModal()` - Closes modal and resets state
- `importKaryawan()` - Main import handler method

**Uses:**
- `Livewire\WithFileUploads` trait for secure file handling
- `Maatwebsite\Excel\Facades\Excel` for import processing

### 3. **View Template** (`resources/views/livewire/admin/karyawan/karyawan-table.blade.php`)
Updated with import button and modal.

**Components:**
- Import Excel button (green) next to Add Karyawan button
- Import Modal with:
  - File upload input
  - Template download link
  - File validation
  - Progress indicator
  - Result display with error details
  - Summary statistics (Success/Error/Total counts)

## Excel File Format

### Required Columns (Header Row):
```
nip                    | Full Name           | Email (optional)    | Gender
---|---|---|---
status_pegawai        | status_kawin        | golongan           | hp
whatsapp              | tempat_lahir        | tanggal_lahir      | agama
blood_type            | nik                 | npwp               | alamat_ktp
tgl_masuk             | jenis_karyawan      | tgl_karyawan_tetap | 
```

### Field Descriptions:
- **nip** (string, required if no full_name) - Employee ID number
- **full_name** (string, required) - Full name of employee
- **email** (string, optional) - Email address (creates user account if provided)
- **gender** (string) - Laki-laki/Perempuan or M/F
- **status_pegawai** (string) - Must match existing status in database
- **status_kawin** (string) - Must match existing status in database
- **golongan** (string) - Must match existing golongan in database
- **hp** (string) - Phone number
- **whatsapp** (string) - WhatsApp number
- **tempat_lahir** (string) - Place of birth
- **tanggal_lahir** (date) - Date of birth (supports: d/m/Y, d-m-Y, Y-m-d)
- **agama** (string) - Religion
- **blood_type** (string) - Blood type (A, B, O, AB, etc.)
- **nik** (string) - NIK number
- **npwp** (string) - NPWP tax number
- **alamat_ktp** (string) - Address from ID card
- **tgl_masuk** (date) - Start date
- **jenis_karyawan** (string) - Type (PNS, PPPK, Kontrak, PHL)
- **tgl_karyawan_tetap** (date, optional) - Date became permanent employee

### Validation Rules:
```php
'*.nip' => 'nullable|unique:karyawan,nip'
'*.email' => 'nullable|email'
'*.tanggal_lahir' => 'nullable|date_format:d/m/Y,d-m-Y,Y-m-d'
'*.tgl_masuk' => 'nullable|date_format:d/m/Y,d-m-Y,Y-m-d'
'*.gender' => 'nullable|in:M,F,Laki-laki,Perempuan'
'*.jenis_karyawan' => 'nullable|in:PNS,PPPK,Kontrak,PHL'
```

## Usage

### How to Import:
1. Click **"Import Excel"** button on Karyawan page
2. Download template (optional) to see the format
3. Select an Excel file (.xlsx, .xls, .csv)
4. Click **"Import Karyawan"** button
5. View results with success/error statistics

### Template File:
A template CSV file is available at `/public/template_import_karyawan.csv`

**Sample Data:**
```csv
nip,full_name,email,gender,status_pegawai,status_kawin,golongan,hp,whatsapp,tempat_lahir,tanggal_lahir,agama,blood_type,nik,npwp,alamat_ktp,tgl_masuk,jenis_karyawan,tgl_karyawan_tetap
12345,John Doe,john.doe@example.com,Laki-laki,Tetap,Menikah,Golongan IIb,08123456789,08123456789,Jakarta,01/01/1990,Islam,O+,1234567890123456,12.345.678.9-123.456,Jl. Merdeka No. 1,01/01/2020,PNS,15/01/2022
```

## Error Handling

### Import Result Display:
- **Success Count** - Number of employees successfully imported
- **Error Count** - Number of rows with errors
- **Error Details Table** - Shows:
  - Row number
  - NIP
  - Full Name
  - Error message

### Common Errors:
1. **Duplicate NIP** - NIP already exists in database
2. **Invalid Email Format** - Email doesn't match email format
3. **Invalid Date Format** - Date not in supported formats
4. **Invalid Gender** - Gender not M, F, Laki-laki, or Perempuan
5. **Master Data Not Found** - Status/Golongan doesn't match database
6. **Database Constraint Violation** - Violates unique/foreign key constraints

## Features

✅ **Bulk Import** - Import multiple employees at once (up to 5MB file size)
✅ **Auto User Creation** - Creates user account if email provided
✅ **Master Data Mapping** - Automatically links to Status, Golongan, etc.
✅ **Date Format Flexibility** - Supports multiple date formats
✅ **Validation** - Comprehensive field and data validation
✅ **Error Tracking** - Detailed error reporting with row numbers
✅ **Transaction Safe** - Each employee created in separate transaction
✅ **Audit Trail** - Records created_by and updated_by information
✅ **Template Download** - Pre-made template file for reference

## Performance Notes

- Maximum file size: 5MB
- Recommended rows per import: 100-500 for optimal performance
- Each row is processed individually with proper error handling
- Successfully imported rows are saved even if some rows have errors
- Empty rows are automatically skipped

## Future Enhancements

- [ ] Batch update existing employees (merge mode)
- [ ] Export current data to Excel
- [ ] Mapping customization UI
- [ ] File upload history/audit log
- [ ] Scheduled imports via cron
- [ ] Email notifications on import completion
