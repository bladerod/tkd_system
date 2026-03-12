// 2024_01_01_000007_create_instructors_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('rank_belt')->nullable();
            $table->string('certification_level')->nullable();
            $table->string('specialization')->nullable();
            $table->date('hire_date');
            $table->text('bio')->nullable();
            $table->boolean('active_flag')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('instructors'); }
};
