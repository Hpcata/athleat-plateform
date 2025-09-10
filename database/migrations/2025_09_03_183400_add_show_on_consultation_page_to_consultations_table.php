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
        if (!Schema::hasTable('consultations')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->boolean('show_on_consultation_page')->default(0)->after('time');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('consultations')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->dropColumn('show_on_consultation_page');
            });
        }
    }
};