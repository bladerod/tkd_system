// 2024_01_01_000008_create_classes_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('class_name');
            $table->string('age_group')->nullable();
            $table->string('level')->nullable();
            $table->integer('max_students')->default(20);
            $table->foreignId('primary_instructor_id')->constrained('instructors');
            $table->foreignId('assistant_instructor_id')->nullable()->constrained('instructors');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('classes'); }
};
