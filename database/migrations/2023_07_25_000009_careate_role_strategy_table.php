<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CareateRoleStrategyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_strategy', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id')->unsigned()->index()->comment('角色id');
            $table->integer('strategy_id')->unsigned()->index()->comment('策略id');
            $table->tinyInteger('sequence')->unsigned()->default(0)->comment('排序,从小到大');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_strategy');
    }
}
