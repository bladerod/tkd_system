// 2024_01_01_000032_create_devices_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('device_name');
            $table->enum('device_type', ['kiosk', 'tablet', 'desktop'])->default('kiosk');
            $table->string('api_key')->unique();
            $table->timestamp('last_seen')->nullable();
            $table->enum('status', ['online', 'offline', 'maintenance'])->default('offline');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('devices'); }
};
