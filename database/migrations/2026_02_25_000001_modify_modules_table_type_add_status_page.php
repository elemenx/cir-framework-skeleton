<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE modules MODIFY COLUMN type ENUM('action','permission','module','listPage','tabPage','configPage','customPage','statusPage') DEFAULT 'action' COMMENT '类型'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE modules MODIFY COLUMN type ENUM('action','permission','module','listPage','tabPage','configPage','customPage') DEFAULT 'action' COMMENT '类型'");
    }
};
