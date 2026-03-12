// 2024_01_01_000006_create_parent_students_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('parent_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('parents')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->enum('relationship', ['mother', 'father', 'guardian'])->default('guardian');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('parent_students'); }
};
