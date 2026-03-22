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
        u.id,
        u.name AS full_name,
        u.email,
        u.created_at,
        u.updated_at
    FROM users u
");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vwuser");
    }
}