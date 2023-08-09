<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('module_id')->unsigned()->default(0)->index()->comment('模块id');
            $table->uuid('parent_id')->nullable()->index()->comment('隶属分类');
            $table->string('name')->comment('表单名称');
            $table->string('key')->nullable()->comment('表单键名');
            $table->string('description')->nullable()->comment('描述');
            $table->string('help_link')->nullable()->comment('外部帮助链接');
            $table->string('type')->nullable()->comment('字段类型; 短文本-text,长文本-textarea, 数字-number, 单选-select, 多选-checkbox, 日期-date, 级联-cascader, 关联-relation, 开关-switch, 单选框-radio, 富文本-editor, 日期范围-range, 时间-time, 图片-image, 文件-file, 省-province, 省-市-province_city, 省-市-区-province_city_district, 自定义-custom');
            $table->string('type_params')->nullable()->comment('数据对应关系 map kv');
            $table->boolean('type_enabled')->unsigned()->default(1)->index()->comment('是否可编辑');
            $table->integer('data_source')->unsigned()->defulat(0)->comment('数据源');
            $table->string('default_value')->nullable()->comment('是否为默认值');
            $table->tinyInteger('span')->unsigned()->default(24)->comment('额外设置');
            $table->integer('sequence')->unsigned()->default(0)->comment('排序');
            $table->unique(['parent_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_items');
    }
}
