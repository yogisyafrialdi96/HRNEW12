# Desain Module Pengajuan Izin & Cuti dengan Hirarki Approval

## 📋 Daftar Isi
1. [Overview](#overview)
2. [Struktur Database](#struktur-database)
3. [Alur Approval & Notification](#alur-approval--notification)
4. [Entity Relationship Diagram](#entity-relationship-diagram)
5. [Implementasi Detail](#implementasi-detail)
6. [Queries & Scopes](#queries--scopes)

---

## Overview

Module ini dirancang untuk mengelola:
- **Pengajuan Izin** (Sakit, Keperluan, Dinas, dll)
- **Pengajuan Cuti** (Tahunan, Bersama, Khusus, dll)
- **Hirarki Approval** (Multiple levels: Atasan Langsung → Manager → HR → Director)
- **Notification System** (Email, In-app notifications untuk setiap status change)
- **Historical Tracking** (Riwayat approval dengan timestamp & notes)

---

## Struktur Database

### 1. `leave_types` (Master Data - Jenis Izin/Cuti)

```sql
CREATE TABLE leave_types (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,           -- 'SICK', 'ANNUAL', 'PERMIT', 'OFFICIAL', dll
    name VARCHAR(100) NOT NULL,                 -- 'Izin Sakit', 'Cuti Tahunan', 'Izin Keluar', dll
    category ENUM('leave', 'permission') NOT NULL, -- Membedakan izin vs cuti
    quota_per_year INT,                         -- NULL jika tidak terbatas
    requires_attachment BOOLEAN DEFAULT FALSE,  -- Perlu dokumen pendukung
    color_badge VARCHAR(20) DEFAULT 'blue',    -- 'red', 'blue', 'green', 'yellow', dll
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_category (category),
    INDEX idx_is_active (is_active)
);
```

**Contoh Data:**
```
| ID | Code        | Name                | Category   | Quota | Badge  |
|----|-------------|-------------------|------------|-------|--------|
| 1  | SICK        | Izin Sakit        | permission | NULL  | red    |
| 2  | PERMIT      | Izin Keluar       | permission | NULL  | blue   |
| 3  | OFFICIAL    | Dinas             | permission | NULL  | yellow |
| 4  | STUDY       | Tugas Belajar     | permission | NULL  | purple |
| 5  | ANNUAL      | Cuti Tahunan      | leave      | 12    | green  |
| 6  | SPECIAL     | Cuti Khusus       | leave      | 3     | blue   |
| 7  | TOGETHER    | Cuti Bersama      | leave      | NULL  | orange |
```

---

### 2. `leave_quotas` (Track Quota Penggunaan Per Karyawan Per Tahun)

```sql
CREATE TABLE leave_quotas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    karyawan_id BIGINT NOT NULL,
    leave_type_id BIGINT NOT NULL,
    year INT NOT NULL,
    total_quota INT NOT NULL,                    -- Quota tahunan
    used_quota INT DEFAULT 0,                    -- Sudah digunakan
    remaining_quota INT GENERATED ALWAYS AS (total_quota - used_quota) STORED,
    carry_over_quota INT DEFAULT 0,              -- Sisa dari tahun lalu
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    FOREIGN KEY (leave_type_id) REFERENCES leave_types(id),
    UNIQUE KEY unique_karyawan_type_year (karyawan_id, leave_type_id, year),
    INDEX idx_karyawan_year (karyawan_id, year),
    INDEX idx_year (year)
);
```

---

### 3. `approval_hierarchies` (Definisikan Struktur Hirarki Approval)

```sql
CREATE TABLE approval_hierarchies (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    leave_type_id BIGINT NOT NULL,              -- Jenis izin/cuti
    department_id BIGINT,                        -- Spesifik departemen (NULL = all)
    jabatan_id BIGINT,                           -- Spesifik jabatan (NULL = all)
    level INT NOT NULL,                          -- 1, 2, 3, 4 (urutan approval)
    approver_role VARCHAR(50),                  -- Role yang boleh approve: 'direct_manager', 'manager', 'hr', 'director'
    approver_jabatan_id BIGINT,                 -- Approver dari jabatan tertentu
    min_day_requirement INT DEFAULT 0,          -- Min hari untuk approval level ini
    max_day_requirement INT DEFAULT 999,        -- Max hari untuk approval level ini
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (leave_type_id) REFERENCES leave_types(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES master_departments(id),
    FOREIGN KEY (jabatan_id) REFERENCES master_jabatans(id),
    FOREIGN KEY (approver_jabatan_id) REFERENCES master_jabatans(id),
    INDEX idx_leave_type (leave_type_id),
    INDEX idx_department (department_id)
);
```

**Contoh Data:**
```
| ID | leave_type | department | jabatan | level | approver_role   | jabatan_id | min_day | max_day |
|----|-----------|------------|---------|-------|-----------------|------------|---------|---------|
| 1  | 1 (SICK)  | NULL      | NULL    | 1     | direct_manager  | NULL       | 0       | 3       |
| 2  | 1 (SICK)  | NULL      | NULL    | 2     | manager         | 5          | 4       | 5       |
| 3  | 5 (ANNUAL)| NULL      | NULL    | 1     | direct_manager  | NULL       | 0       | 5       |
| 4  | 5 (ANNUAL)| NULL      | NULL    | 2     | manager         | 5          | 6       | 12      |
| 5  | 5 (ANNUAL)| NULL      | NULL    | 3     | hr              | 15         | 0       | 999     |
```

---

### 4. `leave_requests` (Data Utama - Pengajuan Izin/Cuti)

```sql
CREATE TABLE leave_requests (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    karyawan_id BIGINT NOT NULL,
    leave_type_id BIGINT NOT NULL,
    
    -- Detail Pengajuan
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days INT NOT NULL,                     -- Hitung otomatis (exclude weekend/holiday)
    reason TEXT NOT NULL,
    
    -- File & Attachment
    attachment_path VARCHAR(255),                -- Path untuk dokumen pendukung
    attachment_type VARCHAR(50),                 -- 'medical_cert', 'letter', dll
    
    -- Status Tracking
    status ENUM('draft', 'submitted', 'approved', 'rejected', 'cancelled', 'revoked') DEFAULT 'draft',
    
    -- Additional Info
    contact_person VARCHAR(100),                 -- Kontak darurat saat izin
    contact_phone VARCHAR(20),
    notes TEXT,
    
    -- Audit Trail
    submitted_at TIMESTAMP NULL,                 -- Waktu submission
    final_approved_at TIMESTAMP NULL,            -- Waktu final approval
    created_by BIGINT,                           -- User yang buat (biasanya karyawan itu sendiri)
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    FOREIGN KEY (leave_type_id) REFERENCES leave_types(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    
    INDEX idx_karyawan_status (karyawan_id, status),
    INDEX idx_date_range (start_date, end_date),
    INDEX idx_status (status),
    INDEX idx_submitted_at (submitted_at)
);
```

---

### 5. `leave_approvals` (Hirarki Approval Track - Setiap Step Approval)

```sql
CREATE TABLE leave_approvals (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    leave_request_id BIGINT NOT NULL,
    
    -- Approver Info
    approver_user_id BIGINT NOT NULL,            -- User yang approve
    approval_level INT NOT NULL,                 -- 1, 2, 3, 4
    approval_role VARCHAR(50) NOT NULL,         -- 'direct_manager', 'manager', 'hr'
    
    -- Status Approval
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    
    -- Waktu & Keputusan
    decision_date TIMESTAMP NULL,
    notes TEXT,                                  -- Keterangan approve/reject
    
    -- Tracking
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Kapan dikirim ke approver
    reminder_sent_count INT DEFAULT 0,
    last_reminder_sent TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (leave_request_id) REFERENCES leave_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (approver_user_id) REFERENCES users(id),
    
    UNIQUE KEY unique_request_level (leave_request_id, approval_level),
    INDEX idx_approver_status (approver_user_id, status),
    INDEX idx_status (status),
    INDEX idx_decision_date (decision_date)
);
```

---

### 6. `leave_balances` (Summary Saldo Izin/Cuti Per Karyawan)

```sql
CREATE TABLE leave_balances (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    karyawan_id BIGINT NOT NULL,
    leave_type_id BIGINT NOT NULL,
    year INT NOT NULL,
    
    opening_balance INT DEFAULT 0,               -- Saldo awal tahun
    granted_amount INT DEFAULT 0,                -- Pemberian tahun ini
    used_amount INT DEFAULT 0,                   -- Terpakai tahun ini
    closing_balance INT GENERATED ALWAYS AS (opening_balance + granted_amount - used_amount) STORED,
    
    carry_forward_to_next_year INT DEFAULT 0,   -- Sisa yang bisa dibawa
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    FOREIGN KEY (leave_type_id) REFERENCES leave_types(id),
    
    UNIQUE KEY unique_balance (karyawan_id, leave_type_id, year),
    INDEX idx_karyawan_year (karyawan_id, year)
);
```

---

### 7. `leave_notifications` (Notification Log)

```sql
CREATE TABLE leave_notifications (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    leave_request_id BIGINT NOT NULL,
    recipient_user_id BIGINT NOT NULL,
    
    notification_type ENUM('submitted', 'approved_level', 'approved_final', 'rejected', 'cancelled', 'reminder') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    
    channel ENUM('email', 'in_app', 'sms') DEFAULT 'in_app',
    
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (leave_request_id) REFERENCES leave_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (recipient_user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_recipient_read (recipient_user_id, is_read),
    INDEX idx_notification_type (notification_type),
    INDEX idx_created_at (created_at)
);
```

---

### 8. `leave_history` (Audit Trail - Riwayat Semua Perubahan)

```sql
CREATE TABLE leave_history (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    leave_request_id BIGINT NOT NULL,
    action VARCHAR(50) NOT NULL,                 -- 'created', 'submitted', 'approved', 'rejected', 'updated', 'cancelled'
    
    action_by BIGINT NOT NULL,                   -- User yang melakukan action
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    old_values JSON,                             -- Perubahan data (untuk audit)
    new_values JSON,
    
    notes TEXT,
    
    FOREIGN KEY (leave_request_id) REFERENCES leave_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (action_by) REFERENCES users(id),
    
    INDEX idx_action_by (action_by),
    INDEX idx_action_date (action_date)
);
```

---

## Alur Approval & Notification

### 📊 Flowchart Approval Process

```
Karyawan Submit Pengajuan Izin/Cuti
         ↓
    [Draft Status]
         ↓
Karyawan Klik "Submit"
         ↓
    [Submitted Status]
    ↓ → Create Leave_Approvals untuk Level 1
    ↓ → Send Notification ke Direct Manager
    ↓ → Create Leave_History
         ↓
    [Waiting for Level 1 Approval]
         ↓
Manager Buka Dashboard Approval
    ↓ → Melihat Leave_Approvals dgn status='pending'
    ↓ → Approve atau Reject
         ↓
├─ REJECT: Status → 'rejected' | Notify Karyawan
│
└─ APPROVE: 
    ├─ Level 1 Approval: status='approved'
    ├─ Check: Apakah ada Level 2?
    │   ├─ ADA → Create Level 2 Approval | Send Notification ke Level 2 Manager
    │   └─ TIDAK → Status Final Approve | Update Leave_Quotas & Leave_Balances
    └─ Notify Karyawan
```

### 🔔 Notification Flow

**1. Saat Submit Pengajuan:**
- Notifikasi ke Direct Manager: "Ada pengajuan izin/cuti dari [Nama Karyawan] untuk [Tgl Start-End]"
- In-app notification + Email

**2. Saat Approve Level 1:**
- Notifikasi ke Level 2 Approver (jika ada): "Ada pengajuan izin untuk approve dari [Direct Manager]"
- Notifikasi ke Karyawan: "Pengajuan Anda sedang diproses oleh [Manager Name]"

**3. Saat Final Approve:**
- Notifikasi ke Karyawan: "✓ Pengajuan Anda disetujui"
- Update quota otomatis

**4. Saat Reject:**
- Notifikasi ke Karyawan: "✗ Pengajuan ditolak dengan alasan: [Notes]"

**5. Reminder (Pending > 3 hari):**
- Notifikasi ke Approver yang belum approve
- Track di leave_approvals.reminder_sent_count & last_reminder_sent

---

## Entity Relationship Diagram

```
┌─────────────────┐
│  leave_types    │
│  (Jenis Izin)   │
└────────┬────────┘
         │
         ├──→ leave_quotas (Quota per Karyawan)
         ├──→ leave_requests (Pengajuan)
         ├──→ approval_hierarchies (Aturan Approval)
         └──→ leave_balances (Saldo)

┌─────────────────┐
│   karyawan      │
└────────┬────────┘
         │
         ├──→ leave_requests (Karyawan yang pengajuan)
         ├──→ leave_quotas (Quota tiap tipe)
         └──→ leave_balances (Saldo tiap tipe)

┌─────────────────────────┐
│   leave_requests        │
│ (Pengajuan Utama)       │
└────────┬────────────────┘
         │
         ├──→ leave_approvals (Multi-level approvals)
         │    ├──→ users (Approver di setiap level)
         │    └──→ approval_hierarchies (Config)
         │
         ├──→ leave_notifications (Notif logs)
         └──→ leave_history (Audit trail)

┌──────────────────────┐
│ approval_hierarchies │
│ (Config Approval)    │
└────────┬─────────────┘
         │
         ├──→ leave_types
         ├──→ master_departments
         ├──→ master_jabatans
         └──→ karyawan_jabatan (untuk matching approver)
```

---

## Implementasi Detail

### A. Model Relationships

#### Model: `LeaveType.php`

```php
<?php
namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = 'leave_types';
    protected $fillable = [
        'code', 'name', 'category', 'quota_per_year', 
        'requires_attachment', 'color_badge', 'description', 'is_active'
    ];

    // Relationships
    public function leaveRequests() {
        return $this->hasMany(LeaveRequest::class);
    }

    public function quotas() {
        return $this->hasMany(LeaveQuota::class);
    }

    public function hierarchies() {
        return $this->hasMany(ApprovalHierarchy::class);
    }
    
    // Scopes
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    public function scopeLeaves($query) {
        return $query->where('category', 'leave');
    }

    public function scopePermissions($query) {
        return $query->where('category', 'permission');
    }
}
```

#### Model: `LeaveRequest.php`

```php
<?php
namespace App\Models\Leave;

use App\Models\Employee\Karyawan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model
{
    use SoftDeletes;

    protected $table = 'leave_requests';
    protected $fillable = [
        'karyawan_id', 'leave_type_id', 'start_date', 'end_date',
        'total_days', 'reason', 'attachment_path', 'attachment_type',
        'status', 'contact_person', 'contact_phone', 'notes',
        'submitted_at', 'final_approved_at', 'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'submitted_at' => 'datetime',
        'final_approved_at' => 'datetime',
    ];

    // Relationships
    public function karyawan() {
        return $this->belongsTo(Karyawan::class);
    }

    public function leaveType() {
        return $this->belongsTo(LeaveType::class);
    }

    public function approvals() {
        return $this->hasMany(LeaveApproval::class);
    }

    public function notifications() {
        return $this->hasMany(LeaveNotification::class);
    }

    public function history() {
        return $this->hasMany(LeaveHistory::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeByKaryawan($query, $karyawanId) {
        return $query->where('karyawan_id', $karyawanId);
    }

    public function scopeByStatus($query, $status) {
        return $query->where('status', $status);
    }

    public function scopeBetweenDates($query, $startDate, $endDate) {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    public function scopePending($query) {
        return $query->where('status', 'submitted')->whereHas('approvals', function ($q) {
            $q->where('status', 'pending');
        });
    }

    // Methods
    public function canBeApprovedBy(User $user) {
        return $this->approvals()
            ->where('approver_user_id', $user->id)
            ->where('status', 'pending')
            ->exists();
    }

    public function getNextApprovalLevel() {
        return $this->approvals()
            ->where('status', 'pending')
            ->orderBy('approval_level')
            ->first();
    }

    public function isFullyApproved() {
        return $this->approvals()->where('status', '!=', 'approved')->doesntExist() &&
               $this->approvals()->count() > 0;
    }

    public function approve(User $approver, $notes = null) {
        // Update approval status
        $currentApproval = $this->approvals()
            ->where('approver_user_id', $approver->id)
            ->where('status', 'pending')
            ->first();

        if (!$currentApproval) {
            throw new \Exception('No pending approval for this user');
        }

        $currentApproval->update([
            'status' => 'approved',
            'decision_date' => now(),
            'notes' => $notes
        ]);

        // Check if fully approved
        if ($this->isFullyApproved()) {
            $this->update([
                'status' => 'approved',
                'final_approved_at' => now()
            ]);
            
            // Update quota
            $this->updateQuota();
        } else {
            // Create next level approval
            $this->createNextLevelApproval();
        }

        return $currentApproval;
    }

    public function reject(User $approver, $notes = null) {
        $this->approvals()
            ->where('approver_user_id', $approver->id)
            ->where('status', 'pending')
            ->first()
            ->update([
                'status' => 'rejected',
                'decision_date' => now(),
                'notes' => $notes
            ]);

        $this->update(['status' => 'rejected']);
    }

    private function updateQuota() {
        LeaveQuota::updateOrCreate(
            [
                'karyawan_id' => $this->karyawan_id,
                'leave_type_id' => $this->leave_type_id,
                'year' => $this->start_date->year
            ],
            [
                'used_quota' => \DB::raw("used_quota + {$this->total_days}")
            ]
        );
    }

    private function createNextLevelApproval() {
        $currentLevel = $this->approvals()
            ->whereNotNull('status')
            ->max('approval_level');

        $hierarchy = ApprovalHierarchy::where('leave_type_id', $this->leave_type_id)
            ->where('level', $currentLevel + 1)
            ->first();

        if ($hierarchy) {
            // Find approver based on jabatan/role
            $approver = $this->findApprover($hierarchy);
            
            if ($approver) {
                LeaveApproval::create([
                    'leave_request_id' => $this->id,
                    'approver_user_id' => $approver->id,
                    'approval_level' => $currentLevel + 1,
                    'approval_role' => $hierarchy->approver_role,
                    'status' => 'pending'
                ]);

                // Send notification
                LeaveNotification::create([
                    'leave_request_id' => $this->id,
                    'recipient_user_id' => $approver->id,
                    'notification_type' => 'approved_level',
                    'title' => "Pengajuan Izin #{$this->id}",
                    'message' => "Ada pengajuan izin dari {$this->karyawan->full_name}"
                ]);
            }
        }
    }

    private function findApprover(ApprovalHierarchy $hierarchy) {
        // Jika direct_manager → ambil manager dari karyawan
        if ($hierarchy->approver_role === 'direct_manager') {
            return $this->karyawan->activeJabatan?->jabatan?->managerUser;
        }

        // Jika specific jabatan → cari user dengan jabatan tersebut di unit yang sama
        if ($hierarchy->approver_jabatan_id) {
            return User::whereHas('karyawan.activeJabatan', function ($q) use ($hierarchy) {
                $q->where('jabatan_id', $hierarchy->approver_jabatan_id)
                  ->where('unit_id', $this->karyawan->activeJabatan->unit_id);
            })->first();
        }

        // Fallback: HR department head
        return null;
    }
}
```

#### Model: `LeaveApproval.php`

```php
<?php
namespace App\Models\Leave;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    protected $table = 'leave_approvals';
    protected $fillable = [
        'leave_request_id', 'approver_user_id', 'approval_level',
        'approval_role', 'status', 'decision_date', 'notes',
        'sent_at', 'reminder_sent_count', 'last_reminder_sent'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'decision_date' => 'datetime',
        'last_reminder_sent' => 'datetime'
    ];

    // Relationships
    public function leaveRequest() {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approver_user_id');
    }

    // Scopes
    public function scopePending($query) {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query) {
        return $query->where('status', 'approved');
    }

    // Methods
    public function shouldSendReminder() {
        if ($this->status !== 'pending') return false;
        
        $daysPending = now()->diffInDays($this->sent_at);
        return $daysPending >= 3 && 
               ($this->reminder_sent_count === 0 || 
                now()->diffInDays($this->last_reminder_sent) >= 2);
    }

    public function sendReminder() {
        $this->increment('reminder_sent_count');
        $this->update(['last_reminder_sent' => now()]);
        
        // Send email reminder
        // Dispatch job untuk send email
    }
}
```

---

### B. Key Business Logic

#### Submission Flow

```php
// In LeaveRequestController atau Livewire Component
public function submit(LeaveRequest $request) {
    $karyawan = auth()->user()->karyawan;
    
    // Validate quota
    $quota = LeaveQuota::where([
        'karyawan_id' => $karyawan->id,
        'leave_type_id' => $request->leave_type_id,
        'year' => $request->start_date->year
    ])->first();
    
    if ($quota && $quota->remaining_quota < $request->total_days) {
        throw new \Exception('Quota tidak cukup');
    }

    // Update status
    $request->update([
        'status' => 'submitted',
        'submitted_at' => now()
    ]);

    // Create approvals based on hierarchy
    $this->createApprovals($request);
    
    // Send notification
    $this->sendNotifications($request);
    
    // Log history
    LeaveHistory::create([
        'leave_request_id' => $request->id,
        'action' => 'submitted',
        'action_by' => auth()->id(),
        'new_values' => ['status' => 'submitted']
    ]);
}

private function createApprovals(LeaveRequest $request) {
    $hierarchies = ApprovalHierarchy::where('leave_type_id', $request->leave_type_id)
        ->where(function ($q) {
            $q->whereNull('department_id')
              ->orWhere('department_id', $request->karyawan->activeJabatan->department_id);
        })
        ->where(function ($q) use ($request) {
            $q->whereNull('jabatan_id')
              ->orWhere('jabatan_id', $request->karyawan->activeJabatan->jabatan_id);
        })
        ->where('min_day_requirement', '<=', $request->total_days)
        ->where('max_day_requirement', '>=', $request->total_days)
        ->orderBy('level')
        ->get();

    foreach ($hierarchies as $hierarchy) {
        $approver = $this->findApproverFor($request, $hierarchy);
        
        if ($approver) {
            LeaveApproval::create([
                'leave_request_id' => $request->id,
                'approver_user_id' => $approver->id,
                'approval_level' => $hierarchy->level,
                'approval_role' => $hierarchy->approver_role,
                'status' => 'pending'
            ]);
        }
    }
}

private function sendNotifications(LeaveRequest $request) {
    $firstApproval = $request->approvals()->first();
    
    if ($firstApproval) {
        LeaveNotification::create([
            'leave_request_id' => $request->id,
            'recipient_user_id' => $firstApproval->approver_user_id,
            'notification_type' => 'submitted',
            'title' => "Pengajuan Izin dari {$request->karyawan->full_name}",
            'message' => "Periode: {$request->start_date->format('d/m/Y')} - {$request->end_date->format('d/m/Y')} ({$request->total_days} hari)",
            'channel' => 'email'
        ]);
    }
}
```

---

### C. Quota Calculation

```php
// Automatically calculate used days (excluding weekends & holidays)
public function calculateTotalDays() {
    $holidays = Holiday::whereBetween('date', [$this->start_date, $this->end_date])->get();
    
    $days = 0;
    $current = $this->start_date->copy();
    
    while ($current <= $this->end_date) {
        if (!$current->isWeekend() && !$holidays->contains($current)) {
            $days++;
        }
        $current->addDay();
    }
    
    return $days;
}
```

---

## Queries & Scopes

### Most Common Queries

```php
// 1. Get pending approval untuk user
LeaveRequest::whereHas('approvals', function ($q) {
    $q->where('approver_user_id', auth()->id())
      ->where('status', 'pending');
})->with(['karyawan', 'leaveType', 'approvals.approver'])->get();

// 2. Get historical leave requests untuk karyawan
LeaveRequest::byKaryawan($karyawanId)
    ->byStatus('approved')
    ->betweenDates(now()->startOfYear(), now()->endOfYear())
    ->with(['leaveType', 'approvals'])
    ->get();

// 3. Get remaining quota untuk karyawan
LeaveQuota::where([
    'karyawan_id' => $karyawanId,
    'leave_type_id' => $leaveTypeId,
    'year' => now()->year
])->first()?->remaining_quota;

// 4. Check overlapping leave requests
LeaveRequest::where('karyawan_id', $karyawanId)
    ->where('status', 'approved')
    ->where(function ($q) use ($startDate, $endDate) {
        $q->whereBetween('start_date', [$startDate, $endDate])
          ->orWhereBetween('end_date', [$startDate, $endDate])
          ->orWhere([
              ['start_date', '<=', $startDate],
              ['end_date', '>=', $endDate]
          ]);
    })
    ->exists();

// 5. Get notification untuk user
LeaveNotification::where('recipient_user_id', auth()->id())
    ->where('is_read', false)
    ->orderByDesc('created_at')
    ->paginate(10);
```

---

## 📝 Summary Implementasi

### Phase 1: Database & Models
- ✅ Buat 8 migration files
- ✅ Buat 6 Model classes dengan relationships
- ✅ Setup seeders untuk leave_types & approval_hierarchies

### Phase 2: Business Logic
- ✅ LeaveRequest submission flow
- ✅ Multi-level approval logic
- ✅ Quota calculation & validation

### Phase 3: UI/Livewire Components
- 📝 Karyawan: Form pengajuan + history
- 📝 Manager: Dashboard approval + action
- 📝 Admin: Setup leave_types, quotas, hierarchies
- 📝 Notification panel

### Phase 4: Additional Features
- 📝 Approval reminders (scheduled job)
- 📝 Leave calendar view
- 📝 Reporting & analytics
- 📝 Email notifications
- 📝 API endpoints

---

## ⚠️ Catatan Penting

1. **Quota Calculation**: Hitung hari kerja (exclude weekend & hari libur)
2. **Approval Flow**: Dinamis berdasarkan approval_hierarchies config
3. **Audit Trail**: Setiap perubahan dicatat di leave_history
4. **Notification**: Multi-channel (email + in-app)
5. **Permission Control**: Gunakan Spatie Permission untuk akses module
6. **Soft Delete**: Leave requests tidak pernah dihapus (hanya soft-delete untuk audit)

