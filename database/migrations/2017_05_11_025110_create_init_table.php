<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Filesystem\Filesystem;

class CreateInitTable extends Migration
{

    // migrage 常用脚本命令
    // 初始化执行 【php artisan migrate】 执行所有的sql脚本
    // 创建新的migrate文件  【php artisan make:migration 创建的文件名】
    // 回滚操作 【php artisan migrate:rollback】 每次回滚一层

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 用户表
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_general_ci';
                $table->increments('id')->comment('自增ID');
                $table->string('phone', 12)->default('')->comment('手机号');
                $table->string('inckname', 60)->default('')->comment('昵称');
                $table->tinyInteger('gender')->default(1)->comment('性别：1男 2女');
                $table->tinyInteger('is_valid')->default(1)->comment('有效');
                $table->integer('member')->unsigned()->comment('会员编号'); // 不能为负数
                $table->integer('work')->nullable()->comment('工作'); // nullable 可以为空
                $table->timestamps();
                $table->softDeletes();
                $table->unique('member'); // 唯一索引
                $table->index('phone'); // 主键索引
            });
        }

        // 店铺表
        if ( !Schema::hasTable('shop') ) {
            Schema::create('shop', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_general_ci';
                $table->increments('id')->comment('自增ID');
                $table->string('name', 32)->comment('店铺名称');
                $table->string('address', 100)->comment('店铺地址');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 导入sql
         $FS = new Filesystem();
         $sql = $FS->get(base_path('database') . '/' . 'init_db.sql');
         DB::connection()->getPdo()->exec($sql);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop');
        Schema::dropIfExists('users');
    }
}
