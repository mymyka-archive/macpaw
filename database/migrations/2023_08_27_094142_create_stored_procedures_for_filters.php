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
        DB::statement('DROP PROCEDURE IF EXISTS contributions_by_collection;');
        DB::statement("
            CREATE PROCEDURE contributions_by_collection()
            BEGIN
                CREATE TEMPORARY TABLE contribution_sum
                SELECT collection_id, SUM(amount) AS total 
                FROM contributors 
                GROUP BY collection_id;

                SELECT id, 
                    title, 
                    description, 
                    target_amount, 
                    link, 
                    total, 
                    (target_amount - total) AS sum_left 
                FROM contribution_sum INNER JOIN collections 
                    ON contribution_sum.collection_id = collections.id;

                DROP TEMPORARY TABLE IF EXISTS contribution_sum;
            END;");
        
        DB::statement('DROP PROCEDURE IF EXISTS active_collections;');
        DB::statement("
            CREATE PROCEDURE active_collections()
            BEGIN
                CREATE TEMPORARY TABLE contribution_sum
                SELECT collection_id, SUM(amount) AS total 
                FROM contributors 
                GROUP BY collection_id;

                SELECT id, 
                    title, 
                    description, 
                    target_amount, 
                    link, 
                    total, 
                    (target_amount - total) AS sum_left 
                FROM contribution_sum INNER JOIN collections 
                    ON contribution_sum.collection_id = collections.id
                WHERE (target_amount - total) > 0;

                DROP TEMPORARY TABLE IF EXISTS contribution_sum;
            END;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS contributions_by_collection;');
        DB::statement('DROP PROCEDURE IF EXISTS active_collections;');
    }
};
