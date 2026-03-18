<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RecreateVwuserView extends Migration
{
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS vwuser");
        
        DB::statement("
            CREATE VIEW vwuser AS
            SELECT 
                u.user_id,
                b.branch_name,
                u.role,
                CONCAT(u.fname, ' ', u.lname) AS full_name,
                u.email,
                u.mobile_no,
                u.password,
                u.photo_url,
                u.last_login_at,
                u.created_at,
                u.updated_at
            FROM users u
            LEFT JOIN branches b ON u.branch_id = b.branch_id
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vwuser");
    }
}