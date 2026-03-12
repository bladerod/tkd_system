// 2024_01_01_000017_create_payments_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'gcash', 'card', 'bank'])->default('cash');
            $table->string('reference_no')->nullable();
            $table->timestamp('paid_at');
            $table->foreignId('received_by_user_id')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
