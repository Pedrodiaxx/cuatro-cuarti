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
    Schema::create('doctors', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('speciality_id')
            ->nullable()
            ->constrained('specialities')
            ->nullOnDelete();

        $table->string('medical_license_number')->nullable();
        $table->text('biography')->nullable();

        $table->timestamps();

        // (Opcional pero recomendado) si un usuario solo puede tener 1 doctor:
        $table->unique('user_id');
    });
}
};
