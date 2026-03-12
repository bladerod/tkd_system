// 2024_01_01_000020_create_skill_checklist_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('skill_checklist', function (Blueprint $table) {
            $table->id();
            $table->string('belt_level');
            $table->string('skill_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('skill_checklist'); }
};
