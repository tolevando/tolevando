<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnNumberDeliveryAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_addresses', 'number')) {
                $table->string('number')->after('address')->nullable();
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
        Schema::table('delivery_addresses', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_addresses', 'number')) {
                $table->dropColumn('number');
            }
        });
    }
}
