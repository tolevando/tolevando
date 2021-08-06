<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOptionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('option_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('option_groups', 'market_id')) {
                $table->integer('market_id')->unsigned()->after('name')->nullable();
                $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade')->onUpdate('cascade');
            }

            if (!Schema::hasColumn('option_groups', 'is_required')) {
                $table->boolean('is_required')->default(0)->after('name')->nullable();
            }

            if (!Schema::hasColumn('option_groups', 'is_unique')) {
                $table->boolean('is_unique')->default(0)->after('name')->nullable();
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
        Schema::table('option_groups', function (Blueprint $table) {
            if (Schema::hasColumn('option_groups', 'market_id')) {
                $table->dropForeign('option_groups_market_id_foreign');
                $table->dropColumn('market_id');
            }

            if (Schema::hasColumn('option_groups', 'is_required')) {
                $table->dropColumn('is_required');
            }

            if (Schema::hasColumn('option_groups', 'is_unique')) {
                $table->dropColumn('is_unique');
            }
        });
    }
}
