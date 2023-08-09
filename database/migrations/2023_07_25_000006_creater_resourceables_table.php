<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaterResourceablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resourceables', function (Blueprint $table) {
            $table->id();
            $table->integer('resource_id')->unsigned()->index()->comment('资源id');
            $table->morphs('resourceable');
            $table->string('keys')->nullable()->comment('关联项ID');
            $table->string('identifier_alias')->nullable()->comment('关联项ID');
            $table->string('expression')->nullable()->comment('表达式json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resourceables');
    }
}
