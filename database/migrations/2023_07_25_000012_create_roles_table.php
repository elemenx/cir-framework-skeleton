<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->integer('org_id')->unsigned()->index()->default(0)->comment('组织ID');
            $table->string('name');
            $table->text('acls')->nullable()->comment('权限');
            $table->text('raw_acls')->nullable()->comment('原始权限');
            $table->text('config')->nullable()->comment('高级配置');
            $table->boolean('is_default')->index()->unsigned()->default(0)->comment('注册时默认角色');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
