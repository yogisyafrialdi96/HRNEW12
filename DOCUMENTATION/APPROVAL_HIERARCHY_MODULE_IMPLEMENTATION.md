# IMPLEMENTASI APPROVAL HIERARCHY MODULE - DOKUMENTASI LENGKAP

**Tanggal:** 5 Desember 2025  
**Status:** ✅ IMPLEMENTASI SELESAI  
**Framework:** Laravel 12 + Livewire v3 + TailwindCSS

---

## 📋 RINGKASAN PROYEK

Modul Approval Hierarchy (Atasan Management) dirancang untuk mengelola struktur hirarki approval dalam sistem izin dan cuti karyawan. Modul ini memungkinkan:

✅ Pengelolaan relasi user-atasan dengan 4 level approval  
✅ Template workflow yang dapat dikustomisasi  
✅ Mapping template ke unit dan jenis izin  
✅ Audit trail lengkap untuk setiap perubahan  
✅ Soft delete dengan history tracking  

---

## 🏗️ ARSITEKTUR DATABASE

### Tabel-tabel yang Dibuat

```
1. atasan_user
   - id (PK)
   - user_id (FK → users)
   - atasan_id (FK → users)
   - level (1-4)
   - start_date
   - end_date
   - is_active
   - created_by, updated_by
   - deleted_at (soft delete)

2. atasan_user_history
   - id (PK)
   - atasan_user_id (FK)
   - user_id, atasan_id, level
   - action (created, updated, deleted, deactivated)
   - changed_by (FK → users)
   - old_data, new_data (JSON)
   - reason
   - created_at

3. approval_templates
   - id (PK)
   - nama_template
   - deskripsi
   - unit_id (FK → master_unit, nullable)
   - is_default
   - created_by, updated_by
   - deleted_at (soft delete)

4. approval_template_details
   - id (PK)
   - approval_template_id (FK)
   - level
   - jabatan
   - created_at

5. unit_approval_settings
   - id (PK)
   - unit_id (FK → master_unit)
   - jenis_izin (izin|cuti|sakit)
   - approval_template_id (FK)
   - is_active
   - catatan
   - created_by, updated_by
   - created_at, updated_at
```

---

## 📁 STRUKTUR FILE & FOLDER

```
app/
├── Livewire/
│   └── Admin/
│       └── Atasan/
│           ├── AtasanUserIndex.php          (List atasan users)
│           ├── AtasanUserForm.php            (Create/Edit atasan users)
│           ├── ApprovalTemplateIndex.php    (List templates)
│           ├── ApprovalTemplateForm.php     (Create/Edit templates)
│           ├── UnitApprovalSettingIndex.php (List settings)
│           └── UnitApprovalSettingForm.php  (Create/Edit settings)
│
├── Models/
│   └── Atasan/
│       ├── AtasanUser.php
│       ├── AtasanUserHistory.php
│       ├── ApprovalTemplate.php
│       ├── ApprovalTemplateDetail.php
│       └── UnitApprovalSetting.php
│
database/
├── migrations/
│   └── 2025_12_05_025643_create_atasan_users_table.php (All 5 tables)
│
└── seeders/
    ├── PermissionSeeder.php                 (Contains atasan permissions)
    └── AtasanUserSeeder.php                 (Sample data)

resources/
└── views/
    └── livewire/
        └── admin/
            └── atasan/
                ├── atasan-user-index.blade.php
                ├── atasan-user-form.blade.php
                ├── approval-template-index.blade.php
                ├── approval-template-form.blade.php
                ├── unit-approval-setting-index.blade.php
                └── unit-approval-setting-form.blade.php

routes/
└── web.php                                  (Routes dengan middleware)
```

---

## 🔌 ROUTE & ENDPOINTS

### Route Group: `/admin/atasan`

```php
Route::middleware('permission:users.view')->group(function () {
    Route::prefix('atasan')->name('atasan.')->group(function () {
        
        // Atasan Users Routes
        Route::prefix('users')->name('users.')->group(function () {
            GET  /admin/atasan/users                 → AtasanUserIndex      (List)
            GET  /admin/atasan/users/create          → AtasanUserForm       (Create)
            GET  /admin/atasan/users/{id}/edit       → AtasanUserForm       (Edit)
        });
        
        // Approval Templates Routes
        Route::prefix('templates')->name('templates.')->group(function () {
            GET  /admin/atasan/templates             → ApprovalTemplateIndex (List)
            GET  /admin/atasan/templates/create      → ApprovalTemplateForm  (Create)
            GET  /admin/atasan/templates/{id}/edit   → ApprovalTemplateForm  (Edit)
        });
        
        // Unit Approval Settings Routes
        Route::prefix('unit-settings')->name('unit-settings.')->group(function () {
            GET  /admin/atasan/unit-settings         → UnitApprovalSettingIndex (List)
            GET  /admin/atasan/unit-settings/create  → UnitApprovalSettingForm  (Create)
            GET  /admin/atasan/unit-settings/{id}/edit → UnitApprovalSettingForm (Edit)
        });
    });
});
```

