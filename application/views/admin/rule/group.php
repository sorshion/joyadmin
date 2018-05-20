<?php $this->load->view('admin/header');?>

<ul id="myTab" class="nav nav-tabs">
    <li class="active">
        <a href="#home" data-toggle="tab">用户组列表</a>
    </li>
    <li>
        <a href="javascript:;" onclick="add()">添加用户组</a>
    </li>
</ul>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="home">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th>用户组名</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $d):?>
                <tr>
                    <td><?=$d['title'];?></td>
                    <td>
                        <a href="javascript:void(0);" ruleId="<?=$d['id']?>" ruleTitle="<?=$d['title'];?>" onclick="edit(this)">修改</a> |
                        <a href="<?='javascript:if(confirm(\'确定删除？\'))location=\''?><?=site_url() . '/admin/rule/delete_group/' . $d['id'] . '\'';?>">删除</a> |
                        <a href="<?=site_url() . '/admin/rule/rule_group/' . $d['id']?>">分配权限</a> |
                        <a href="<?=site_url() . '/admin/rule/check_user/' . $d['id']?>">添加成员</a>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>

<!-- 添加菜单模态框开始 -->
<div class="modal inmodal" id="joy-add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span aria-hidden="true">×</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">
                    添加菜单
                </h4>
            </div>
            <div class="modal-body">
                <form  class="form-inline" action="<?=site_url()?>/admin/rule/add_group" method="post">
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <tr>
                            <th width="20%">用户组名：</th>
                            <td>
                                <input class="form-control" type="text" name="title">
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
            </div>
        </div>
    </div>
</div>
<!-- 添加菜单模态框结束 -->

<!-- 修改菜单模态框开始 -->
<div class="modal inmodal" id="joy-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span aria-hidden="true">×</span><span class="sr-only">关闭</span>
                </button>
                <h4 class="modal-title">
                    修改菜单
                </h4>
            </div>
            <div class="modal-body">
                <form  class="form-inline" action="<?=site_url()?>/admin/rule/edit_group" method="post">
                    <input type="hidden" name="id">
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <tr>
                            <th width="20%">规则名：</th>
                            <td>
                                <input class="form-control" type="text" name="title">
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td>
                                <input class="btn btn-success" type="submit" value="修改">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- 修改菜单模态框结束 -->
<?php $this->load->view('admin/footer');?>
<script src="<?=base_url()?>public/admin/js/content.js?v=1.0.0"></script>

<script>
    // 添加菜单
    function add(){
        $("input[name='title']").val('');
        $('#joy-add').modal('show');
    }

    // 修改菜单
    function edit(obj){
        var ruleId=$(obj).attr('ruleId');
        var ruletitle=$(obj).attr('ruletitle');
        $("input[name='id']").val(ruleId);
        $("input[name='title']").val(ruletitle);
        $('#joy-edit').modal('show');
    }
</script>
