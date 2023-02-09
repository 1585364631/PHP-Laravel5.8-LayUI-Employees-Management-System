<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //主页跳转
    function index(){
        $data = [
            '服务器IP地址' => $_SERVER['SERVER_ADDR'],
            '域名' => $_SERVER['SERVER_NAME'],
            '端口' => $_SERVER['SERVER_PORT'],
            '服务器参数' => php_uname(),
            'PHP版本' => PHP_VERSION,
            'Laravel版本' => app()::VERSION,
            'PHP运行方式' => php_sapi_name(),
            '文件最大上传（MB）' => get_cfg_var ("upload_max_filesize")?get_cfg_var ("upload_max_filesize"):"不允许",
            'PHP脚本最大运行时间' => get_cfg_var("max_execution_time")."秒",
            'PHP脚本最大运行内存' => get_cfg_var ("memory_limit")?get_cfg_var("memory_limit"):"无",
            '客户端IP地址' => request()->getClientIp()
        ];
        return view("home.index")->with('data',$data);
    }

    function post(){
        return view("home.post");
    }

    function test(){
        return view('home.test');
    }
}
