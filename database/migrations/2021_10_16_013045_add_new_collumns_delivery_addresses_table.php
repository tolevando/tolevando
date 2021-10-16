<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewCollumnsDeliveryAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_addresses', 'bairro')) {
                $table->longText('bairro')->after('address')->nullable();
            }
            
            if (!Schema::hasColumn('delivery_addresses', 'complement')) {
                $table->longText('complement')->after('address')->nullable();
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
            if (Schema::hasColumn('delivery_addresses', 'bairro')) {
                $table->dropColumn('bairro');
            }

            if (Schema::hasColumn('delivery_addresses', 'complement')) {
                $table->dropColumn('complement');
            }
        });
    }
}
