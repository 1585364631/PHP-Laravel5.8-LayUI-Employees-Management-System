<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends Controller
{
    //显示登陆页面
    public function login()
    {
        return view('home.login');
    }

    //验证用户登陆
    public function check(Request $request)
    {   //定义自动验证规则
        $rule = [
           'username'=>'required',
           'password'=>'required|min:6',
        ];
        //验证规则对应的错误提示信息
        $message = [
            'username.required'=>'用户名不能为空',
            'password.required'=>'密码不能为空',
            'password.min'=>'密码最少为6位',
        ];
        //验证码(mews/captcha)3.2版本，前后端分离抽离需要出来单独认证，传统验证器使用不了
        if(!captcha_check($request->input("yzm"))){
            if($request->input("yzm","") == ""){
                return response()->json(['code' => 0, 'msg' => "验证码不能为空"]);
            }
            return response()->json(['code' => 0, 'msg' => "验证码有误"]);
        }
        //输入信息自动验证
        $validator=Validator::make($request->all(),$rule,$message);
        //将验证错误信息返回给浏览器
        if ($validator->fails()) {
            foreach ($validator->getMessageBag()->toArray() as $v) {
                $msg = $v[0];
            }
            return response()->json(['code' => 0, 'msg' => $msg]);
        }
        //账号密码验证
        $username=$request->get('username');
        $password=$request->get('password');
        $user=User::where('username',$username)->first();
        if($user->password==md5(md5($password).$user->salt)){
            Session::put('user',['id'=>$user->id,'name'=>$username]);
            return response()->json(['code' => 1, 'msg' => '登陆成功！']);
        }else{
            return response()->json(['code' => 0, 'msg' => '登陆失败！']);
        }
    }

    //用户退出
    public function logout()
    {
       if(request()->session()->has('user')){
        request()->session()->pull('user');
       }
       return  redirect()->route("userLogin");
    }
}
