<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Employee extends Model
{
    //
    protected $table = 'pre_emp';
    protected $primaryKey = "emp_id";
    public $incrementing = true;
    public $timestamps = false;
    protected $guarded = [];

    //模型一对一关联
    function Department(){
        return $this->hasOne('App\Department',"dept_id","emp_dept_id");
    }

    static function getFields(){
        // return Schema::getColumnListing('pre_emp');
        $co = [
            ["name"=>"emp_id","value"=>"员工ID"],
            ["name"=>"dept_name","value"=>"部门名称"],
            ["name"=>"emp_name","value"=>"员工名称"],
            ["name"=>"emp_birth","value"=>"出生日期"],
            ["name"=>"emp_entry","value"=>"入职时间"],
        ];
        return $co;
    }
}