---

## 🔐 PERMISSION & AUTHORIZATION

### Permission yang Tersedia

Setiap komponen menggunakan permission dari Spatie Permission:

```php
// Dalam PermissionSeeder.php (lines 163-180)
$permissions = [
    // Existing permissions (digunakan oleh Atasan modules)
    'users.view',
    'users.create',
    'users.edit',
    'users.delete',
    
    // Optional: Atasan-specific permissions (tidak digunakan sekarang)
    'atasan.view',
    'atasan.create',
    'atasan.edit',
    'atasan.delete',
    'atasan_template.view',
    'atasan_template.create',
    'atasan_template.edit',
    'atasan_template.delete',
    'atasan_unit_setting.view',
    'atasan_unit_setting.create',
    'atasan_unit_setting.edit',
    'atasan_unit_setting.delete',
];
```

### Penggunaan dalam Komponen

```php
// AtasanUserIndex.php
public function delete(AtasanUser $model) {
    $this->authorize('users.delete');  // Check permission
    // ...
}

public function toggleActive(AtasanUser $model) {
    $this->authorize('users.edit');    // Check permission
    // ...
}
```

---

## 🎯 LIVEWIRE COMPONENTS - DETAIL IMPLEMENTASI

### 1. AtasanUserIndex - List & Manage Atasan Users

**Purpose:** Tampilkan daftar semua atasan users dengan fitur:
- Search by user name/email
- Filter by level (1-4)
- Pagination (15 items per page)
- Soft delete dengan history logging
- Toggle active/inactive status
- Edit & Delete actions

**Key Methods:**
```php
#[\Livewire\Attributes\Computed]
public function atasanUsers()
    → Query dengan filtering, sorting, pagination

public function delete(AtasanUser $model)
    → Soft delete + history logging

public function toggleActive(AtasanUser $model)
    → Toggle is_active + history logging

public function clearFilters()
    → Reset search, filter, halaman
```

**Data yang Ditampilkan:**
- User Name, Email
- Atasan Name, Email
- Level (1-4)
- Status (Aktif/Nonaktif)
- Created Date
- Actions (Edit, Toggle, Delete)

**Stats Widget:**
- Total Atasan Users
- Level Distribution (1-4)
- Active Count

---

### 2. AtasanUserForm - Create/Edit Atasan User

**Purpose:** Form untuk create/edit relasi user-atasan

**Key Methods:**
```php
public function mount(?AtasanUser $model = null)
    → Load existing data for edit mode

public function save()
    → Validate & save + history logging
    → Prevent duplicate user per level
    → Ensure different user vs atasan
    → Validate date ordering (start < end)

public function render()
    → Return view dengan form fields
```

**Form Fields:**
- User Selection (dropdown)
- Atasan Selection (dropdown, exclude current user)
- Level (1-4 select)
- Start Date (required)
- End Date (optional)
- Status Checkbox (is_active)
- Notes Textarea

**Validasi:**
```php
'user_id' => 'required|exists:users,id|unique:atasan_user,user_id,NULL,id,level,' . $this->level',
'atasan_id' => 'required|exists:users,id|different:user_id',
'level' => 'required|integer|between:1,4',
'start_date' => 'required|date',
'end_date' => 'nullable|date|after:start_date',
'notes' => 'nullable|string|max:500',
```

**Audit Trail:**
```php
AtasanUserHistory::create([
    'atasan_user_id' => $model->id,
    'user_id' => $model->user_id,
    'atasan_id' => $model->atasan_id,
    'level' => $model->level,
    'action' => 'created|updated',
    'changed_by' => auth()->id(),
    'old_data' => json_encode($oldData),
    'new_data' => json_encode($model->toArray()),
    'reason' => 'Manual entry',
]);
```

---

### 3. ApprovalTemplateIndex - List & Manage Templates

