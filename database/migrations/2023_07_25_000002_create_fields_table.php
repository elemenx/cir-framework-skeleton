<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->integer('module_id')->unsigned()->index()->comment('动态模块id');
            $table->string('name')->comment('字段名');
            $table->text('config')->nullable()->comment('配置');
            $table->tinyInteger('list_sequence')->nullable()->comment('列表页字段排序');
            $table->tinyInteger('search_sequence')->nullable()->comment('搜索栏字段排序');
            $table->unique(['module_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
