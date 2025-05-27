<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('common_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('common_forms', 'identifier')) {
                $table->string('identifier')->nullable()->comment('标识符');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
