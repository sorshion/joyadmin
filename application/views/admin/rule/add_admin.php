<?php $this->load->view('admin/header');?>
<ul id="myTab" class="nav nav-tabs">
    <li>
        <a href="<?=site_url()?>/admin/rule/admin_user_list">管理员列表</a>
    </li>
    <li class="active">
        <a href="#">添加管理员</a>
    </li>
</ul>

<form class="form-inline" method="post">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>管理组</th>
            <td>
                <?php foreach ($data as $v):?>
                    <?=$v['title']?>
                    <input class="xb-icheck" type="checkbox" name="group_ids[]" value="<?=$v['id']?>">
                <?php endforeach;?>
            </td>
        </tr>
        <tr>
            <th>姓名</th>
            <td>
                <input class="form-control" type="text" name="username">
            </td>
        </tr>
        <tr>
            <th>手机号</th>
            <td>
                <input class="form-control" type="text" name="phone">
            </td>
        </tr>
        <tr>
            <th>邮箱</th>
            <td>
                <input class="form-control" type="text" name="email">
            </td>
        </tr>
        <tr>
            <th>初始密码</th>
            <td>
                <input class="form-control" type="password" name="password1">
            </td>
        </tr>
        <tr>
            <th>确认密码</th>
            <td>
                <input class="form-control" type="password" name="password2">
            </td>
        </tr>
        <tr>
            <th>状态</th>
            <td>
                <span class="inputword">允许登录</span>
                <input class="xb-icheck" type="radio" name="status" value="1" checked="checked">
                &emsp;
                <span class="inputword">禁止登录</span>
                <input class="xb-icheck" type="radio" name="status" value="0">
            </td>
        </tr>
        <tr>
            <th></th>
            <td>
                <input class="btn btn-success" type="submit" value="添加">
            </td>
        </tr>
    </table>
</form>

<?php $this->load->view('admin/footer');?>
