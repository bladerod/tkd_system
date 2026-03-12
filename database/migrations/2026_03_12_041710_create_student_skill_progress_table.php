// 2024_01_01_000021_create_student_skill_progress_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_skill_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('skill_id')->constrained('skill_checklist');
            $table->foreignId('instructor_id')->constrained('instructors');
            $table->enum('status', ['not_started', 'in_progress', 'mastered'])->default('not_started');
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_skill_progress'); }
};
