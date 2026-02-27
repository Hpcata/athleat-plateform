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
        Schema::create('user_item_meals', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id')->nullable();
        $table->unsignedBigInteger('item_id');
        $table->unsignedBigInteger('meal_id');

        $table->boolean('is_swiped')->default(0);

        $table->string('qty', 50)->nullable();
        $table->string('unit', 50)->nullable();

        $table->decimal('carbs', 5, 2)->nullable();
        $table->decimal('protein', 5, 2)->nullable();
        $table->decimal('fat', 5, 2)->nullable();

        $table->string('energy', 10)->nullable();

        $table->json('selected_qty_unit')->nullable();

        $table->timestamps();

        // Indexes
        $table->index('user_id');
        $table->index(['user_id', 'meal_id']);
    }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_item_meals');
    }
};
