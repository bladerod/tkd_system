// 2024_01_01_000026_create_certificates_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->string('certificate_type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('issued_date');
            $table->foreignId('issued_by_user_id')->constrained('users');
            $table->string('qr_code_value')->unique();
            $table->string('verification_url');
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('certificates'); }
};
