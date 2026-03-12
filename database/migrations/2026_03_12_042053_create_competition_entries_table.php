// 2024_01_01_000025_create_competition_entries_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('competition_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('instructor_id')->constrained('instructors');
            $table->string('category');
            $table->string('division');
            $table->string('result')->nullable();
            $table->enum('medal', ['gold', 'silver', 'bronze', 'none'])->default('none');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('competition_entries'); }
};
