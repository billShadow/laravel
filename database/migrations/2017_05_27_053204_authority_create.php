<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AuthorityCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 角色表
        if ( !Schema::hasTable('role') ) {
            Schema::create('role', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_general_ci';
                $table->increments('role_id')->comment('自增ID');
                $table->tinyInteger('user_id')->comment('关联用户ID');
                $table->string('role_name', 32)->nullable()->comment('角色名称');
                $table->string('role_desc', 100)->nullable()->comment('角色功能说明');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 功能表
        if ( !Schema::hasTable('action') ) {
            Schema::create('action', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_general_ci';
                $table->increments('action_id')->comment('自增ID');
                $table->string('action_name', 32)->default('')->comment('功能名称');
                $table->string('action_desc', 100)->nullable()->comment('功能说明');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 角色对应的功能表
        if ( !Schema::hasTable('role_action') ) {
            Schema::create('role_action', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_general_ci';
                $table->increments('id')->comment('自增ID');
                $table->tinyInteger('role_id')->comment('关联角色ID');
                $table->tinyInteger('action_id')->comment('关联功能ID');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 用户对应的角色表
        if ( !Schema::hasTable('user_role') ) {
            Schema::create('user_role', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->charset = 'utf8';
                $table->collation = 'utf8_general_ci';
                $table->increments('id')->comment('自增ID');
                $table->tinyInteger('user_id')->comment('关联用户ID');
                $table->tinyInteger('role_id')->comment('关联角色ID');
                $table->timestamps();
                $table->softDeletes();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
        Schema::dropIfExists('action');
        Schema::dropIfExists('role_action');
        Schema::dropIfExists('user_role');
    }
}
