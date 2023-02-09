@extends('home.base')
@section('sidebar')
    <ul class="layui-nav layui-nav-tree" lay-filter="leftnav">
        <li class="layui-nav-item">
            <a href="{{ route('userIndex') }}">服务器信息</a>
        </li>
        <li class="layui-nav-item ">
            <a href="{{ route('department') }}">部门管理</a>
        </li>
        <li class="layui-nav-item">
            <a href="javascript:;">员工管理</a>
        </li>
        <li class="layui-nav-item layui-this">
            <a href="javascript:;">Post请求测试</a>
        </li>
    </ul>
@endsection
@section('content')
    <div class="layui-tab layui-tab-card" style="height: 100%">
        <ul class="layui-tab-title">
            <li class="layui-this">Post请求测试</li>
        </ul>
        <div class="layui-tab-content" style="height: 100px;">
            <div class="layui-tab-item layui-show">
                <form class="layui-form" action="" method="POST">
                    <div class="layui-form-item">
                        <label class="layui-form-label">json内容</label>
                        <div class="layui-input-block">
                            <textarea placeholder="请输入内容" class="layui-textarea" id="aaaa"></textarea>
                        </div>
                        @csrf
                        <br>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button type="button" id="submit" class="layui-btn"
                                    onclick="submitclick()">立即提交</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection
    @section('js')
        <script>
            const submitclick = () => {
                var v = document.getElementById('aaaa').value;
                const instance = axios.create({
                    baseURL: "{{ route('employeeDelete') }}",
                    timeout: 1000,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                instance.post("", JSON.parse(v))
                    .then(function(response) {
                        console.log(response);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        </script>
    @endsection
