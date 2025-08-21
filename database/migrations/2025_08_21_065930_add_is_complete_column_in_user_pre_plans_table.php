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
        Schema::table('user_pre_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('user_pre_plans', 'is_complete')) {
                $table->boolean('is_complete')
                    ->default(0)
                    ->after('sport_image')
                    ->comment('Indicates whether the questionnaire is complete');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_pre_plans', function (Blueprint $table) {
            if (Schema::hasColumn('user_pre_plans', 'is_complete')) {
                $table->dropColumn('is_complete');
            }
        });
    }
};