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
        Schema::create('recurring_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_plan_id')->constrained()->onDelete('cascade')->comment('Reference to user_plans table');
            $table->string('stripe_subscription_id')->nullable()->comment('Stripe subscription ID for recurring payments');
            $table->integer('total_payments')->default(0)->comment('Total number of payments completed');
            $table->integer('total_payments_expected')->default(8)->comment('Total number of payments expected (8 months)');
            $table->timestamp('next_payment_date')->nullable()->comment('Next payment due date');
            $table->timestamp('last_payment_date')->nullable()->comment('Last successful payment date');
            $table->enum('payment_status', ['active', 'past_due', 'canceled', 'incomplete', 'incomplete_expired', 'trialing', 'unpaid'])->default('active')->comment('Current payment status from Stripe');
            $table->timestamp('canceled_at')->nullable()->comment('When the subscription was canceled');
            $table->text('cancelation_reason')->nullable()->comment('Reason for cancellation');
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index('user_plan_id');
            $table->index('stripe_subscription_id');
            $table->index('payment_status');
            $table->index('next_payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurring_payments');
    }
};
