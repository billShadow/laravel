<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertUsersFiled extends Migration
{

    // 常见修改字段
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Users表添加累计积分字段
        if ( !Schema::hasColumn('users', 'total_points') ) {
            Schema::table('users', function (Blueprint $table) {
                $table->bigInteger('total_points')->default(0)->comment('累计积分')->after('work');
            });
        }

        // Users表添加可用积分
        if ( !Schema::hasColumn('users', 'avail_points') ) {
            Schema::table('users', function (Blueprint $table) {
                $table->bigInteger('avail_points')->default(0)->comment('可用积分')->after('work');
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
        if ( Schema::hasColumn('users', 'avail_points') ) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('avail_points');
            });
        }
        if ( Schema::hasColumn('users', 'total_points') ) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('total_points');
            });
        }
    }
}
