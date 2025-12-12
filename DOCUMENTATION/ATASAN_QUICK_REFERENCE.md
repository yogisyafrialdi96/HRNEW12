# QUICK REFERENCE - Approval Hierarchy Module

## 🎯 Module Overview

**Purpose:** Manage approval hierarchy, templates, and unit-specific approval configurations for leave/permission requests.

**Access:** `/admin/atasan/`  
**Requires Permission:** `users.view` (middleware)  
**Framework:** Laravel 12 + Livewire v3 + TailwindCSS

---

## 📍 Routes Map

```
/admin/atasan/users
├── GET /                       List all atasan users
├── GET /create                 Create new atasan user
└── GET /{id}/edit              Edit existing atasan user

/admin/atasan/templates
├── GET /                       List all templates
├── GET /create                 Create new template
└── GET /{id}/edit              Edit existing template

/admin/atasan/unit-settings
├── GET /                       List all unit settings
├── GET /create                 Create new setting
└── GET /{id}/edit              Edit existing setting
```

---

## 🎨 Component Structure

### Index Components (List Pages)

| Component | URL | Features |
|-----------|-----|----------|
| **AtasanUserIndex** | `/atasan/users` | Search, Filter by level, Pagination, Stats |
| **ApprovalTemplateIndex** | `/atasan/templates` | Search, Filter by unit, Set default, Pagination |
| **UnitApprovalSettingIndex** | `/atasan/unit-settings` | Filter unit & jenis_izin, Pagination, Stats |

**Common Methods in Index Components:**
```php
#[\Livewire\Attributes\Computed]
public function items()  // Filtered, paginated items

public function delete($id)  // Soft delete

public function clearFilters()  // Reset filters

public function toggleActive($id)  // For status changes

public function setDefault($id)  // For templates
```

### Form Components (Create/Edit Pages)

| Component | URL | Fields |
|-----------|-----|--------|
| **AtasanUserForm** | `/atasan/users/create` or `/{id}/edit` | User, Atasan, Level, Dates, Notes |
| **ApprovalTemplateForm** | `/atason/templates/create` or `/{id}/edit` | Name, Description, Unit, Default |
| **UnitApprovalSettingForm** | `/atasan/unit-settings/create` or `/{id}/edit` | Unit, Jenis Izin, Template, Notes |

**Common Methods in Form Components:**
```php
public function mount(?Model $model = null)  // Load data for edit

public function save()  // Validate & save

public function render()  // Return view
```

---

## 📊 Data Models

### AtasanUser
```php
$atasanUser = AtasanUser::find($id);
$atasanUser->user;          // Belongs to User
$atasanUser->atasan;        // Belongs to User (atasan relationship)
$atasanUser->histories;     // Has many AtasanUserHistory
$atasanUser->level;         // 1-4
$atasanUser->is_active;     // Boolean
```

### ApprovalTemplate
```php
$template = ApprovalTemplate::find($id);
$template->unit;            // Belongs to Unit (nullable)
$template->details;         // Has many ApprovalTemplateDetail
$template->settings;        // Has many UnitApprovalSetting
$template->is_default;      // Boolean
```

### UnitApprovalSetting
```php
$setting = UnitApprovalSetting::find($id);
$setting->unit;             // Belongs to Unit
$setting->template;         // Belongs to ApprovalTemplate
$setting->jenis_izin;       // 'izin' | 'cuti' | 'sakit'
$setting->is_active;        // Boolean
```

---

## 🔐 Authorization

### Check Permission in Components
```php
public function save() {
    $this->authorize('users.create');  // For create
    $this->authorize('users.edit');    // For edit
    $this->authorize('users.delete');  // For delete
}
```

### Route Middleware
```php
Route::middleware('permission:users.view')->group(function () {
    // All routes protected
});
```

---

## 💾 Common Operations

### Create Atasan User
```php
$atasan = AtasanUser::create([
    'user_id' => 1,
    'atasan_id' => 2,
    'level' => 1,
    'start_date' => '2025-01-01',
    'is_active' => true,
    'created_by' => auth()->id(),
]);

// Log to history
AtasanUserHistory::create([
    'atasan_user_id' => $atasan->id,
    'action' => 'created',
    'changed_by' => auth()->id(),
]);
```

### Create Approval Template
```php
$template = ApprovalTemplate::create([
    'nama_template' => 'Template Izin Tahunan',
    'deskripsi' => 'Untuk pengajuan cuti tahunan',
    'unit_id' => null,  // Global
    'is_default' => false,
    'created_by' => auth()->id(),
]);
```

### Create Unit Approval Setting
```php
// Auto-deactivate previous active
UnitApprovalSetting::where('unit_id', 1)
    ->where('jenis_izin', 'cuti')
    ->where('is_active', true)
    ->update(['is_active' => false]);

// Create new
$setting = UnitApprovalSetting::create([
    'unit_id' => 1,
    'jenis_izin' => 'cuti',
    'approval_template_id' => $templateId,
    'is_active' => true,
    'created_by' => auth()->id(),
]);
```

---

## 🔍 Query Examples

