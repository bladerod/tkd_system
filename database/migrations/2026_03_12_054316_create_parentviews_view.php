<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
    CREATE OR REPLACE VIEW parentviews AS
    SELECT 
    u.name AS fname,
    p.mobile AS mobile,
    p.status AS status
    FROM parents p
    JOIN users u ON p.user_id = u.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS parentviews");
    }
};