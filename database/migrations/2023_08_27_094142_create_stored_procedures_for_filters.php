<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS collections_summary_procedure;');
        DB::statement("
            CREATE PROCEDURE collections_summary_procedure()
            BEGIN
                DROP TABLE IF EXISTS contribution_sum;
                
                CREATE TABLE contribution_sum
                SELECT collection_id, SUM(amount) AS total 
                FROM contributors 
                GROUP BY collection_id;

                DROP TABLE IF EXISTS collections_summary;
                CREATE TABLE collections_summary
                SELECT id, 
                    title, 
                    description, 
                    target_amount, 
                    link, 
                    total, 
                    (target_amount - total) AS sum_left 
                FROM contribution_sum INNER JOIN collections 
                    ON contribution_sum.collection_id = collections.id;
            END;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS collections_summary_procedure;');
    }
};
