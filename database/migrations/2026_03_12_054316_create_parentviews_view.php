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
            CREATE VIEW parentviews AS
            SELECT 
                CONCAT(u.fname, ' ', u.lname) AS fname,
                p.emergency_contact AS mobile,
                p.id_verified_flag AS status
            FROM parents p
            JOIN users u ON p.user_id = u.id
            WHERE u.role = 'parent'
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