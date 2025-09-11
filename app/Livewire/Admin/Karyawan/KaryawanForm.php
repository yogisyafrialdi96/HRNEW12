<?php

namespace App\Livewire\Admin\Karyawan;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Employee\Karyawan;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KaryawanForm extends Component
{
    use WithFileUploads;

    public ?Karyawan $employee = null;
    public $isEdit = false;

    // Personal Information
    #[Validate('required|string|max:20|unique:employees,employee_number')]
    public $employee_number = '';

    #[Validate('required|string|size:16|unique:employees,nik')]
    public $nik = '';

    #[Validate('required|string|max:255')]
    public $first_name = '';

    #[Validate('nullable|string|max:255')]
    public $last_name = '';

    #[Validate('required|email|unique:employees,email')]
    public $email = '';

    #[Validate('required|string|max:20')]
    public $phone = '';

    #[Validate('required|date|before:today')]
    public $birth_date = '';

    #[Validate('required|string|max:255')]
    public $birth_place = '';

    #[Validate('required|in:male,female')]
    public $gender = '';

    #[Validate('required|in:single,married,divorced,widowed')]
    public $marital_status = '';

    #[Validate('nullable|string|max:50')]
    public $religion = '';

    // Address Information
    #[Validate('required|string')]
    public $address = '';

    #[Validate('required|string|max:10')]
    public $postal_code = '';

    #[Validate('required|string|max:100')]
    public $city = '';

    #[Validate('required|string|max:100')]
    public $province = '';

    // Emergency Contact
    #[Validate('nullable|string|max:255')]
    public $emergency_contact_name = '';

    #[Validate('nullable|string|max:20')]
    public $emergency_contact_phone = '';

    #[Validate('nullable|string|max:50')]
    public $emergency_contact_relation = '';

    // Employment Information
    #[Validate('required|date')]
    public $hired_date = '';

    #[Validate('nullable|date|after:hired_date')]
    public $terminated_date = '';

   

    // File Upload
    #[Validate('nullable|image|max:2048')] // 2MB max
    public $photo;
    
    public $existing_photo = '';

    protected $messages = [
        'employee_number.required' => 'Nomor karyawan wajib diisi.',
        'employee_number.unique' => 'Nomor karyawan sudah digunakan.',
        'nik.required' => 'NIK wajib diisi.',
        'nik.size' => 'NIK harus 16 digit.',
        'nik.unique' => 'NIK sudah terdaftar.',
        'email.unique' => 'Email sudah digunakan.',
        'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
        'terminated_date.after' => 'Tanggal keluar harus setelah tanggal masuk.',
        'photo.image' => 'File harus berupa gambar.',
        'photo.max' => 'Ukuran foto maksimal 2MB.',
    ];

    public function mount(?Karyawan $employee = null)
    {
        if ($employee && $employee->exists) {
            $this->employee = $employee;
            $this->isEdit = true;
            $this->loadEmployeeData();
        } else {
            $this->generateEmployeeNumber();
            $this->hired_date = now()->format('Y-m-d');
        }
    }

    protected function loadEmployeeData()
    {
        $this->employee_number = $this->employee->employee_number;
        $this->nik = $this->employee->nik;
        $this->first_name = $this->employee->first_name;
        $this->last_name = $this->employee->last_name;
        $this->email = $this->employee->email;
        $this->phone = $this->employee->phone;
        $this->birth_date = $this->employee->birth_date->format('Y-m-d');
        $this->birth_place = $this->employee->birth_place;
        $this->gender = $this->employee->gender;
        $this->marital_status = $this->employee->marital_status;
        $this->religion = $this->employee->religion;
        $this->address = $this->employee->address;
        $this->postal_code = $this->employee->postal_code;
        $this->city = $this->employee->city;
        $this->province = $this->employee->province;
        $this->emergency_contact_name = $this->employee->emergency_contact_name;
        $this->emergency_contact_phone = $this->employee->emergency_contact_phone;
        $this->emergency_contact_relation = $this->employee->emergency_contact_relation;
        $this->hired_date = $this->employee->hired_date->format('Y-m-d');
        $this->terminated_date = $this->employee->terminated_date?->format('Y-m-d');
        
        $this->existing_photo = $this->employee->photo;
    }

    protected function generateEmployeeNumber()
    {
        $year = date('Y');
        $month = date('m');
        
        // Format: YYYY-MM-XXXX (contoh: 2024-12-0001)
        $lastEmployee = Karyawan::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('employee_number', 'desc')
            ->first();

        if ($lastEmployee && preg_match('/(\d{4})-(\d{2})-(\d{4})/', $lastEmployee->employee_number, $matches)) {
            $sequence = intval($matches[3]) + 1;
        } else {
            $sequence = 1;
        }

        $this->employee_number = sprintf('%s-%s-%04d', $year, $month, $sequence);
    }

