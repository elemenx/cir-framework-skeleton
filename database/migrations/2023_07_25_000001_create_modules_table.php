<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique()->comment('标识符');
            $table->enum('type', ['action','permission','module','listPage','tabPage','configPage','customPage'])->index()->default('action')->comment('类型');
            $table->string('name')->comment('模块名称');
            $table->string('acl')->nullable()->comment('路由acl');
            $table->text('params')->nullable()->comment('模块内参数');
            $table->text('config')->nullable()->comment('模块配置');
            $table->integer('data_resource_id')->unsigned()->index()->default(0)->comment('获取数据资源id');
            $table->tinyInteger('sequence')->unsigned()->default(0)->comment('排序号');
            // $table->integer('parent_id')->unsigned()->index()->default(0)->comment('父模块id');
            $table->string('parent_identifier')->nullable()->comment('父模块标识符');
            $table->string('icon')->nullable()->comment('图标');
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
        Schema::dropIfExists('modules');
    }
}
