<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrgUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('org_user', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->index()->comment('用户ID');
            $table->integer('org_id')->unsigned()->index()->comment('组织ID');
            $table->integer('role_id')->unsigned()->index()->comment('角色ID');
            $table->unique(['user_id', 'org_id']);
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('org_user');
    }
}
