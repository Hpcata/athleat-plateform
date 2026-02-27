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
        Schema::create('user_plans', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('plan_id');

        $table->unsignedBigInteger('modified_by')->nullable();

        $table->string('status', 50)->nullable();

        $table->boolean('is_mail_sent')->default(0);
        $table->timestamp('mail_sent_at')->nullable();

        $table->boolean('nutrition_info_flag')->default(1);

        $table->timestamps();

        // Indexes
        $table->index('user_id');
        $table->index('plan_id');
        $table->index('status');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_plans');
    }
};
