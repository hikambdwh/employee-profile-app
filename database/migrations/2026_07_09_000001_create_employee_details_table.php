<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 20)->unique();
            $table->string('access_id', 20)->nullable()->unique();
            $table->string('time_policy_code', 50);
            $table->string('shift_group_code', 50);
            $table->string('emergency_full_name', 100)->nullable();
            $table->text('current_address');
            $table->string('mother_full_name', 100)->nullable();
            $table->string('education_level', 30)->nullable();
            $table->string('primary_contact_number', 30);
            $table->string('tax_number', 30)->nullable()->index();
            $table->string('emergency_contact_no', 30)->nullable();
            $table->string('current_provinsi', 255)->nullable();
            $table->string('primary_email', 191)->index();
            $table->string('display_name', 100);
            $table->string('current_kotamadya_kabupaten', 255)->nullable();
            $table->string('major', 100)->nullable();
            $table->string('company', 100)->nullable()->index();
            $table->string('institution_name', 150)->nullable();
            $table->string('tax_movement_recalculate', 255)->nullable();
            $table->string('residency_status', 50);
            $table->string('religion', 50);
            $table->string('birth_place', 100);
            $table->date('date_of_birth');
            $table->string('bpjs_jamsostek_contribution', 255);
            $table->string('bpjs_pension_eligibility', 255)->nullable();
            $table->string('tax_fasilitas_code', 100);
            $table->string('marital_status', 50);
            $table->text('tax_object_code_monthly_code');
            $table->string('gender', 20);
            $table->text('ktp_address')->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('store_code', 30)->index();
            $table->string('ktp_number', 25)->index();
            $table->string('base_cost_center', 100)->nullable();
            $table->string('bpjs_kesehatan_number', 30)->nullable()->index();
            $table->string('brand', 100)->nullable()->index();
            $table->string('bpjs_ketenagakerjaan_number', 30)->nullable()->index();
            $table->string('bc_code', 30)->nullable();
            $table->string('salary_matrix', 100)->nullable();
            $table->string('bank_code', 30)->nullable();
            $table->string('nationality', 50);
            $table->string('kep_sso_hash', 50)->nullable();
            $table->string('bpa1_sifat_pemotongan_code', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_details');
    }
};
