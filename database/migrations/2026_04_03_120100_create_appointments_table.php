<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('blood_drive_id')->constrained('blood_drives')->cascadeOnDelete();
            $table->dateTime('slot_time');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'attended'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['donor_id', 'blood_drive_id'], 'donor_drive_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};