**Purpose:** Tampilkan daftar approval templates dengan fitur:
- Search by template name
- Filter by unit
- Pagination (15 items)
- Set as default
- Soft delete
- Edit & Delete actions

**Key Methods:**
```php
#[\Livewire\Attributes\Computed]
public function templates()
    → Query templates dengan filtering & pagination

public function setDefault(ApprovalTemplate $model)
    → Deactivate old default, activate new one

public function delete(ApprovalTemplate $model)
    → Soft delete template
    → Check if template is still in use
```

**Stats Widget:**
- Total Templates
- Default Templates Count
- Unit-Specific Templates Count

**Table Columns:**
- Nama Template
- Deskripsi (truncated)
- Unit (badge)
- Default Status
- Created Date
- Actions

---

### 4. ApprovalTemplateForm - Create/Edit Template

**Purpose:** Form untuk create/edit approval templates

**Key Methods:**
```php
public function mount(?ApprovalTemplate $model = null)
    → Load existing template data

public function save()
    → Validate & save template
    → Auto-remove previous default if is_default = true
    → Create history log

public function render()
    → Return form view
```

**Form Fields:**
- Nama Template (unique)
- Deskripsi (textarea)
- Unit Selection (optional, for global templates)
- Set as Default (checkbox)
- Approval Details Section (read-only display)

**Validasi:**
```php
'nama_template' => 'required|string|max:100|unique:approval_templates,nama_template,' . ($this->model?->id ?? 'NULL'),
'deskripsi' => 'nullable|string|max:500',
'unit_id' => 'nullable|exists:master_unit,id',
'is_default' => 'boolean',
```

---

### 5. UnitApprovalSettingIndex - List Unit Settings

**Purpose:** Tampilkan daftar konfigurasi unit approval dengan fitur:
- Filter by unit
- Filter by jenis_izin (izin|cuti|sakit)
- Pagination
- Soft delete
- Edit actions

**Key Methods:**
```php
#[\Livewire\Attributes\Computed]
public function settings()
    → Query settings dengan filters & pagination

public function delete(UnitApprovalSetting $model)
    → Soft delete setting
```

**Stats Widget:**
- Total Settings
- Active Settings
- Units Configured Count

**Table Columns:**
- Unit Name
- Jenis Izin (badge)
- Template Name
- Status (Aktif/Nonaktif)
- Created Date
- Actions

---

### 6. UnitApprovalSettingForm - Create/Edit Unit Setting

**Purpose:** Form untuk konfigurasi unit-specific approval templates

**Key Methods:**
```php
public function mount(?UnitApprovalSetting $model = null)
    → Load existing setting data

public function save()
    → Validate & save setting
    → Duplicate prevention: only 1 active setting per (unit + jenis_izin)
    → Auto-deactivate previous active setting

public function render()
    → Return form view
```

**Form Fields:**
- Unit Selection (required)
- Jenis Izin Selection (izin|cuti|sakit)
- Template Approval Selection
- Status Checkbox (is_active)
- Catatan (textarea)

**Validasi:**
```php
'unit_id' => 'required|exists:master_unit,id',
'jenis_izin' => 'required|in:izin,cuti,sakit',
'approval_template_id' => 'required|exists:approval_templates,id',
'is_active' => 'boolean',
'catatan' => 'nullable|string|max:500',
```

**Duplicate Prevention:**
```php
// Jika is_active = true, deactivate setting lain untuk kombinasi yang sama
if ($this->is_active) {
    UnitApprovalSetting::where('unit_id', $this->unit_id)
        ->where('jenis_izin', $this->jenis_izin)
        ->where('is_active', true)
        ->update(['is_active' => false]);
}
```

---

## 🎨 BLADE TEMPLATE DESIGN PATTERN

Semua blade template mengikuti design pattern dari karyawan-table dengan:

### Layout Structure

```blade
<div>
    <!-- Header Section -->
    - Judul & Deskripsi
    - Add/Create Button
    
    <!-- Search & Filter Section -->
    - Search Input
    - Filter Dropdowns
    - Clear Filters Button
    
    <!-- Stats Widget Section -->
    - Grid layout dengan stat cards
    - Icon + count display
    
    <!-- Main Table -->
    - Responsive table with hover effects
    - Action buttons (Edit, Delete, Toggle)
    - Empty state message
    
    <!-- Pagination -->
    - Links component
</div>
```

### Styling Components

