// 2024_01_01_000018_create_discounts_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['percent', 'fixed'])->default('percent');
            $table->decimal('value', 10, 2);
            $table->date('valid_from');
            $table->date('valid_to');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('discounts'); }
};
