<?php

namespace App\Http\Controllers;

use App\Models\employee_details;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

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

        return view('pages.form', [
            'title' => 'Employee Form',
            'user' => $user,

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

        /*
         * Menolak nilai placeholder seperti:
         * -, --, N/A, n/a, dan variasi huruf lainnya.
         */
        $notPlaceholder = function (
            string $attribute,
            mixed $value,
            \Closure $fail
        ) use ($emptyValues) {
            $normalizedValue = strtoupper(
                trim((string) $value)
            );

            if (in_array($normalizedValue, $emptyValues, true)) {
                $fail("Field {$attribute} harus diisi dengan data yang valid.");
            }
        };

        /*
         * Aturan tipe data setiap field.
         * Status required akan ditambahkan otomatis berdasarkan config.
         */
        $rules = [
            /*
             * Employee ID adalah data HR.
             * Employee hanya menggunakannya untuk mencari record.
             */
            'employee_id' => [
                'required',
                'string',
                'max:20',
                'exists:employee_details,employee_id',
            ],

            'emergency_full_name' => [
                'string',
                'max:100',
            ],

            'current_address' => [
                'string',
                'max:2000',
            ],

            'mother_full_name' => [
                'string',
                'max:100',
            ],

            'education_level' => [
                Rule::in([
                    'SMA',
                    'SMK',
                    'D1',
                    'D2',
                    'D3',
                    'D4',
                    'S1',
                    'S2',
                    'S3',
                ]),
            ],

            'primary_contact_number' => [
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
            ],

            'tax_number' => [
                'string',
                'max:30',
            ],

            'emergency_contact_no' => [
                'string',
                'max:30',
                'regex:/^[0-9+\-\s()]+$/',
            ],

            'current_provinsi' => [
                'string',
                'max:100',
            ],

            'primary_email' => [
                'email',
                'max:191',
            ],

            'display_name' => [
                'string',
                'max:100',
            ],

            'current_kotamadya_kabupaten' => [
                'string',
                'max:100',
            ],

            'major' => [
                'string',
                'max:100',
            ],

            'institution_name' => [
                'string',
                'max:150',
            ],

            'religion' => [
                Rule::in([
                    'Islam',
                    'Hinduism',
                    'Christianity',
                    'Buddhism',
                    'Catholicism',
                    'Sikhism',
                    'Other'
                ]),
            ],

            'birth_place' => [
                'string',
                'max:100',
            ],

            'date_of_birth' => [
                'date',
                'before_or_equal:today',
            ],

            'marital_status' => [
                Rule::in([
                    'Single',
                    'Married',
                    'Divorced',
                    'Widowed',
                ]),
            ],

            'gender' => [
                Rule::in([
                    'Male',
                    'Female',
                ]),
            ],

            'ktp_address' => [
                'string',
                'max:2000',
            ],

            'blood_group' => [
                Rule::in([
                    'A+',
                    'B+',
                    'AB+',
                    'O+',
                    'A-',
                    'AB-',
                    'B-',
                    'O-',
                ]),
            ],

            'ktp_number' => [
                'string',
                'max:25',
                'regex:/^[0-9]+$/',
            ],

            'nationality' => [
                'string',
                'max:50',
            ],
        ];

        /*
         * Pastikan setiap field yang berada di config
         * memiliki aturan validation.
         */
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

        /*
         * Tambahkan required dan validasi placeholder
         * secara otomatis.
         */
        foreach ($employeeRequiredFields as $field) {
            array_unshift(
                $rules[$field],
                'required'
            );

            $rules[$field][] = $notPlaceholder;
        }

        $validated = $request->validate(
            $rules,
            [
                'employee_id.exists' => 'Employee ID tidak ditemukan.',

                'date_of_birth.before_or_equal' =>
                'Tanggal lahir tidak boleh melebihi hari ini.',

                'primary_contact_number.regex' =>
                'Format nomor kontak utama tidak valid.',

                'emergency_contact_no.regex' =>
                'Format nomor kontak darurat tidak valid.',

                'ktp_number.regex' =>
                'Nomor KTP hanya boleh berisi angka.',
            ]
        );

        $employee = employee_details::where(
            'employee_id',
            $validated['employee_id']
        )->firstOrFail();

        /*
         * Hanya mengambil field yang menjadi tanggung jawab employee.
         * Kolom HR tidak akan ikut diperbarui walaupun dimanipulasi
         * melalui request.
         */
        $employeeData = Arr::only(
            $validated,
            $employeeRequiredFields
        );

        $employee->fill($employeeData);

        /*
         * Penanda bahwa employee pernah menyelesaikan submit.
         */
        if (is_null($employee->employee_completed_at)) {
            $employee->employee_completed_at = now();
        }

        $employee->save();
        $employee->refresh();

        /*
         * Pemeriksaan tambahan setelah data tersimpan.
         */
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

        /*
         * Arahkan ke form supaya statistik langsung dihitung ulang.
         */
        return redirect('form')
            ->with(
                'success',
                'Data employee berhasil dilengkapi.'
            );
    }
}
