<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brandings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();
            $table->string('logo_filename')->nullable();
            $table->string('certificate_header_text')->nullable();
            $table->string('certificate_signature_name')->nullable();
            $table->string('signature_position')->nullable();
            $table->string('official_seal_path')->nullable();
            $table->string('official_seal_filename')->nullable();
            $table->string('primary_color')->default('#1C1C1D');
            $table->string('secondary_color')->nullable();
            $table->string('accent_color')->nullable();
            $table->string('favicon_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brandings');
    }
};
