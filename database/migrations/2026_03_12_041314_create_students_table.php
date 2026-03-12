// 2024_01_01_000005_create_students_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('student_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->date('birthdate');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('photo_url')->nullable();
            $table->string('current_belt')->default('white');
            $table->date('join_date');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('medical_notes')->nullable();
            $table->text('allergies')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_mobile')->nullable();
            $table->foreignId('primary_parent_id')->constrained('parents');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('students'); }
};
