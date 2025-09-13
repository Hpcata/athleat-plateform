<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn([
                'is_recurring',
                'stripe_subscription_id',
                'total_payments',
                'total_payments_expected',
                'next_payment_date',
                'last_payment_date',
                'payment_status',
                'canceled_at',
                'cancelation_reason'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('status')->comment('Whether this plan has recurring monthly payments');
            $table->string('stripe_subscription_id')->nullable()->after('is_recurring')->comment('Stripe subscription ID for recurring payments');
            $table->integer('total_payments')->default(0)->after('stripe_subscription_id')->comment('Total number of payments completed');
            $table->integer('total_payments_expected')->default(8)->after('total_payments')->comment('Total number of payments expected (8 months)');
            $table->timestamp('next_payment_date')->nullable()->after('total_payments_expected')->comment('Next payment due date');
            $table->timestamp('last_payment_date')->nullable()->after('next_payment_date')->comment('Last successful payment date');
            $table->enum('payment_status', ['active', 'past_due', 'canceled', 'incomplete', 'incomplete_expired', 'trialing', 'unpaid'])->default('active')->after('last_payment_date')->comment('Current payment status from Stripe');
            $table->timestamp('canceled_at')->nullable()->after('payment_status')->comment('When the subscription was canceled');
            $table->text('cancelation_reason')->nullable()->after('canceled_at')->comment('Reason for cancellation');
        });
    }
};
