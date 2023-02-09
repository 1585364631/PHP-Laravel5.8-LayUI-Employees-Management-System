<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 引入静态文件 -->
    <link rel="stylesheet" href="{{ asset('home') }}/common/twitter-bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('home') }}/common/font-awesome-4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('home') }}/common/toastr.js/2.1.4/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('home') }}/css/main.css">
    <script src="{{ asset('home') }}/common/jquery/1.12.4/jquery.min.js"></script>
    <script src="{{ asset('home') }}/common/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('home') }}/common/toastr.js/2.1.4/toastr.min.js"></script>
    <script src="{{ asset('home') }}/js/main.js"></script>
    <title>登录</title>
</head>

<body class="login">
    <div class="container">
        <!-- 登录表单 -->
        <form action="{{ url('/check') }} " method="post" class="j-login">
            <h1>员工管理系统</h1>
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="用户名" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="密码" required>
            </div>
            <div class="form-group">
                <input type="text" name="yzm" class="form-control" placeholder="验证码" required>
            </div>
            <!-- 验证码 -->
            <div class="form-group">
                <div class="login-captcha"><img src="{{ captcha_src() }}" alt="captcha"></div>
            </div>
            <div class="form-group">
                @csrf
                <input type="submit" class="btn btn-lg btn-block btn-success" value="登录">
            </div>
        </form>
    </div>
    <script>
        $('.login-captcha img').click(function() {
            $(this).attr('src', '{{ captcha_src() }}' + '?_=' + Math.random());

        });
        main.ajaxForm('.j-login', function() {
            // 登入成功后延迟一秒
            setTimeout(() => {
                location.href = "{{ route('userIndex') }}";
            }, 1000);
        });
    </script>
</body>

</html>
