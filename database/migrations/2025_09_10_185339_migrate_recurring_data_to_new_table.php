<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Migrate existing recurring payment data from user_plans to recurring_payments table
        DB::statement("
            INSERT INTO recurring_payments (
                user_plan_id,
                stripe_subscription_id,
                total_payments,
                total_payments_expected,
                next_payment_date,
                last_payment_date,
                payment_status,
                canceled_at,
                cancelation_reason,
                created_at,
                updated_at
            )
            SELECT 
                id as user_plan_id,
                stripe_subscription_id,
                total_payments,
                total_payments_expected,
                next_payment_date,
                last_payment_date,
                payment_status,
                canceled_at,
                cancelation_reason,
                created_at,
                updated_at
            FROM user_plans 
            WHERE is_recurring = 1
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove migrated data from recurring_payments table
        DB::table('recurring_payments')->truncate();
    }
};
