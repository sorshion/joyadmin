<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<title>后台管理系统 - 登录</title>
<meta name="keywords" content="后台管理系统">
<meta name="description" content="后台管理系统">
<link href="<?=base_url()?>public/admin/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url()?>public/admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">
<link href="<?=base_url()?>public/admin/css/animate.css" rel="stylesheet">
<link href="<?=base_url()?>public/admin/css/style.css" rel="stylesheet">
<link href="<?=base_url()?>public/admin/css/login.css" rel="stylesheet">
<!--[if lt IE 9]>
<meta http-equiv="refresh" content="0;ie.html" />
<![endif]-->
<script>
    if (window.top !== window.self) {
        window.top.location = window.location;
    }
</script>

</head>

<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7">
            <div class="signin-info">
                <div class="logopanel m-b">
                    <h1>[ 你的名字 ]</h1>
                </div>
                <div class="m-b"></div>
                <h4>欢迎使用 <strong>后台管理系统</strong></h4>
            </div>
        </div>
        <div class="col-sm-5">
            <form method="post" action="<?=site_url()?>/admin/login/index">
                <h4 class="no-margins">登录：</h4>
                <input type="text" name='username' class="form-control uname" placeholder="用户名" />
                <input type="password" name='password' class="form-control pword m-b" placeholder="密码" />
                <button class="btn btn-success btn-block" type="submit">登录</button>
            </form>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            &copy; 2018 Yourdream
        </div>
    </div>
</div>
</body>

</html>
