<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('标题');
            $table->string('sub_title')->comment('子标题');
            $table->string('path')->comment('模块路径');
            $table->string('acl')->comment('权限点');
            $table->string('icon')->nullable()->comment('图标');
            $table->integer('sort')->unsigned()->default(0)->comment('排序');
            $table->string('query')->nullable()->comment('query参数');
            $table->string('params')->nullable()->comment('params参数');
            $table->boolean('hidden')->unsigned()->default(0)->comment('是否隐藏');
            $table->nestedSet();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
