<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->string('verification_document_path')->nullable()->after('medical_conditions');
            $table->boolean('is_verified')->default(false)->after('verification_document_path');
            $table->timestamp('verified_at')->nullable()->after('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn(['verification_document_path', 'is_verified', 'verified_at']);
        });
    }
};
