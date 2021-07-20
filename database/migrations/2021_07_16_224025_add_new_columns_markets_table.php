<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('markets', function (Blueprint $table) {
            if (!Schema::hasColumn('markets', 'estimated_time_get_product')) {
                $table->string('estimated_time_get_product', 50)->after('exige_agendamento')->nullable();
            }

            if (!Schema::hasColumn('markets', 'estimated_time_delivery')) {
                $table->string('estimated_time_delivery', 50)->after('exige_agendamento')->nullable();
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
        Schema::table('markets', function (Blueprint $table) {
            if (Schema::hasColumn('markets', 'estimated_time_get_product')) {
                $table->dropColumn('estimated_time_get_product');
            }

            if (Schema::hasColumn('markets', 'estimated_time_get_product')) {
                $table->dropColumn('estimated_time_delivery');
            }
        });
    }
}
