<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsOpeningHourMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('opening_hour_markets', function (Blueprint $table) {
            if (!Schema::hasColumn('opening_hour_markets', 'close_hour_second')) {
                $table->time('close_hour_second')->after('close_hour')->nullable();
            }

            if (!Schema::hasColumn('opening_hour_markets', 'open_hour_second')) {
                $table->time('open_hour_second')->after('close_hour')->nullable();
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
        Schema::table('opening_hour_markets', function (Blueprint $table) {
            if (Schema::hasColumn('opening_hour_markets', 'close_hour_second')) {
                $table->dropColumn('close_hour_second');
            }

            if (Schema::hasColumn('opening_hour_markets', 'open_hour_second')) {
                $table->dropColumn('open_hour_second');
            }
        });
    }
}