```blade
<!-- Colors by Purpose -->
Blue (Primary):     User/Data related
Purple:             Templates/Workflows
Emerald (Green):    Active/Success states
Red:                Delete/Error states
Indigo/Teal:        Form-related pages
Gray:               Neutral elements

<!-- Dark Mode Support -->
- dark:bg-gray-800
- dark:text-white
- dark:border-gray-700
- dark:hover:bg-gray-700/50
```

### Form Pattern

```blade
<div class="bg-white rounded-lg shadow-md">
    <!-- Header with gradient -->
    <div class="px-6 py-5 bg-gradient-to-r...">
        <h3>Form Title</h3>
    </div>
    
    <!-- Form Content -->
    <div class="px-6 py-8 space-y-6">
        @foreach input field
            - Label
            - Input element
            - Error message
    </div>
    
    <!-- Footer with actions -->
    <div class="px-6 py-4 border-t...">
        - Cancel button
        - Save button (with loading state)
    </div>
</div>
```

---

## 🔄 DATA FLOW & BUSINESS LOGIC

### Flow 1: Create Atasan User

```
User navigates to /admin/atasan/users/create
    ↓
AtasanUserForm component mounts (empty)
    ↓
User fills form (user, atasan, level, dates)
    ↓
Click Save button
    ↓
Livewire validate:
  - user_id exists & unique per level
  - atasan_id exists & different from user_id
  - dates properly ordered
    ↓
Create AtasanUser record
    ↓
Log to AtasanUserHistory with action='created'
    ↓
Dispatch success notification
    ↓
Redirect to index page
```

### Flow 2: Update Atasan User

```
User navigates to /admin/atasan/users/{id}/edit
    ↓
AtasanUserForm mounts with $model
    ↓
Form fields pre-filled with existing data
    ↓
User modifies fields
    ↓
Click Save button
    ↓
Store old_data before update
    ↓
Validate new data
    ↓
Update AtasanUser record
    ↓
Log to AtasanUserHistory with action='updated'
    ↓
Dispatch success notification
    ↓
Redirect to index page
```

### Flow 3: Toggle Active Status

```
User clicks "Nonaktifkan" or "Aktifkan" button
    ↓
toggleActive() method called
    ↓
Check user.edit permission
    ↓
Toggle is_active value
    ↓
Store old value in history
    ↓
Log to AtasanUserHistory with action='deactivated'|'updated'
    ↓
Dispatch notification
    ↓
Component reactivity refreshes table
```

### Flow 4: Soft Delete with History

```
User clicks Delete button
    ↓
Browser confirm dialog "Yakin ingin menghapus?"
    ↓
delete() method called
    ↓
Check user.delete permission
    ↓
Create history record with action='deleted'
    ↓
Call $model->delete() (Eloquent soft delete)
    ↓
Dispatch success notification
    ↓
Table reactivity removes row
```

### Flow 5: Set Default Template

```
User clicks "Set Default" button on template
    ↓
setDefault() method called
    ↓
Fetch current default template
    ↓
Update old default: is_default = false
    ↓
Update new template: is_default = true
    ↓
Dispatch success notification
    ↓
Table reactivity refreshes
```

### Flow 6: Unit Approval Setting - Duplicate Prevention

```
User clicks Save on UnitApprovalSettingForm
    ↓
Check if is_active = true
    ↓
Query for existing active setting:
   WHERE unit_id = X
   AND jenis_izin = Y
   AND is_active = true
    ↓
If exists, deactivate it
    ↓
Create/Update new setting
    ↓
Save and redirect
```

---

## 💾 DATABASE MIGRATIONS

### Migration File Location
`database/migrations/2025_12_05_025643_create_atasan_users_table.php`

### Tables Created (All 5):

1. **atasan_user**
   - Relasi user-atasan dengan level approval
   - Soft delete support
   - Timestamp tracking

2. **atasan_user_history**
   - Audit trail lengkap
   - Track perubahan (old_data, new_data)
   - Reason tracking

3. **approval_templates**
   - Workflow template configuration
   - Unit-specific atau global
   - Default template tracking

4. **approval_template_details**
   - Level dan jabatan per template
   - Reusable untuk berbagai izin

5. **unit_approval_settings**
   - Map unit + jenis_izin → template
   - Active/inactive tracking
   - One-active-only per combination

---

## 📊 SEEDER DATA

### AtasanUserSeeder

