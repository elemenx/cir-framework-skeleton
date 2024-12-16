<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateTypeToModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `modules` MODIFY `type` ENUM('action','permission','module','listPage','tabPage','configPage','customPage','batchDelete')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `modules` MODIFY `type` ENUM('action','permission','module','listPage','tabPage','configPage','customPage')");
    }
}