    protected function rules()
    {
        $rules = [
            'employee_number' => ['required', 'string', 'max:20'],
            'nik' => ['required', 'string', 'size:16'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'birth_date' => ['required', 'date', 'before:today'],
            'birth_place' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female'],
            'marital_status' => ['required', 'in:single,married,divorced,widowed'],
            'religion' => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'postal_code' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:50'],
            'hired_date' => ['required', 'date'],
            'terminated_date' => ['nullable', 'date', 'after:hired_date'],
            'status' => ['required'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];

        // Unique validation untuk edit
        if ($this->isEdit && $this->employee) {
            $rules['employee_number'][] = 'unique:employees,employee_number,' . $this->employee->id;
            $rules['nik'][] = 'unique:employees,nik,' . $this->employee->id;
            $rules['email'][] = 'unique:employees,email,' . $this->employee->id;
        } else {
            $rules['employee_number'][] = 'unique:employees,employee_number';
            $rules['nik'][] = 'unique:employees,nik';
            $rules['email'][] = 'unique:employees,email';
        }

        return $rules;
    }

    public function updatedNik()
    {
        // Validate NIK format (basic validation)
        if (strlen($this->nik) === 16 && is_numeric($this->nik)) {
            // Extract birth date from NIK (basic extraction)
            try {
                $day = substr($this->nik, 6, 2);
                $month = substr($this->nik, 8, 2);
                $year = substr($this->nik, 10, 2);
                
                // Adjust year (assume 00-30 is 2000s, 31-99 is 1900s)
                $fullYear = intval($year) <= 30 ? 2000 + intval($year) : 1900 + intval($year);
                
                // If day > 40, it's female (subtract 40)
                if (intval($day) > 40) {
                    $day = intval($day) - 40;
                    $this->gender = 'female';
                } else {
                    $this->gender = 'male';
                }
                
                // Set birth date if valid
                $birthDate = Carbon::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $fullYear, intval($month), $day));
                if ($birthDate && $birthDate->isPast()) {
                    $this->birth_date = $birthDate->format('Y-m-d');
                }
            } catch (\Exception $e) {
                // Ignore errors in NIK parsing
            }
        }
    }

    public function save()
    {
        try {
            $this->validate();

            DB::beginTransaction();

            $data = [
                'employee_number' => $this->employee_number,
                'nik' => $this->nik,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'full_name' => trim($this->first_name . ' ' . $this->last_name),
                'email' => $this->email,
                'phone' => $this->phone,
                'birth_date' => $this->birth_date,
                'birth_place' => $this->birth_place,
                'gender' => $this->gender,
                'marital_status' => $this->marital_status,
                'religion' => $this->religion,
                'address' => $this->address,
                'postal_code' => $this->postal_code,
                'city' => $this->city,
                'province' => $this->province,
                'emergency_contact_name' => $this->emergency_contact_name,
                'emergency_contact_phone' => $this->emergency_contact_phone,
                'emergency_contact_relation' => $this->emergency_contact_relation,
                'hired_date' => $this->hired_date,
                'terminated_date' => $this->terminated_date,
                'status' => $this->status,
            ];

            // Handle photo upload
            if ($this->photo) {
                // Delete old photo if exists
                if ($this->isEdit && $this->existing_photo) {
                    Storage::disk('public')->delete($this->existing_photo);
                }

                // Store new photo
                $photoPath = $this->photo->store('employees/photos', 'public');
                $data['photo'] = $photoPath;
            }

            if ($this->isEdit) {
                $this->employee->update($data);
                $message = 'Data karyawan berhasil diperbarui!';
                $this->dispatch('employee-updated');
            } else {
                $employee = Karyawan::create($data);
                $this->employee = $employee;
                $message = 'Karyawan baru berhasil ditambahkan!';
                $this->dispatch('employee-created');
            }

            DB::commit();

            session()->flash('success', $message);

            // Redirect to employee profile
            return $this->redirect(route('employees.show', $this->employee), navigate: true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            
            // Validation errors will be handled automatically by Livewire
            throw $e;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            // Log the error for debugging
            \Log::error('Error saving employee: ' . $e->getMessage(), [
                'employee_id' => $this->employee?->id,
                'is_edit' => $this->isEdit,
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function removePhoto()
    {
        try {
            if ($this->isEdit && $this->existing_photo) {
                // Delete from storage
                Storage::disk('public')->delete($this->existing_photo);
                
                // Update database
                $this->employee->update(['photo' => null]);
                
                $this->existing_photo = '';
                session()->flash('success', 'Foto berhasil dihapus!');
            }
        } catch (\Exception $e) {
            \Log::error('Error removing photo: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus foto. Silakan coba lagi.');
        }
    }

    public function cancel()
    {
        if ($this->isEdit) {
            return $this->redirect(route('employees.show', $this->employee), navigate: true);
        } else {
            return $this->redirect(route('employees.index'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.admin.karyawan.karyawan-form', [
            'genderOptions' => [
                'male' => 'Laki-laki',
                'female' => 'Perempuan'
            ],
            'maritalStatusOptions' => [
                'single' => 'Belum Menikah',
                'married' => 'Menikah',
                'divorced' => 'Cerai',
                'widowed' => 'Janda/Duda'
            ],
            
        ]);
    }
}