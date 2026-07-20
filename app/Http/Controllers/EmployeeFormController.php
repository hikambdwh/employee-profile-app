<?php

namespace App\Http\Controllers;

use App\Models\employee_details;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class EmployeeFormController extends Controller
{
    /**
     * Menampilkan form dan melakukan sync berdasarkan employee_id.
     */
    public function show(Request $request)
    {
        $user = null;

        

        if ($request->filled('employee_id')) {
            $request->validate([
                'employee_id' => [
                    'required',
                    'string',
                    'max:20',
                ],
            ]);

            $user = employee_details::where(
                'employee_id',
                $request->employee_id
            )->first();

            if (!$user) {
                return redirect()
                    ->route('employee.form')
                    ->with('error', 'Employee ID tidak ditemukan.');
            }
        }

        $existingDocuments = [
            'ijazah_filename' => filled($user?->ijazah_filename)
                && Storage::disk('public')->exists($user->ijazah_filename),

            'ktp_filename' => filled($user?->ktp_filename)
                && Storage::disk('public')->exists($user->ktp_filename),

            'kk_filename' => filled($user?->kk_filename)
                && Storage::disk('public')->exists($user->kk_filename),

            'npwp_filename' => filled($user?->npwp_filename)
                && Storage::disk('public')->exists($user->npwp_filename),
        ];


        return view('pages.form', [
            'title' => 'Employee Form',
            'user' => $user,
            'existingDocuments' => $existingDocuments,

            /*
             * Dikirim ke Blade agar atribut required pada form
             * mengikuti config/employee.php.
             */
            'employeeRequiredFields' => config(
                'employee.employee_required_fields',
                []
            ),
        ]);
    }

    /**
     * Menyimpan data yang menjadi tanggung jawab employee.
     */
    public function submit(Request $request)
    {
        $employeeRequiredFields = config(
            'employee.employee_required_fields',
            []
        );

        $emptyValues = array_map(
            fn($value) => strtoupper(trim((string) $value)),
            config('employee.empty_values', [])
        );

        $notPlaceholder = function (
            string $attribute,
            mixed $value,
            \Closure $fail
        ) use ($emptyValues) {
            $normalizedValue = strtoupper(trim((string) $value));

            if (in_array($normalizedValue, $emptyValues, true)) {
                $fail("Field {$attribute} harus diisi dengan data yang valid.");
            }
        };

        /*
     * Validasi employee_id dulu agar kita bisa ambil data employee.
     */
        $request->validate([
            'employee_id' => [
                'required',
                'string',
                'max:20',
                'exists:employee_details,employee_id',
            ],
        ], [
            'employee_id.exists' => 'Employee ID tidak ditemukan.',
        ]);

        $employee = employee_details::where(
            'employee_id',
            $request->employee_id
        )->firstOrFail();

        /*
     * ktp_postal_code tidak ada input di form.
     * Nilainya disamakan otomatis dengan current_postal_code.
     */
        $request->merge([
            'ktp_postal_code' => $request->input('current_postal_code'),
        ]);

        $fileFields = [
            'ktp_filename',
            'kk_filename',
            'ijazah_filename',
            'npwp_filename',
        ];

        $rules = [
            'employee_id' => [
                'required',
                'string',
                'max:20',
                'exists:employee_details,employee_id',
            ],

            'emergency_full_name' => ['string', 'max:255'],
            'current_address' => ['string', 'max:2000'],
            'mother_full_name' => ['string', 'max:255'],

            'education_level' => [
                Rule::in(['SMA', 'SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3']),
            ],

            'education_from' => [
                'integer',
                'min:1800',
                'max:2100',
            ],

            'education_end' => [
                'integer',
                'min:1800',
                'max:2100',
                'gte:education_from',
            ],

            'primary_contact_number' => [
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
            ],

            'tax_number' => ['string', 'max:30'],

            'emergency_contact_no' => [
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
            ],

            'current_provinsi' => ['string', 'max:255'],
            'current_kotamadya_kabupaten' => ['string', 'max:255'],
            'current_kecamatan' => ['string', 'max:255'],
            'current_kelurahan' => ['string', 'max:255'],
            'current_postal_code' => ['string', 'max:50'],

            'ktp_address' => ['string', 'max:2000'],
            'ktp_provinsi' => ['string', 'max:255'],
            'ktp_kotamadya_kabupaten' => ['string', 'max:255'],
            'ktp_kecamatan' => ['string', 'max:255'],
            'ktp_kelurahan' => ['string', 'max:255'],

            /*
         * Tidak ada input di Blade.
         * Diisi otomatis dari current_postal_code.
         */
            'ktp_postal_code' => ['string', 'max:50'],

            'primary_email' => ['email', 'max:191'],
            'display_name' => ['string', 'max:255'],
            'major' => ['string', 'max:255'],
            'institution_name' => ['string', 'max:150'],

            'religion' => [
                Rule::in([
                    'Islam',
                    'Hinduism',
                    'Christianity',
                    'Buddhism',
                    'Catholicism',
                    'Sikhism',
                    'Other',
                ]),
            ],

            'birth_place' => ['string', 'max:255'],

            'date_of_birth' => [
                'date',
                'before_or_equal:today',
            ],

            'marital_status' => [
                Rule::in(['Single', 'Married', 'Divorced', 'Widowed']),
            ],

            'gender' => [
                Rule::in(['Male', 'Female']),
            ],

            'blood_group' => [
                Rule::in(['A+', 'B+', 'AB+', 'O+', 'A-', 'AB-', 'B-', 'O-']),
            ],

            'ktp_number' => [
                'string',
                'max:25',
                'regex:/^[0-9]+$/',
            ],

            'nationality' => ['string', 'max:50'],

            'ktp_filename' => [
                Rule::requiredIf(fn() => empty($employee->ktp_filename)),
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'kk_filename' => [
                Rule::requiredIf(fn() => empty($employee->kk_filename)),
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'ijazah_filename' => [
                Rule::requiredIf(fn() => empty($employee->ijazah_filename)),
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'npwp_filename' => [
                Rule::requiredIf(fn() => empty($employee->npwp_filename)),
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ];

        $unknownFields = array_diff(
            $employeeRequiredFields,
            array_keys($rules)
        );

        if (!empty($unknownFields)) {
            throw new \RuntimeException(
                'Validation belum tersedia untuk field: ' .
                    implode(', ', $unknownFields)
            );
        }

        foreach ($employeeRequiredFields as $field) {
            if (in_array($field, $fileFields, true)) {
                continue;
            }

            array_unshift($rules[$field], 'required');
            $rules[$field][] = $notPlaceholder;
        }

        $validated = $request->validate(
            $rules,
            [
                'date_of_birth.before_or_equal' =>
                'Tanggal lahir tidak boleh melebihi hari ini.',

                'primary_contact_number.regex' =>
                'Format nomor kontak utama tidak valid.',

                'emergency_contact_no.regex' =>
                'Format nomor kontak darurat tidak valid.',

                'ktp_number.regex' =>
                'Nomor KTP hanya boleh berisi angka.',

                'education_end.gte' =>
                'Tahun selesai pendidikan tidak boleh lebih kecil dari tahun mulai pendidikan.',

                'ktp_filename.required' =>
                'File KTP wajib diupload.',

                'kk_filename.required' =>
                'File KK wajib diupload.',

                'ijazah_filename.required' =>
                'File ijazah wajib diupload.',

                'npwp_filename.required' =>
                'File NPWP wajib diupload.',

                '*.mimes' =>
                'File harus berupa PDF, JPG, JPEG, atau PNG.',

                '*.max' =>
                'Ukuran file maksimal 5MB.',
            ]
        );

        /*
     * Ambil data text/input biasa saja.
     * File tidak boleh langsung masuk fill(), karena isinya UploadedFile object.
     */
        $employeeData = Arr::only(
            $validated,
            array_diff($employeeRequiredFields, $fileFields)
        );

        $employee->fill($employeeData);

        /*
     * Simpan file jika employee upload file baru.
     */
        $fileFieldMap = [
            'ktp_filename' => [
                'prefix' => 'ktp',
                'folder' => 'ktp',
            ],
            'kk_filename' => [
                'prefix' => 'kk',
                'folder' => 'kk',
            ],
            'ijazah_filename' => [
                'prefix' => 'ijazah',
                'folder' => 'ijazah',
            ],
            'npwp_filename' => [
                'prefix' => 'npwp',
                'folder' => 'npwp',
            ],
        ];

        $safeEmployeeId = preg_replace(
            '/[^A-Za-z0-9_-]/',
            '_',
            $employee->employee_id
        );

        foreach ($fileFieldMap as $field => $config) {
            if (!$request->hasFile($field)) {
                continue;
            }

            $file = $request->file($field);

            $extension = strtolower($file->getClientOriginalExtension());

            $fileName = $config['prefix'] . '_' . $safeEmployeeId . '.' . $extension;

            $directory = 'employee-documents/' . $config['folder'];

            /*
     * Hapus file lama jika ada dan berbeda.
     */
            if (!empty($employee->{$field}) && Storage::disk('public')->exists($employee->{$field})) {
                Storage::disk('public')->delete($employee->{$field});
            }

            /*
     * Simpan file dengan nama custom.
     * Contoh hasil:
     * employee-documents/ktp/ktp_6949.pdf
     */
            $path = $file->storeAs(
                $directory,
                $fileName,
                'public'
            );
 
            /*
     * Yang disimpan ke database adalah path file.
     */
            $employee->{$field} = $path;
        }

        if (is_null($employee->employee_completed_at)) {
            $employee->employee_completed_at = now();
        }

        $employee->save();
        $employee->refresh();

        $recognizedAsComplete = employee_details::query()
            ->whereKey($employee->id)
            ->employeeDataComplete()
            ->exists();

        if (!$recognizedAsComplete) {
            return redirect()
                ->route('employee.form', [
                    'employee_id' => $employee->employee_id,
                ])
                ->with(
                    'error',
                    'Data tersimpan, tetapi masih ada field employee yang belum dianggap lengkap.'
                );
        }

        return redirect('form')
            ->with(
                'success',
                'Data employee berhasil dilengkapi.'
            );
    }
}
