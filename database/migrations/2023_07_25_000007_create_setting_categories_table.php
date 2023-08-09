<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('module_id')->unsigned()->index()->comment('模块id');
            $table->string('key')->nullable()->comment('键值');
            $table->string('name')->comment('名称');
            $table->integer('sequence')->unsigned()->default(0)->comment('排序');
            $table->uuid('parent_id')->nullable()->index()->comment('父分类id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_categories');
    }
}
