<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class DepartmentController extends Controller
{
    //
    function index(){
        return view("home.department");
    }

    function add(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_name' => [
                'required',
                'max:12',
                'min:2',
                'unique:pre_dept,dept_name'
            ]
        ],[
            'dept_name.required'=>'dept_name字段不能为空',
            "dept_name.max" => "部门名称最长为12个字符",
            "dept_name.min" => "部门名称最短为12个字符",
            'dept_name.unique' => '部门名称已存在'
        ]);
        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->toArray() as $v) {
                $msg = $v[0];
            }
            return response()->json(['code' => 1, 'msg' => $msg]);
        }
        $data = [
            'dept_id' => $request->input('dept_id',null),
            'dept_name' => $request->input('dept_name'),
        ];
        try{
            $info = Department::insert($data);
            return response()->json(['code' => 0, 'msg' => "添加成功"]);
        }catch (QueryException $e){
            return response()->json(['code' => 1, 'msg' => "添加失败，" . $e->getMessage()]);
        }
    }

    function update(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_id' => 'required',
            'dept_name' => [
                'required',
                'max:12',
                'min:2',
                'unique:pre_dept,dept_name'
            ]
        ],[
            'dept_id.required'=>'dept_id字段不能为空',
            'dept_name.required'=>'dept_name字段不能为空',
            "dept_name.max" => "部门名称最长为12个字符",
            "dept_name.min" => "部门名称最短为12个字符",
            'dept_name.unique' => '部门名称已存在或未修改内容'
        ]);
        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->toArray() as $v) {
                $msg = $v[0];
            }
            return response()->json(['code' => 1, 'msg' => $msg]);
        }
        $data = [
            'dept_id' => $request->input('dept_id'),
            'dept_name' => $request->input('dept_name'),
        ];
        try{
            $info = Department::where("dept_id",$data['dept_id'])->first()->update($data);
            return response()->json(['code' => 0, 'msg' => "修改成功"]);
        }catch (QueryException $e){
            return response()->json(['code' => 1, 'msg' => "修改失败，" . $e->getMessage()]);
        }
    }

    function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'dept_id' => 'required',
        ],[
            'dept_id.required'=>'dept_id字段不能为空',
        ]);
        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->toArray() as $v) {
                $msg = $v[0];
            }
            return response()->json(['code' => 1, 'msg' => $msg]);
        }
        $dept_id = $request->input('dept_id');
        try{
            $info = Department::destroy($dept_id);
            return response()->json(['code' => 0, 'msg' => "删除成功"]);
        }catch (QueryException $e){
            return response()->json(['code' => 1, 'msg' => "删除失败，" . $e->getMessage()]);
        }
    }

    function get(Request $request){
        $page = $request->input("page",0) - 1;
        $size = $request->input("limit",10);
        $searchField = $request->input("searchField",null);
        $searchValue = $request->input("searchValue",null);
        $sortField = $request->input("sortField","dept_id");
        $sortValue = $request->input("sortValue","asc");
        try{
            if(isset($searchField) && isset($searchValue)){
                $count = Department::where($searchField,"like","%{$searchValue}%")->orderBy($sortField,$sortValue)->count();
                $info = Department::where($searchField,"like","%{$searchValue}%")->orderBy($sortField,$sortValue)->offset($page*$size)->limit($size)->get();
            }else{
                $count = Department::orderBy($sortField,$sortValue)->count();
                $info = Department::orderBy($sortField,$sortValue)->offset($page*$size)->limit($size)->get();
            }
            return response()->json(['code' => 0, 'msg' => "查询成功","count"=>$count,"data"=>$info])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }catch (QueryException $e){
            return response()->json(['code' => 1, 'msg' => "查询失败，" . $e->getMessage()]);
        }
    }


}
