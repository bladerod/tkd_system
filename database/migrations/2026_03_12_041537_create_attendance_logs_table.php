// 2024_01_01_000012_create_attendance_logs_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('class_session_id')->constrained('class_sessions');
            $table->timestamp('checkin_time');
            $table->timestamp('checkout_time')->nullable();
            $table->enum('method', ['face', 'qr', 'manual'])->default('manual');
            $table->decimal('confidence_score', 5, 2)->nullable();
            $table->foreignId('recorded_by_user_id')->nullable()->constrained('users');
            $table->string('device_id')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('attendance_logs'); }
};