### Get Active Atasan for User
```php
$atasan = AtasanUser::where('user_id', $userId)
    ->where('is_active', true)
    ->orderBy('level')
    ->first();
```

### Get Default Template
```php
$template = ApprovalTemplate::where('is_default', true)->first();
```

### Get Approval Chain for Specific Izin
```php
$setting = UnitApprovalSetting::where('unit_id', $unitId)
    ->where('jenis_izin', 'cuti')
    ->where('is_active', true)
    ->with('template.details')
    ->first();
```

### Get Approval History
```php
$history = AtasanUserHistory::where('atasan_user_id', $atasanUserId)
    ->orderBy('created_at', 'desc')
    ->get();
```

---

## 📝 Validation Rules

### AtasanUserForm
```php
'user_id' => 'required|exists:users,id|unique:atasan_user,user_id,NULL,id,level,' . $this->level,
'atasan_id' => 'required|exists:users,id|different:user_id',
'level' => 'required|integer|between:1,4',
'start_date' => 'required|date',
'end_date' => 'nullable|date|after:start_date',
```

### ApprovalTemplateForm
```php
'nama_template' => 'required|string|max:100|unique:approval_templates,nama_template,' . ($this->model?->id ?? 'NULL'),
'deskripsi' => 'nullable|string|max:500',
'unit_id' => 'nullable|exists:master_unit,id',
'is_default' => 'boolean',
```

### UnitApprovalSettingForm
```php
'unit_id' => 'required|exists:master_unit,id',
'jenis_izin' => 'required|in:izin,cuti,sakit',
'approval_template_id' => 'required|exists:approval_templates,id',
'is_active' => 'boolean',
```

---

## 🎨 UI Components Used

### Form Elements
- Text Input
- Select Dropdown
- Textarea
- Checkbox
- Date Input
- Date Range

### Buttons
- Primary (Blue) - Save/Create
- Secondary (Gray) - Cancel/Back
- Danger (Red) - Delete
- Info (Various colors) - Edit, Toggle, Set Default

### Notifications
```php
$this->dispatch('notify', type: 'success', message: 'Data saved');
$this->dispatch('notify', type: 'error', message: 'Error occurred');
```

### Confirmation Dialogs
```blade
wire:confirm="Are you sure?"
```

---

## 🔄 Workflow Patterns

### Create Pattern
```
View Form → User fills → Validate → Save → History Log → Notify → Redirect
```

### Edit Pattern
```
Load Form → Pre-fill data → User edits → Validate → Save → History Log → Notify → Redirect
```

### Delete Pattern
```
Click Delete → Confirm Dialog → Soft Delete → History Log → Notify → Table Refresh
```

### Toggle Pattern
```
Click Toggle → Update status → History Log → Notify → Table Refresh
```

---

## 📱 Responsive Design

### Breakpoints Used
- Mobile: Default (< 640px)
- SM: >= 640px
- MD: >= 768px
- LG: >= 1024px

### Mobile Optimizations
- Collapsible filters
- Stacked form fields
- Full-width buttons
- Truncated text with tooltips

---

## 🌙 Dark Mode

All components support dark mode with:
- `dark:bg-gray-800` backgrounds
- `dark:text-white` text colors
- `dark:border-gray-700` borders
- `dark:hover:bg-gray-700/50` interactions

---

## 🧪 Testing

### Test Create Flow
```
1. Navigate to /admin/atasan/users/create
2. Fill form with valid data
3. Click Save
4. Check database for new record
5. Check history log created
6. Verify notification shown
```

### Test Edit Flow
```
1. Navigate to /admin/atasan/users/{id}/edit
2. Modify one field
3. Click Save
4. Check database for updated record
5. Check history log with old_data
6. Verify notification shown
```

### Test Permission
```
1. User without users.create permission
2. Try to create → Should get 403
3. Try to access /admin/atasan/users/create → Should redirect
```

---

## 🐛 Debugging Tips

### Component State
```php
// In component
dd($this->search);          // Check public property
dd($this->items);           // Check computed property
```

### Query Debug
```php
// In Livewire component
$items->toQuery()->dd();    // Show SQL query
```

### Permission Debug
```bash
php artisan permission:list
php artisan cache:clear
```

### Route Debug
```bash
php artisan route:list | grep atasan
```

---

## 📚 Related Documentation

- Full Implementation: `DOCUMENTATION/APPROVAL_HIERARCHY_MODULE_IMPLEMENTATION.md`
- Database Design: `DOCUMENTATION/LEAVE_PERMISSION_MODULE_DESIGN.md`
- API Reference: (To be added)
- User Guide: (To be added)

---

## 🚀 Production Checklist

- [x] All components created
- [x] All views created
- [x] Routes registered
- [x] Permissions seeded
- [x] Migrations executed
- [x] Models with relationships
- [x] Authorization checks
- [x] History logging
- [x] Validation rules
- [x] Error handling
- [x] Dark mode support
- [x] Responsive design
- [ ] Unit tests
- [ ] Integration tests
- [ ] End-to-end tests
- [ ] Performance testing
- [ ] Security audit
- [ ] Documentation review

---

**Quick Reference Version:** 1.0  
**Last Updated:** 2025-12-05  
**Author:** Development Team
