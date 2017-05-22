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

        // 后台用户管理
        if ( !Schema::hasTable('adm_user') ) {
            Schema::create('adm_user', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                $table->increments('id')->comment('自增ID');
                $table->string('account', 30)->default('')->comment('账号');
                $table->string('pass', 80)->default('')->comment('密码');
                $table->string('nickname', 30)->default('')->comment('昵称');
                $table->tinyInteger('user_type')->default(1)->comment('用户类型');
                $table->tinyInteger('is_valid')->default(1)->comment('是否有效');
                $table->timestamps();
            });
        }

        // 订单表
        if ( !Schema::hasTable('order') ) {
            Schema::create('order', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_general_ci';
                $table->increments('order_id')->comment('自增订单ID');
                $table->string('order_no', 50)->default('')->comment('订单编号');
                $table->integer('user_id')->comment('关联用户ID');
                $table->tinyInteger('order_status')->default(1)->comment('订单状态 1未扣减积分 2已扣减积分 3无效订单');
                $table->string('card_id', 30)->default('')->comment('优惠券卡号');
                $table->float('integral')->default(0)->comment('积分数');
                $table->tinyInteger('is_valid')->default(1)->comment('是否有效，0:无效，1:有效');
                $table->timestamps();
                $table->unique('order_no');
                $table->index('order_status');
                $table->index('user_id');
                $table->index(['user_id', 'order_status']);
                $table->index('card_id');
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
        Schema::dropIfExists('order');
        Schema::dropIfExists('users');
    }
}
