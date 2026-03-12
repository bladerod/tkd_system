// 2024_01_01_000031_create_chat_messages_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('chat_threads')->onDelete('cascade');
            $table->foreignId('sender_user_id')->constrained('users');
            $table->text('message');
            $table->string('attachment_url')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('chat_messages'); }
};
