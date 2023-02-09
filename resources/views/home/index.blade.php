@extends('home.base')
@section('sidebar')
    <ul class="layui-nav layui-nav-tree" lay-filter="leftnav">
        <li class="layui-nav-item layui-this">
            <a href="{{ route('userIndex') }}">服务器信息</a>
        </li>
        <li class="layui-nav-item">
            <a href="{{ route('department') }}">部门管理</a>
        </li>
        <li class="layui-nav-item">
            <a href="{{ route('employee') }}">员工管理</a>
        </li>
    </ul>
@endsection
@section('content')
    <div class="layui-tab layui-tab-card" style="height: 100%">
        <ul class="layui-tab-title">
            <li class="layui-this">服务器信息</li>
        </ul>
        <div class="layui-tab-content" style="height: 100px;">
            <div class="layui-tab-item layui-show">
                <table class="layui-table">
                    <colgroup>
                        <col width="150">
                        <col width="200">
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>类型</th>
                            <th>参数</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $i => $j)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $j }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
