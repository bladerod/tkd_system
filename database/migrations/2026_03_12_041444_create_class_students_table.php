// 2024_01_01_000009_create_class_students_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('class_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'dropped'])->default('active');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('class_students'); }
};
