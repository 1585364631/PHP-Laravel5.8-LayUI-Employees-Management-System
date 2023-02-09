<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>员工管理系统</title>
    <link rel="stylesheet" href="{{ asset('layui') }}/css/layui.css">
    <script src="{{ asset('axios/axios.js') }}"></script>

</head>

<body>
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <div class="layui-logo layui-hide-xs layui-bg-black">
                <p style="color: #fac66f">员工管理系统</p>
            </div>
            <!-- 头部区域（可配合layui 已有的水平导航） -->
            <ul class="layui-nav layui-layout-left">
                <!-- 移动端显示 -->
                <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-header-event="menuLeft">
                    <i class="layui-icon layui-icon-spread-left"></i>
                </li>
                {{-- <li class="layui-nav-item layui-hide-xs layui-this"><a href="javascript:;">基础</a></li> --}}
            </ul>
            <ul class="layui-nav layui-layout-right">
                <li class="layui-nav-item layui-hide layui-show-md-inline-block">
                    <a href="javascript:;">
                        <img src="https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fbpic.51yuansu.com%2Fpic2%2Fcover%2F00%2F37%2F77%2F58121dd048cdb_610.jpg&refer=http%3A%2F%2Fbpic.51yuansu.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=auto?sec=1672903501&t=881e8711f0a16e30d625bdde55e900f8"
                            class="layui-nav-img">
                        {{ session()->get('user.name') }}
                    </a>
                    <dl class="layui-nav-child">
                        <dd><a href="{{ route('userLogout') }}">退出登录</a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item" lay-header-event="menuRight" lay-unselect>
                    <a href="javascript:;">
                        <i class="layui-icon layui-icon-more-vertical"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="layui-side layui-bg-black">
            <div class="layui-side-scroll">
                <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
                @yield('sidebar')
            </div>
        </div>

        <div class="layui-body">
            <!-- 内容主体区域 -->
            <div style="padding: 15px;">
                @yield('content')
            </div>
        </div>

        <div class="layui-footer">
            <!-- 底部固定区域 -->
            <span id="footinfo"></span>
        </div>
    </div>
    <script src="{{ asset('layui') }}/layui.js"></script>
    <script>
        var footinfo = document.getElementById('footinfo')
        setInterval(() => {
            footinfo.innerText = Date();
        }, 1000);
        //JS
        layui.use(['element', 'layer', 'util'], function() {
            var element = layui.element,
                layer = layui.layer,
                util = layui.util,
                $ = layui.$;

            //头部事件
            util.event('lay-header-event', {
                //左侧菜单事件
                menuLeft: function(othis) {
                    layer.msg('展开左侧菜单的操作', {
                        icon: 0
                    });
                },
                menuRight: function() {
                    layer.open({
                        type: 1,
                        content: '<div style="padding: 15px;">' +
                            "{{ session()->get('user.name') }}" +
                            '，欢迎您</div>',
                        area: ['260px', '100%'],
                        offset: 'rt',
                        anim: 5,
                        shadeClose: true
                    });
                }
            });
        });

        const is_e = (i) => {
            if (i == null || i == "" || i == undefined) {
                return true
            }
            return false
        }
    </script>
    @yield('js')

</body>


</html>
