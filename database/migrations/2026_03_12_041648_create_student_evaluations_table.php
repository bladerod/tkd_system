// 2024_01_01_000019_create_student_evaluations_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('instructor_id')->constrained('instructors');
            $table->foreignId('class_id')->constrained('classes');
            $table->date('evaluation_date');
            $table->integer('technique_score')->nullable();
            $table->integer('discipline_score')->nullable();
            $table->integer('fitness_score')->nullable();
            $table->integer('sparring_score')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('belt_ready_flag')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_evaluations'); }
};
