// 2024_01_01_000029_create_chat_threads_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chat_threads', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['private', 'class', 'parent-staff'])->default('private');
            $table->foreignId('class_id')->nullable()->constrained('classes');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('chat_threads'); }
};
