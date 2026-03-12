// 2024_01_01_000014_create_plans_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->text('description')->nullable();
            $table->integer('sessions_per_week')->nullable();
            $table->decimal('monthly_price', 10, 2);
            $table->boolean('unlimited_flag')->default(false);
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->boolean('active_flag')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('plans'); }
};
