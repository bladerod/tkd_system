// 2024_01_01_000004_create_parents_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('emergency_contact')->nullable();
            $table->text('relationship_note')->nullable();
            $table->text('address')->nullable();
            $table->boolean('id_verified_flag')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('parents'); }
};
