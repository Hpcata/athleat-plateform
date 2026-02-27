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
        Schema::create('user_categories', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_plan_id');
        $table->unsignedBigInteger('category_id')->nullable();

        $table->timestamps();

        // Indexes
        $table->index('user_plan_id');
        $table->index('category_id');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_categories');
    }
};
