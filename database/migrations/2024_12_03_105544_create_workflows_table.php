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
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('审批流名称');
            $table->string('common_form_identifier')->nullable()->comment('审批流绑定通用模板id');
            $table->text('form_dsl')->nullable()->comment('表单设计dsl');
            $table->text('workflow_dsl')->nullable()->comment('流程设计 dsl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflows');
    }
};
