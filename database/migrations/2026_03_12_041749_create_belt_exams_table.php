// 2024_01_01_000022_create_belt_exams_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('belt_exams', function (Blueprint $table) {
            $table->id();
            $table->date('exam_date');
            $table->string('belt_level');
            $table->foreignId('class_id')->constrained('classes');
            $table->foreignId('chief_instructor_id')->constrained('instructors');
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('belt_exams'); }
};
