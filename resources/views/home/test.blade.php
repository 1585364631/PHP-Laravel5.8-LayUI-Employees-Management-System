<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>示例演示</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('layui') }}/css/layui.css">
</head>

<body>
    <form class="layui-form" action="">
        <div class="layui-input-inline">
            <select name="quiz1">
                <option value="">请选择省</option>
                <option value="浙江" selected="">浙江省</option>
                <option value="你的工号">江西省</option>
                <option value="你最喜欢的老师">福建省</option>
            </select>
        </div>
    </form>


    <!-- 注意：项目正式环境请勿引用该地址 -->
    <script src="{{ asset('layui') }}/layui.js"></script>

    <script></script>

</body>

</html>
