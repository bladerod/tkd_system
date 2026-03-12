// 2024_01_01_000028_create_notifications_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type')->nullable();
            $table->boolean('read_flag')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notifications'); }
};
