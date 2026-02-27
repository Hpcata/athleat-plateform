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
             Schema::create('plan_sub_plans', function (Blueprint $table) {
        $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY
        
        $table->unsignedBigInteger('plan_id');
        $table->unsignedBigInteger('sub_plan_id');

        $table->timestamps();

        // Indexes
        $table->index('plan_id');
        $table->index('sub_plan_id');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            Schema::dropIfExists('plan_sub_plans');

    }
};
