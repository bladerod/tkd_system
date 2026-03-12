// 2024_01_01_000023_create_belt_exam_results_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('belt_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('belt_exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students');
            $table->integer('score')->nullable();
            $table->enum('result', ['pass', 'fail', 'pending'])->default('pending');
            $table->text('remarks')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('instructors');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('belt_exam_results'); }
};
