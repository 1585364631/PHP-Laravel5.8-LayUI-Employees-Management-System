<?php

use Illuminate\Database\Seeder;

class AllTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //填充管理员表
        $salt = md5(uniqid(microtime(), true));
        $password = md5(md5('123456'). $salt);
        DB::table('user')->insert([
            [
                'id' => 1,
                'username' => 'admin',
                'password' => $password,
                'salt' => $salt
            ],
        ]);

        //填充部门
        DB::table('pre_dept')->insert([
            [
                'dept_id' => 1,
                'dept_name' => '市场部'
            ],[
                'dept_id' => 2,
                'dept_name' => '开发部'
            ],[
                'dept_id' => 3,
                'dept_name' => '媒体部'
            ],[
                'dept_id' => 4,
                'dept_name' => '销售部'
            ],[
                'dept_id' => 5,
                'dept_name' => '人事部'
            ],
        ]);

        //填充员工
        DB::table('pre_emp')->insert([
            [
                'emp_id' => 1,
                'emp_dept_id' => '1',
                'emp_name' => '张三',
                'emp_birth' => '1990-02-03 00:00:00',
                'emp_entry' => '2015-08-08 00:00:00'
            ],[
                'emp_id' => 2,
                'emp_dept_id' => '2',
                'emp_name' => '李四',
                'emp_birth' => '1991-05-02 00:00:00',
                'emp_entry' => '2015-07-02 00:00:00'
            ],[
                'emp_id' => 3,
                'emp_dept_id' => '3',
                'emp_name' => '王五',
                'emp_birth' => '1989-07-12 00:00:00',
                'emp_entry' => '2015-09-01 00:00:00'
            ],[
                'emp_id' => 4,
                'emp_dept_id' => '4',
                'emp_name' => '赵六',
                'emp_birth' => '1989-12-03 00:00:00',
                'emp_entry' => '2014-04-12 00:00:00'
            ],
        ]);
    }
}
