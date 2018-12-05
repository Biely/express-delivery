<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('eid')->comment('快递单号');
            $table->string('store')->comment('快递网点');
            $table->string('etype')->comment('快递类型');
            $table->uuid('user_uuid')->comment('用户id');
            $table->string('uname')->comment('用户姓名');
            $table->string('qq')->comment('联系方式');
            $table->integer('qtype')->comment('问题类型');
            $table->integer('times')->comment('投诉次数')->default(1);
            $table->text('content')->comment('问题描述');
            $table->string('deadline')->comment('超时时间');
            $table->string('file')->comment('媒体文件')->nullable();
            $table->integer('isok')->comment('是否完结')->default(0);
            $table->string('sid')->comment('客服id')->nullable();
            $table->string('sname')->comment('客服名称')->nullable();
            $table->string('score')->comment('评价')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_uuid')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
