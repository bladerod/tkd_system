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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('class_name', 150);
            $table->string('age_group', 50)->nullable();
            $table->string('level', 50)->nullable();
            $table->integer('max_students')->default(0);
            $table->foreignId('primary_instructor_id')->nullable()->constrained('instructors');
            $table->foreignId('assistant_instructor_id')->nullable()->constrained('instructors');
            $table->enum('status', ['active', 'inactive', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};