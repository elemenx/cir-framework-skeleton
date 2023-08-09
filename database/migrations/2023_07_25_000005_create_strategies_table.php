<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrategiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strategies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('策略名');
            $table->boolean('allow')->unsigned()->index()->default(1)->comment('允许');
            $table->string('features')->default('[]')->comment('sa=超管,ro=只读');
            $table->string('acls')->nullable()->comment('acl列表平铺');
            $table->string('raw_acls')->nullable()->comment('原始权限');
            $table->string('rule_config')->nullable()->comment('规则配置');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('strategies');
    }
}
