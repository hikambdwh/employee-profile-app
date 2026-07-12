<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Kolom wajib yang diisi HR
    |--------------------------------------------------------------------------
    */

  'hr_required_fields' => [
    'employee_id',
    'access_id',
    'company',
    'tax_movement_recalculate',
    'residency_status',
    'bpjs_jamsostek_contribution',
    'bpjs_pension_eligibility',
    'tax_fasilitas_code',
    'tax_object_code_monthly_code',
    'store_code',
    'base_cost_center',
    'bpjs_kesehatan_number',
    'brand',
    'bpjs_ketenagakerjaan_number',
    'bc_code',
    'salary_matrix',
    'bank_code',
    'kep_sso_hash',
    'bpa1_sifat_pemotongan_code',
    'shift_group_code',
    'time_policy_code',
  ],

  /*
    |--------------------------------------------------------------------------
    | Kolom wajib yang diisi employee
    |--------------------------------------------------------------------------
    |
    | Masukkan hanya field yang benar-benar wajib.
    | Field opsional tidak perlu dimasukkan.
    |
    */

  'employee_required_fields' => [
    'emergency_full_name',
    'current_address',
    'mother_full_name',
    'education_level',
    'primary_contact_number',
    'tax_number',
    'emergency_contact_no',
    'current_provinsi',
    'primary_email',
    'display_name',
    'current_kotamadya_kabupaten',
    'major',
    'institution_name',
    'religion',
    'birth_place',
    'date_of_birth',
    'marital_status',
    'gender',
    'ktp_address',
    'blood_group',
    'ktp_number',
    'nationality',
  ],

  /*
    |--------------------------------------------------------------------------
    | Nilai yang dianggap kosong
    |--------------------------------------------------------------------------
    */

  'empty_values' => [
    '-',
    '--',
    'N/A',
  ],

];
