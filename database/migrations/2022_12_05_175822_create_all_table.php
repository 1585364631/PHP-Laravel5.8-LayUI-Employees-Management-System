<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //创建管理员表
        Schema::create('user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment('主键');
            $table->string('username', 32)->comment('用户名')->unique();
            $table->string('password', 32)->comment('密码');
            $table->string('salt', 32)->comment('密码salt');
            $table->timestamps();
        });

        //创建部门表
        Schema::create('pre_dept', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('dept_id')->comment('主键');
            $table->string('dept_name', 12)->comment('部门名称')->unique();
        });

        //创建员工表
        Schema::create('pre_emp', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('emp_id')->comment('主键');
            $table->integer('emp_dept_id')->comment('所属部门ID')->unsigned();
            $table->foreign('emp_dept_id')->references('dept_id')->on('pre_dept')->onUpdate('cascade')->onDelete('cascade');
            $table->string('emp_name', 12)->comment('姓名');
            $table->timestamp('emp_birth')->comment('出生日期')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('emp_entry')->comment('入职时间')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //删除员工表
        Schema::dropIfExists('pre_emp');
        //删除部门表
        Schema::dropIfExists('pre_dept');
        //删除管理员表
        Schema::dropIfExists('user');
    }
}
