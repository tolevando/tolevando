<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'option_mid_pizza')) {
                $table->integer('option_mid_pizza')->unsigned()->after('category_id')->nullable()->default(0)->comment('0 => Não oferecer, 1 => Valor Médio, 2 => Valor Maior');
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
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'option_mid_pizza')) {
                $table->dropColumn('option_mid_pizza');
            }
        });
    }
}
