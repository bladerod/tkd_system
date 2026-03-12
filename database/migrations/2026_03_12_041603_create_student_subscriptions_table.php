// 2024_01_01_000015_create_student_subscriptions_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('plans');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'expired', 'cancelled', 'suspended'])->default('active');
            $table->boolean('auto_renew_flag')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('student_subscriptions'); }
};
