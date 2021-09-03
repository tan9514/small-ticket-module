<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSmallTicketTable extends Migration
{
    public $tableName = "small_ticket";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->tableName)) $this->create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }

    /**
     * 执行创建表
     */
    private function create()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';      // 设置存储引擎
            $table->charset = 'utf8';       // 设置字符集
            $table->collation  = 'utf8_general_ci';       // 设置排序规则

            $table->id();
            $table->string('name', 100)->nullable(false)->comment("打印机名称")->unique("name_unique");
            $table->string("brand", 100)->nullable(false)->comment("打印机品牌");
            $table->string("type", 100)->nullable(false)->default("")->comment("打印机类型");
            $table->string("url", 100)->nullable(false)->comment("打印机官网");
            $table->longText("setting")->nullable(false)->comment("打印机基础参数");
           $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        $qu = "ALTER TABLE " . $prefix . $this->tableName . " comment '小票打印机表'";
        DB::statement($qu);
    }
}
