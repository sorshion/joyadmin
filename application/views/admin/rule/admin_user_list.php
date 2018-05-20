<?php $this->load->view('admin/header');?>

<ul id="myTab" class="nav nav-tabs">
    <li class="active">
        <a href="#">管理员列表</a>
    </li>
    <li>
        <a href="<?=site_url()?>/admin/rule/add_admin">添加管理员</a>
    </li>
</ul>

<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="10%">用户名</th>
        <th>用户组</th>
        <th>操作</th>
    </tr>
    <?php foreach ($data as $v):?>
        <tr>
            <td><?=$v['username'];?></td>
            <td><?=$v['title'];?></td>
            <td>
                <a href="<?=site_url() . '/admin/rule/edit_admin/' . $v['id']?>">修改权限或密码</a> |
                <a href="<?='javascript:if(confirm(\'确定删除？\'))location=\''?><?=site_url() . '/admin/rule/delete_admin/' . $v['id'] . '\'';?>">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
</table>

<?php $this->load->view('admin/footer');?>
