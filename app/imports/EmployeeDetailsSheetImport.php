<?php

namespace App\Imports;

use App\Models\employee_details;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeDetailsSheetImport implements
    ToCollection,
    WithHeadingRow,
    WithChunkReading
{
    /**
     * Bila true, nilai kosong dari Excel tidak akan menghapus
     * data lama yang sudah tersimpan di database.
     */
    private const PRESERVE_EXISTING_VALUE_WHEN_EXCEL_EMPTY = true;

    /**
     * Mapping nama header Excel ke kolom database.
     */
    private const COLUMN_MAPPING = [
        'employee_id' => 'employee_id',
        'access_id' => 'access_id',
        'time_policy_code' => 'time_policy_code',
        'shift_group_code' => 'shift_group_code',
        'company' => 'company',
        'tax_movement_recalculate' => 'tax_movement_recalculate',
        'residency_status' => 'residency_status',
        'bpjs_jamsostek_contribution' => 'bpjs_jamsostek_contribution',
        'bpjs_pension_eligibility' => 'bpjs_pension_eligibility',
        'tax_fasilitas_code' => 'tax_fasilitas_code',
        'tax_object_code_monthly_code' => 'tax_object_code_monthly_code',
        'store_code' => 'store_code',
        'base_cost_center' => 'base_cost_center',
        'bpjs_kesehatan_number' => 'bpjs_kesehatan_number',
        'brand' => 'brand',
        'bpjs_ketenagakerjaan_number' => 'bpjs_ketenagakerjaan_number',
        'bc_code' => 'bc_code',
        'salary_matrix' => 'salary_matrix',
        'bank_code' => 'bank_code',
        'kep_sso_hash' => 'kep_sso_hash',
        'bpa1_sifat_pemotongan_code' => 'bpa1_sifat_pemotongan_code',
        'emergency_full_name' => 'emergency_full_name',
        'current_address' => 'current_address',
        'mother_full_name' => 'mother_full_name',
        'education_level' => 'education_level',
        'primary_contact_number' => 'primary_contact_number',
        'primary_email' => 'primary_email',
        'display_name' => 'display_name',
        'tax_number'   => 'tax_number',
        'emergency_contact_no' => 'emergency_contact_no',
        'current_provinsi' => 'current_provinsi',
        'current_kotamadya_kabupaten' => 'current_kotamadya_kabupaten',
        'major' => 'major',
        'institution_name' => 'institution_name',
        'religion' => 'religion',
        'birth_place' => 'birth_place',
        'date_of_birth' => 'date_of_birth',
        'marital_status' => 'marital_status',
        'gender' => 'gender',
        'ktp_address' => 'ktp_address',
        'blood_group' => 'blood_group',
        'ktp_number' => 'ktp_number',
        'nationality' => 'nationality',
        'business_unitorg_element_1' => 'business_unit_org_element_1',
        'departmentorg_element_2' => 'department_org_element_2',
        'current_postal_code' => 'current_postal_code',
        'current_kecamatan' => 'current_kecamatan',
        'current_kelurahan' => 'current_kelurahan',
        'date_of_join_yyyy_mm_dd' => 'date_of_join',
        'education_from' => 'education_from',
        'education_end' => 'education_end',
        'ktp_kecamatan' => 'ktp_kecamatan',
        'ktp_kelurahan' => 'ktp_kelurahan',
        'ktp_kotamadya_kabupaten' => 'ktp_kotamadya_kabupaten',
        'ktp_postal_code' => 'ktp_postal_code',
        'ktp_provinsi' => 'ktp_provinsi',
    ];

    private int $inserted = 0;

    private int $updated = 0;

    private int $skipped = 0;

    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows): void {
            foreach ($rows as $index => $row) {
                $employeeId = $this->normalizeEmployeeId(
                    $row->get('employee_id')
                );

                if ($employeeId === null) {
                    $this->skipped++;

                    Log::warning('Baris import dilewati karena Employee ID kosong.', [
                        'excel_row' => $index + 2,
                    ]);

                    continue;
                }

                $employee = employee_details::query()
                    ->where('employee_id', $employeeId)
                    ->first();

                $data = $this->buildImportData($row);

                /*
                 * Employee ID selalu menjadi identitas utama.
                 */
                $data['employee_id'] = $employeeId;

                if ($employee) {
                    $updateData = $this->prepareUpdateData($data);

                    /*
                     * Employee ID tidak perlu diubah saat update.
                     */
                    unset($updateData['employee_id']);

                    if ($updateData !== []) {
                        $employee->update($updateData);
                    }

                    $this->updated++;

                    continue;
                }

                employee_details::query()->create($data);

                $this->inserted++;
            }
        });
    }

    /**
     * Dengan WithHeadingRow, Laravel Excel otomatis mengubah:
     *
     * Employee ID                     => employee_id
     * Access id                       => access_id
     * Tax object code monthly Code    => tax_object_code_monthly_code
     * KEP SSO hash                    => kep_sso_hash
     */
    private function buildImportData(Collection $row): array
    {
        $data = [];

        foreach (self::COLUMN_MAPPING as $excelHeader => $databaseColumn) {
            $value = $row->get($excelHeader);

            $data[$databaseColumn] = $this->normalizeValue(
                $value,
                $databaseColumn
            );
        }

        return $data;
    }

    /**
     * Saat update, nilai kosong dari Excel tidak menimpa data lama.
     */
    private function prepareUpdateData(array $data): array
    {
        if (!self::PRESERVE_EXISTING_VALUE_WHEN_EXCEL_EMPTY) {
            return $data;
        }

        return array_filter(
            $data,
            fn($value): bool => !$this->isEmptyValue($value)
        );
    }

    private function normalizeEmployeeId(mixed $value): ?string
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        /*
         * Employee ID pada file dapat terbaca sebagai angka.
         */
        if (is_float($value) || is_int($value)) {
            return number_format((float) $value, 0, '', '');
        }

        return trim((string) $value);
    }

    private function normalizeValue(
        mixed $value,
        string $column
    ): mixed {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        $value = trim((string) $value);

        /*
         * Kolom identifier harus tetap berupa string.
         * Ini penting agar angka nol di depan tidak hilang.
         */
        if (
            in_array(
                $column,
                [
                    'employee_id',
                    'access_id',
                    'bpjs_kesehatan_number',
                    'bpjs_ketenagakerjaan_number',
                    'bank_code',
                    'store_code',
                    'bc_code',
                ],
                true
            )
        ) {
            return $value;
        }

        return $value;
    }

    private function isEmptyValue(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        $normalized = strtoupper(trim((string) $value));

        return in_array(
            $normalized,
            [
                '',
                '-',
                '--',
                'N/A',
            ],
            true
        );
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function getInserted(): int
    {
        return $this->inserted;
    }

    public function getUpdated(): int
    {
        return $this->updated;
    }

    public function getSkipped(): int
    {
        return $this->skipped;
    }
}
