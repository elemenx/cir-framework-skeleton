<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->char('mobile', 11)->unique()->comment('手机号');
            $table->string('password')->comment('密码[bcrypt加密后值]');
            $table->string('name')->nullable()->comment('名称');
            $table->boolean('locked')->default(0)->comment('账号是否锁定');
            $table->boolean('enabled')->default(1)->comment('账号是否开启');
            $table->boolean('is_admin')->default(0)->comment('是否为管理员');
            $table->string('note')->nullable()->comment('备注');
            $table->timestamps();
            $table->timestamp('issued_at')->nullable()->comment('鉴权颁发时间');
            $table->timestamp('reset_at')->nullable()->comment('密码重置时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
