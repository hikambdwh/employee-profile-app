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
        Schema::create('business_unit_and_departments', function (Blueprint $table) {
            $table->id();
            $table->string('business_unit_code', 100);
            $table->string('department_code', 100);
            $table->foreign('business_unit_code')->references('business_unit_code')->on('business_units')->onDelete('cascade');
            $table->foreign('department_code')->references('department_code')->on('departments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_unit_and_departments');
    }
};
