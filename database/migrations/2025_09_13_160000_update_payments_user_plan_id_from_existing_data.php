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
        // Update payments table to set user_plan_id based on existing user_plans data
        DB::statement("
            UPDATE payments
            SET user_plan_id = (
                SELECT up.id 
                FROM user_plans up 
                WHERE up.user_id = payments.user_id 
                AND up.plan_id = payments.plan_id 
                LIMIT 1
            )
            WHERE payments.user_id IS NOT NULL 
            AND payments.plan_id IS NOT NULL
            AND payments.user_plan_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
