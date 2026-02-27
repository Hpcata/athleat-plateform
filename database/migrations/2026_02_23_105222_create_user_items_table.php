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
          Schema::create('user_items', function (Blueprint $table) {
        $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY

        $table->unsignedBigInteger('user_plan_id')->nullable();
        $table->unsignedBigInteger('user_category_id')->nullable();
        $table->unsignedBigInteger('user_sub_category_id')->nullable();
        $table->unsignedBigInteger('user_meal_id');
        $table->unsignedBigInteger('item_id')->nullable();

        $table->string('qty', 210)->nullable();

        $table->timestamps();

        // Optional indexes (recommended for performance)
        $table->index('user_plan_id');
        $table->index('user_category_id');
        $table->index('user_sub_category_id');
        $table->index('user_meal_id');
        $table->index('item_id');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_items');
    }
};
