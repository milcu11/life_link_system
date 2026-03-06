<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('users')->onDelete('cascade');
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->integer('quantity'); // in units
            $table->enum('urgency_level', ['low', 'medium', 'high', 'critical']);
            $table->string('location');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->enum('status', ['pending', 'matched', 'fulfilled', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->string('patient_name')->nullable();
            $table->string('contact_person');
            $table->string('contact_phone', 20);
            $table->timestamp('needed_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};