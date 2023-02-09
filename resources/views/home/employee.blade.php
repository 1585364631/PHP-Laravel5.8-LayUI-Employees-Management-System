@extends('home.base')
@section('sidebar')
    <ul class="layui-nav layui-nav-tree" lay-filter="leftnav">
        <li class="layui-nav-item">
            <a href="{{ route('userIndex') }}">服务器信息</a>
        </li>
        <li class="layui-nav-item">
            <a href="{{ route('department') }}">部门管理</a>
        </li>
        <li class="layui-nav-item layui-this">
            <a href="{{ route('employee') }}">员工管理</a>
        </li>
    </ul>
@endsection
@section('content')
    <div class="layui-tab layui-tab-card" style="height: 100%;width:100%">
        <div
            style="display:flex;align-items: center;justify-content: center;height: 40px;position:absolute;z-index:999;right:20px;">
            <button class="layui-btn layui-btn-sm" id="employeeAdd">添加员工</button>
            <form class="layui-form">
                <div class="layui-inline">
                    <label class="layui-form-label">排序方式</label>
                    <div class="layui-input-inline" style="width:110px">
                        <select name="selectSortFiled" id="selectSortFiled" lay-filter="selectSortFiled"></select>
                    </div>
                    <div class="layui-input-inline" style="width:80px">
                        <select name="selectSortType" id="selectSortType" lay-filter="selectSortType">
                            <option selected value="asc">正序</option>
                            <option value="desc">倒序</option>
                        </select>
                    </div>
                    {{-- <button class="layui-btn layui-btn-sm" id="selectSort" type="button">排序</button> --}}
                </div>
            </form>

            <form class="layui-form">
                <div class="layui-inline">
                    <label class="layui-form-label">搜索方式</label>
                    <div class="layui-input-inline" style="width:100px">
                        <select name="selectSearchType" id="selectSearchType"></select>
                    </div>
                    <input type="text" name="searchName" id="searchName" lay-verify="title" autocomplete="off"
                        placeholder="请输入相应字段搜索内容" class="layui-input-inline layui-input" style="width: 200px;">
                    <button class="layui-btn layui-btn-sm" id="DataSearch" type="button">查询</button>
                </div>
            </form>

        </div>
        <ul class="layui-tab-title">
            <li class="layui-this">员工列表</li>
        </ul>
        <div class="layui-tab-content">
            <table id="dataList" lay-filter="test"></table>
        </div>
        <template id="editData">
            <form class="layui-form"
                style="padding: 30px;display:flex;justify-content: center;flex-wrap: wrap;align-items: center;">
                <div class="layui-form-item" style="width: 75%">
                    <label class="layui-form-label">员工名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="employeeName" id="employeeName" lay-verify="title" autocomplete="off"
                            placeholder="请输入员工名称" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item" style="width: 75%">
                    <label class="layui-form-label">出生日期</label>
                    <div class="layui-input-block">
                        <input type="text" name="employeeBirth" id="employeeBirth" lay-verify="title" autocomplete="off"
                            placeholder="请输入出生日期" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item" style="width: 75%">
                    <label class="layui-form-label">入职时间</label>
                    <div class="layui-input-block">
                        <input type="text" name="employeeEntry" id="employeeEntry" lay-verify="title" autocomplete="off"
                            placeholder="请输入入职时间" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item" style="width: 75%">
                    <label class="layui-form-label">所属部门</label>
                    <div class="layui-input-block">
                        <select name="selectDepartment" id="selectDepartment"></select>
                    </div>
                </div>
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
            layui.use(['table', 'laydate', 'form'], function() {
                const instance = axios.create({
                    timeout: 1000,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const getDepartment = (select = "") => {
                    instance.post("{{ route('departmentGet') }}", {
                            "page": 1,
                            "limit": 9999
                        })
                        .then(function(response) {
                            if (response.data.code == 0) {
                                document.getElementById('selectDepartment').innerHTML = ""
                                response.data.data.forEach(i => {
                                    if (select == i.dept_id) {
                                        document.getElementById('selectDepartment').innerHTML =
                                            document.getElementById('selectDepartment').innerHTML +
                                            "<option value='" + i.dept_id + "' selected>" + i
                                            .dept_name +
                                            "</option>"
                                    } else {
                                        document.getElementById('selectDepartment').innerHTML =
                                            document.getElementById('selectDepartment').innerHTML +
                                            "<option value='" + i.dept_id + "'>" + i.dept_name +
                                            "</option>"
                                    }
                                });
                                form.render()
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

                const getFileds = () => {
                    instance.post("{{ route('employeeGetFields') }}", {}).
                    then(function(response) {
                        selectSortFiled.innerHTML = ""
                        selectSearchType.innerHTML = ""
                        var lock = true
                        response.data.data.forEach(i => {
                            if (lock) {
                                selectSortFiled.innerHTML = selectSortFiled.innerHTML +
                                    "<option value='" + i
                                    .name + "' selected>" + i
                                    .value +
                                    "</option>"
                                selectSearchType.innerHTML = selectSearchType.innerHTML +
                                    "<option value='" + i
                                    .name + "' selected>" + i
                                    .value +
                                    "</option>"
                                lock = false
                            } else {
                                selectSortFiled.innerHTML = selectSortFiled.innerHTML +
                                    "<option value='" + i
                                    .name + "'>" + i
                                    .value +
                                    "</option>"
                                selectSearchType.innerHTML = selectSearchType.innerHTML +
                                    "<option value='" + i
                                    .name + "'>" + i
                                    .value +
                                    "</option>"
                            }
                        });
                        form.render()
                    }).catch(function(error) {
                        layer.msg(error, {
                            icon: 5,
                        });
                    });
                }

                var searchField = ""
                var searchValue = ""
                var searchName = document.getElementById('searchName')
                var selectSortFiled = document.getElementById('selectSortFiled')
                var selectSearchType = document.getElementById('selectSearchType')
                var selectSortType = document.getElementById('selectSortType')
                var laydate = layui.laydate;
                var form = layui.form;

                getFileds()

                var table = layui.table;
                table.render({
                    elem: '#dataList',
                    method: "get",
                    url: "{{ route('employeeGet') }}",
                    page: true,
                    height: 'full-200',
                    cols: [
                        [{
                            field: 'emp_id',
                            title: 'ID',
                            fixed: 'left',
                            sort: true
                        }, {
                            field: 'dept_name',
                            title: '部门',
                            sort: true
                        }, {
                            field: 'emp_name',
                            title: '员工名称',
                            sort: true
                        }, {
                            field: 'emp_birth',
                            title: '出生日期',
                            sort: true
                        }, {
                            field: 'emp_entry',
                            title: '入职时间',
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





                // document.getElementById('selectSort').onclick = () => {
                //     if (searchName.value == "" || searchName.value == undefined || searchName.value == null) {
                //         table.reload('dataList', {
                //             page: 1,
                //             where: {
                //                 "sortField": selectSortFiled.value,
                //                 "sortValue": selectSortType.value
                //             }
                //         });
                //         return
                //     } else {
                //         table.reload('dataList', {
                //             where: {
                //                 "searchField": selectSearchType.value,
                //                 "searchValue": searchName.value,
                //                 "sortField": selectSortFiled.value,
                //                 "sortValue": selectSortType.value
                //             },
                //             page: 1,
                //         });
                //     }
                // }

                const sortUpdate = () => {
                    table.reload('dataList', {
                        page: 1,
                        where: {
                            "sortField": selectSortFiled.value,
                            "sortValue": selectSortType.value
                        }
                    });
                }

                const searchUpdate = () => {
                    table.reload('dataList', {
                        where: {
                            "searchField": selectSearchType.value,
                            "searchValue": searchName.value,
                            "sortField": selectSortFiled.value,
                            "sortValue": selectSortType.value
                        },
                        page: 1,
                    });
                }

                const allUpdate = () => {
                    if (searchName.value == "" || searchName.value == undefined || searchName.value == null) {
                        sortUpdate()
                    } else {
                        searchUpdate()
                    }
                }

                document.getElementById('DataSearch').onclick = () => {
                    allUpdate()
                }

                form.on('select(selectSortType)', function(data) {
                    allUpdate()
                });

                form.on('select(selectSortFiled)', function(data) {
                    allUpdate()
                });

                document.getElementById('employeeAdd').onclick = () => {
                    layer.open({
                        title: '添加',
                        type: 1,
                        area: ['70%', '70%'],
                        content: document.getElementById('editData').innerHTML,
                        success: function(layero, index) {
                            document.getElementById('editSubmit').onclick = () => {
                                var employeeName = document.getElementById(
                                    'employeeName')
                                var selectDepartment = document.getElementById(
                                    'selectDepartment')
                                var employeeEntry = document.getElementById(
                                    'employeeEntry')
                                var employeeBirth = document.getElementById(
                                    'employeeBirth')
                                if (employeeName.value == "") {
                                    layer.msg("员工姓名不能为空", {
                                        icon: 5,
                                    });
                                    return
                                }
                                instance.post("{{ route('employeeAdd') }}", {
                                        "emp_dept_id": selectDepartment.value,
                                        "emp_name": employeeName.value,
                                        "emp_birth": employeeBirth.value,
                                        "emp_entry": employeeEntry.value
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
                            laydate.render({
                                elem: '#employeeBirth',
                                type: 'datetime'
                            });
                            laydate.render({
                                elem: '#employeeEntry',
                                type: 'datetime'
                            });
                            getDepartment()
                            document.getElementById('employeeName').value = ""
                            document.getElementById('selectDepartment').value = ""
                            document.getElementById('employeeEntry').value = ""
                            document.getElementById('employeeBirth').value = ""
                            form.render()
                        }
                    });
                }

                table.on('tool(test)', function(obj) {
                    var data = obj.data;
                    if (obj.event === 'del') {
                        layer.confirm('确认要删除该员工吗', function(index) {
                            instance.post("{{ route('employeeDelete') }}", {
                                    "emp_id": obj.data.emp_id
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
                            area: ['70%', '70%'],
                            content: document.getElementById('editData').innerHTML,
                            success: function(layero, index) {
                                document.getElementById('editSubmit').onclick = () => {
                                    var employeeName = document.getElementById(
                                        'employeeName')
                                    var selectDepartment = document.getElementById(
                                        'selectDepartment')
                                    var employeeEntry = document.getElementById(
                                        'employeeEntry')
                                    var employeeBirth = document.getElementById(
                                        'employeeBirth')
                                    if (employeeName.value == "") {
                                        layer.msg("员工姓名不能为空", {
                                            icon: 5,
                                        });
                                        return
                                    }
                                    instance.post("{{ route('employeeUpdate') }}", {
                                            "emp_id": obj.data.emp_id,
                                            "emp_dept_id": selectDepartment.value,
                                            "emp_name": employeeName.value,
                                            "emp_birth": employeeBirth.value,
                                            "emp_entry": employeeEntry.value
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
                                laydate.render({
                                    elem: '#employeeBirth',
                                    type: 'datetime'
                                });
                                laydate.render({
                                    elem: '#employeeEntry',
                                    type: 'datetime'
                                });
                                getDepartment(obj.data.emp_dept_id)
                                document.getElementById('employeeName').value = obj.data.emp_name
                                document.getElementById('selectDepartment').value = obj.data
                                    .dept_name
                                document.getElementById('employeeEntry').value = obj.data.emp_entry
                                document.getElementById('employeeBirth').value = obj.data.emp_birth
                                form.render()
                            }
                        });
                    }
                });

            });
        </script>
    @endsection