Membuat sample data:
- 10+ Atasan User entries dengan berbagai level
- 5+ History entries menunjukkan audit trail
- 3+ Approval Templates
- Template details dengan level configurations
- 5+ Unit Approval Settings

**Run Seeder:**
```bash
php artisan db:seed --class=AtasanUserSeeder
```

---

## 🧪 TESTING CHECKLIST

### Unit Tests to Create:
- [ ] AtasanUserIndex filtering & pagination
- [ ] AtasanUserForm validation rules
- [ ] Duplicate prevention logic
- [ ] History logging on CRUD operations
- [ ] Permission authorization checks
- [ ] ApprovalTemplate default management
- [ ] UnitApprovalSetting one-active-only
- [ ] Soft delete and restore functionality

### Integration Tests:
- [ ] Full create-edit-delete workflow
- [ ] Search functionality
- [ ] Filter combinations
- [ ] History tracking accuracy

### Manual Testing:
- [ ] Navigate all 3 modules (users, templates, settings)
- [ ] Create entries in each module
- [ ] Edit entries
- [ ] Delete entries
- [ ] Check history logs
- [ ] Verify permissions work
- [ ] Test dark mode styling
- [ ] Test responsive design

---

## 🚀 DEPLOYMENT CHECKLIST

```
✅ Database migrations created & executed
✅ Livewire components created with full logic
✅ Blade templates with responsive design
✅ Routes integrated into web.php
✅ Permissions seeded in database
✅ Authorization checks implemented
✅ History logging functional
✅ Soft delete working
✅ Notifications using Livewire dispatch
✅ Pagination implemented
✅ Dark mode supported
✅ Error handling with validation messages
✅ Loading states on buttons
✅ Confirm dialogs on delete

Next Steps:
- [ ] Create unit & integration tests
- [ ] Document API endpoints (if needed)
- [ ] Setup monitoring for audit trail
- [ ] Create user documentation
- [ ] Training for admin users
```

---

## 📝 NOTES FOR FUTURE DEVELOPMENT

### Potential Enhancements:
1. **Bulk Operations**
   - Bulk assign atasan to multiple users
   - Bulk import from CSV

2. **Advanced Filtering**
   - Filter by date range (start_date, end_date)
   - Filter by active/deleted status
   - Advanced search with multiple criteria

3. **Reporting**
   - Hierarchy visualization (org chart)
   - Approval workflow summary
   - History analytics

4. **Notifications**
   - Notify when atasan relationship changes
   - Alert on approval template changes

5. **API Endpoints**
   - RESTful API for external integrations
   - GraphQL for complex queries

6. **Workflow Automation**
   - Auto-assign approvers based on template
   - Auto-escalate pending approvals
   - Webhook triggers

---

## 🔗 RELATED MODELS & MIGRATIONS

**User Model:**
- Has many atasan_user records
- Can be user_id or atasan_id
- Relations: hasMany(AtasanUser), hasMany(AtasanUserHistory)

**Unit Model (master_unit):**
- Has many approval_templates
- Has many unit_approval_settings
- Relations: hasMany(ApprovalTemplate), hasMany(UnitApprovalSetting)

**Relationships:**
```
User
  ├── hasMany AtasanUser (as user_id)
  ├── hasMany AtasanUser (as atasan_id)
  └── hasMany AtasanUserHistory

Unit
  ├── hasMany ApprovalTemplate
  └── hasMany UnitApprovalSetting

ApprovalTemplate
  ├── belongsTo Unit
  ├── hasMany ApprovalTemplateDetail
  └── hasMany UnitApprovalSetting

UnitApprovalSetting
  ├── belongsTo Unit
  ├── belongsTo ApprovalTemplate
  └── belongsTo User (created_by, updated_by)
```

---

## 📞 SUPPORT & TROUBLESHOOTING

### Common Issues:

**1. Toast Notifications Not Showing**
- Solution: Use `$this->dispatch('notify', type: 'success', message: '...')`
- Ensure Toast listener is available in layout

**2. Model Not Found on Edit**
- Check route model binding in routes/web.php
- Verify model class in component

**3. Permission Denied**
- Check if user has `users.view|create|edit|delete` permission
- Verify permission was seeded with `php artisan db:seed --class=PermissionSeeder`

**4. Duplicate Entry Error**
- Validate unique constraints are correct
- Check duplicate prevention logic in form

---

**Documentation Created:** 2025-12-05  
**Version:** 1.0  
**Last Updated:** 2025-12-05
