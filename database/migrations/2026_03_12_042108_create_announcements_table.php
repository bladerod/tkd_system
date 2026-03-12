// 2024_01_01_000027_create_announcements_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->enum('target_type', ['all', 'class', 'belt', 'branch'])->default('all');
            $table->foreignId('class_id')->nullable()->constrained('classes');
            $table->string('title');
            $table->text('message');
            $table->date('publish_date');
            $table->date('expire_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('announcements'); }
};
