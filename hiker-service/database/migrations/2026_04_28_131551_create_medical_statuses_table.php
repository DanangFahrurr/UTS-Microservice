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
        Schema::create('medical_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hiker_id')->constrained('hikers')->onDelete('cascade');
            $table->string('blood_type', 3);
            $table->boolean('has_heart_condition')->default(false);
            $table->boolean('has_asma')->default(false);
            $table->boolean('has_hypertension')->default(false);
            $table->text('other_conditions')->nullable();
            $table->string('surat_kesehatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_statuses');
    }
};
