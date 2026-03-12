// 2024_01_01_000013_create_face_profiles_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('face_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->json('face_embedding_vector');
            $table->string('model_version')->default('v1.0');
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('face_profiles'); }
};
