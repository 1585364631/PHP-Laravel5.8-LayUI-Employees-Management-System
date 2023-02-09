<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table = 'pre_dept';
    protected $primaryKey = "dept_id";
    public $incrementing = true;
    public $timestamps = false;
    protected $guarded = [];
}
