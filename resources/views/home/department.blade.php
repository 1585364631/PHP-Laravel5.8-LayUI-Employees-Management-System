@extends('home.base')
@section('sidebar')
    <ul class="layui-nav layui-nav-tree" lay-filter="leftnav">
        <li class="layui-nav-item">
            <a href="{{ route('userIndex') }}">服务器信息</a>
        </li>
        <li class="layui-nav-item  layui-this">
            <a href="{{ route('department') }}">部门管理</a>
        </li>
        <li class="layui-nav-item">
            <a href="{{ route('employee') }}">员工管理</a>
        </li>
    </ul>
@endsection
@section('content')
    <div class="layui-tab layui-tab-card" style="height: 100%;width:100%">
        <div
            style="display:flex;align-items: center;justify-content: center;height: 40px;width:400px;position:absolute;z-index:999;right:5px">
            <button class="layui-btn layui-btn-sm" id="departmentAdd" style="margin-right: 20px">添加部门</button>
            <input type="text" name="searchName" id="searchName" lay-verify="title" autocomplete="off"
                placeholder="请输入搜索部门名称" class="layui-input" style="width: 200px;margin-right: 10px">
            <button class="layui-btn layui-btn-sm" id="departmentSearch">搜索</button>
        </div>
        <ul class="layui-tab-title">
            <li class="layui-this">部门列表</li>
        </ul>

        <div class="layui-tab-content">
            <table id="dataList" lay-filter="test"></table>
        </div>
        <template id="editData">
            <form style="padding: 30px;display:flex;justify-content: center;flex-wrap: wrap;align-items: center;">
                <div class="layui-form-item" style="width: 75%">
                    <label class="layui-form-label">部门名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="departmentName" id="departmentName" lay-verify="title"
                            autocomplete="off" placeholder="请输入部门名称" class="layui-input">
                    </div>
                </div>
                <br>
                <div class="layui-form-item" style="width: 75%;">
                    <div class="layui-input-block">
                        <button type="button" class="layui-btn" id="editSubmit">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </template>
    @endsection
    @section('js')
        <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
      </script>
        <script>
            layui.use('table', function() {
                const instance = axios.create({
                    timeout: 1000,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                var searchField = ""
                var searchValue = ""
                var searchName = document.getElementById('searchName')

                var table = layui.table;
                table.render({
                    elem: '#dataList',
                    method: "get",
                    url: "{{ route('departmentGet') }}",
                    page: true,
                    height: 'full-200',
                    cols: [
                        [{
                            field: 'dept_id',
                            title: 'ID',
                            fixed: 'left',
                            sort: true
                        }, {
                            field: 'dept_name',
                            title: '部门',
                            sort: true
                        }, {
                            fixed: 'right',
                            title: '操作',
                            width: 250,
                            minWidth: 200,
                            toolbar: '#barDemo'
                        }]
                    ],
                });

                document.getElementById('departmentSearch').onclick = () => {
                    searchValue = searchName.value
                    if (searchName.value == "") {
                        searchField = "";
                    } else {
                        searchField = "dept_name";
                    }
                    table.reload('dataList', {
                        where: {
                            "searchField": searchField,
                            "searchValue": searchValue
                        },
                        page: 1,
                    });
                }

                document.getElementById('departmentAdd').onclick = () => {
                    layer.open({
                        title: '添加',
                        type: 1,
                        area: ['600px', '300px'],
                        content: document.getElementById('editData').innerHTML,
                        success: function(layero, index) {
                            document.getElementById('editSubmit').onclick = () => {
                                var departmentName = document.getElementById(
                                    'departmentName')
                                if (departmentName.value == "") {
                                    layer.msg("内容不能为空", {
                                        icon: 5,
                                    });
                                    return
                                }
                                instance.post("{{ route('departmentAdd') }}", {
                                        "dept_name": departmentName.value
                                    })
                                    .then(function(response) {
                                        if (response.data.code == 0) {
                                            layer.msg(response.data.msg, {
                                                icon: 1,
                                            });
                                            table.reloadData('dataList', {});
                                            layer.close(index);
                                        } else {
                                            layer.msg(response.data.msg, {
                                                icon: 5,
                                            });
                                        }
                                    })
                                    .catch(function(error) {
                                        layer.msg(error, {
                                            icon: 5,
                                        });
                                    });
                            }
                        }
                    });
                    document.getElementById('departmentName').value = ""
                }

                table.on('tool(test)', function(obj) {
                    var data = obj.data;
                    if (obj.event === 'del') {
                        layer.confirm('真的要删除该部门吗？确认后所属该部门的所有员工都会被删除', function(index) {
                            instance.post("{{ route('departmentDelete') }}", {
                                    "dept_id": obj.data.dept_id
                                })
                                .then(function(response) {
                                    if (response.data.code == 0) {
                                        table.reloadData('dataList', {});
                                        layer.close(index);
                                        layer.msg(response.data.msg, {
                                            icon: 1,
                                        });
                                    } else {
                                        layer.msg(response.data.msg, {
                                            icon: 5,
                                        });
                                    }
                                })
                                .catch(function(error) {
                                    layer.msg(error, {
                                        icon: 5,
                                    });
                                });
                        });
                    } else if (obj.event === 'edit') {
                        layer.open({
                            title: '编辑',
                            type: 1,
                            area: ['600px', '300px'],
                            content: document.getElementById('editData').innerHTML,
                            success: function(layero, index) {
                                document.getElementById('editSubmit').onclick = () => {
                                    var departmentName = document.getElementById(
                                        'departmentName')
                                    if (departmentName.value == "") {
                                        layer.msg("内容不能为空", {
                                            icon: 5,
                                        });
                                        return
                                    }
                                    instance.post("{{ route('departmentUpdate') }}", {
                                            "dept_id": obj.data.dept_id,
                                            "dept_name": departmentName.value
                                        })
                                        .then(function(response) {
                                            if (response.data.code == 0) {
                                                layer.msg(response.data.msg, {
                                                    icon: 1,
                                                });
                                                table.reloadData('dataList', {});
                                                layer.close(index);
                                            } else {
                                                layer.msg(response.data.msg, {
                                                    icon: 5,
                                                });
                                            }
                                        })
                                        .catch(function(error) {
                                            layer.msg(error, {
                                                icon: 5,
                                            });
                                        });
                                }
                            }
                        });
                        document.getElementById('departmentName').value = obj.data.dept_name
                    }
                });

            });
        </script>
    @endsection
