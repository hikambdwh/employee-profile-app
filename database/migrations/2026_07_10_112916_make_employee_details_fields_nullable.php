<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            /*
             * Data HR/OD.
             * Bisa kosong pada file Excel.
             */
            $table->string('time_policy_code', 50)
                ->nullable()
                ->change();

            $table->string('shift_group_code', 50)
                ->nullable()
                ->change();

            $table->string('residency_status', 50)
                ->nullable()
                ->change();

            $table->string('bpjs_jamsostek_contribution', 255)
                ->nullable()
                ->change();

            $table->string('tax_fasilitas_code', 100)
                ->nullable()
                ->change();

            $table->text('tax_object_code_monthly_code')
                ->nullable()
                ->change();

            $table->string('store_code', 30)
                ->nullable()
                ->change();

            /*
             * Data yang nantinya diisi employee.
             * Belum tersedia ketika employee pertama kali di-import.
             */
            $table->text('current_address')
                ->nullable()
                ->change();

            $table->string('primary_contact_number', 30)
                ->nullable()
                ->change();

            $table->string('primary_email', 191)
                ->nullable()
                ->change();

            $table->string('display_name', 100)
                ->nullable()
                ->change();

            $table->string('religion', 50)
                ->nullable()
                ->change();

            $table->string('birth_place', 100)
                ->nullable()
                ->change();

            $table->date('date_of_birth')
                ->nullable()
                ->change();

            $table->string('marital_status', 50)
                ->nullable()
                ->change();

            $table->string('gender', 20)
                ->nullable()
                ->change();

            $table->string('ktp_number', 25)
                ->nullable()
                ->change();

            $table->string('nationality', 50)
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        /*
         * Tidak dikembalikan menjadi NOT NULL karena database
         * mungkin sudah memiliki data NULL setelah proses import.
         */
    }
};
