<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSmallTicketSettingTable extends Migration
{
    public $tableName = "small_ticket_setting";

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

            $table->string('code', 100)->nullable(false)->comment("打印设置唯一CODE")->unique("code_unique");
            $table->string('na', 100)->nullable(false)->comment("打印设置名称");
            $table->string('va')->nullable(false)->default("")->comment("打印设置内容");
            $table->unsignedTinyInteger("sort")->nullable(false)->default(100)->comment("排序: 升序");
            $table->timestamps();
        });
        $prefix = DB::getConfig('prefix');
        $qu = "ALTER TABLE " . $prefix . $this->tableName . " comment '小票打印设置表'";
        DB::statement($qu);
    }
}
