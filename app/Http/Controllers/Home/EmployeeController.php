<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class EmployeeController extends Controller
{
    function index(){
        return view("home.employee");
    }

    //删除
    function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id' => [
                'required',
                'integer',
                'exists:pre_emp,emp_id',
            ],
        ],[
            'emp_id.required'=>'主键不能为空',
            'emp_id.integer'=>'主键只能为数字',
            'emp_id.exists'=>'主键不存在',
        ]);
        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->toArray() as $v) {
                $msg = $v[0];
            }
            return response()->json(['code' => 1, 'msg' => $msg]);
        }
        $emp_id = $request->input('emp_id');
        try{
            $info = Employee::destroy($emp_id);
            return response()->json(['code' => 0, 'msg' => "删除成功"]);
        }catch (QueryException $e){
            return response()->json(['code' => 1, 'msg' => "删除失败，" . $e->getMessage()]);
        }
    }

    //添加
    function add(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_dept_id' => [
                'required',
                'exists:pre_dept,dept_id',
            ],
            'emp_name' => 'required|min:2|max:12'
        ],[
            'emp_dept_id.required' => '部门不能为空',
            'emp_dept_id.exists' => '部门不存在',
            "emp_name.required" => "员工名称不能为空",
            "emp_name.max" => "员工名称最长为12个字符",
            "emp_name.min" => "员工名称最短为12个字符",
        ]);
        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->toArray() as $v) {
                $msg = $v[0];
            }
            return response()->json(['code' => 1, 'msg' => $msg]);
        }
        $data = [
            'emp_id' => $request->input('emp_id',null),
            'emp_dept_id' => $request->input('emp_dept_id'),
            'emp_name' => $request->input('emp_name'),
            'emp_birth' => $request->input('emp_birth',null),
            'emp_entry' => $request->input('emp_entry',null),
        ];
        try{
            $info = Employee::insert($data);
            return response()->json(['code' => 0, 'msg' => "添加成功"]);
        }catch (QueryException $e){
            return response()->json(['code' => 1, 'msg' => "添加失败，" . $e->getMessage()]);
        }
    }

    //修改
    function update(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id' => [
                'required',
                'integer',
                'exists:pre_emp,emp_id'
            ],
            'emp_dept_id' => [
                'exists:pre_dept,dept_id',
            ],
            'emp_name' => 'min:2|max:12'
        ],[
            'emp_dept_id.exists' => '部门不存在',
            "emp_name.max" => "部门名称最长为12个字符",
            "emp_name.min" => "部门名称最短为12个字符",
            "emp_id.required" => "主键不能为空",
            "emp_id.integer" => "主键只能为数字",
            "emp_id.exists" => "主键不存在",
        ]);
        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->toArray() as $v) {
                $msg = $v[0];
            }
            return response()->json(['code' => 1, 'msg' => $msg]);
        }
        try{
            $data = [
                'emp_id' => $request->input('emp_id'),
            ];
            if($request->input('emp_dept_id',null) !== null){
                $data['emp_dept_id'] = $request->input('emp_dept_id');
            }
            if($request->input('emp_name',null) !== null){
                $data['emp_name'] = $request->input('emp_name');
            }
            if($request->input('emp_birth',null) !== null){
                $data['emp_birth'] = $request->input('emp_birth');
            }
            if($request->input('emp_entry',null) !== null){
                $data['emp_entry'] = $request->input('emp_entry');
            }
            $info = Employee::where("emp_id",$data['emp_id'])->first()->update($data);
            return response()->json(['code' => 0, 'msg' => "修改成功"]);
        }catch (QueryException $e){
            return response()->json(['code' => 1, 'msg' => "修改失败，" . $e->getMessage()]);
        }
    }

    //查询
    function get(Request $request){
        $page = $request->input("page",0) - 1;
        $size = $request->input("limit",10);
        $searchField = $request->input("searchField",null);
        $searchValue = $request->input("searchValue",null);
        $sortField = $request->input("sortField","emp_id");
        $sortValue = $request->input("sortValue","asc");
        if($sortField=="dept_name"){
            $sortField="emp_dept_id";
        }
        try{
            if(isset($searchField) && isset($searchValue)){
                $count = Employee::join('pre_dept', 'emp_dept_id', 'dept_id')->where($searchField,"like","%{$searchValue}%")->orderBy($sortField,$sortValue)->count();
                $info = Employee::join('pre_dept', 'emp_dept_id', 'dept_id')->where($searchField,"like","%{$searchValue}%")->orderBy($sortField,$sortValue)->offset($page*$size)->limit($size)->get();
            }else{
                $count = Employee::join('pre_dept', 'emp_dept_id', 'dept_id')->orderBy($sortField,$sortValue)->count();
                $info = Employee::join('pre_dept', 'emp_dept_id', 'dept_id')->orderBy($sortField,$sortValue)->offset($page*$size)->limit($size)->get();
            }
            return response()->json(['code' => 0, 'msg' => "查询成功","count"=>$count,"data"=>$info])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }catch (QueryException $e){
            return response()->json(['code' => 1, 'msg' => "查询失败，" . $e->getMessage()]);
        }
    }

    //获取字段列表
    function getFields(){
        return response()->json(['code' => 0, 'msg' => "查询成功","data"=>Employee::getFields()])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
