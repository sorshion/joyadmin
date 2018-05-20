<?php $this->load->view('admin/header');?>

<ul id="myTab" class="nav nav-tabs">
    <li class="active">
        <a href="#home" data-toggle="tab">菜单列表</a>
    </li>
    <li>
        <a href="javascript:void(0);" onclick="add()">添加菜单</a>
    </li>
</ul>
<form action="<?=site_url()?>/admin/nav/order" method="post">
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">
            <table class="table table-striped table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th width="5%">排序</th>
                        <th>菜单名</th>
                        <th>连接</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $d):?>
                    <tr>
                        <td><input class="form-control" type="text" name="<?=$d['id']?>" value="<?=$d['order_number'];?>"></td>
                        <td><?=$d['_name'];?></td>
                        <td><?=$d['mca'];?></td>
                        <td>
                            <a href="javascript:void(0);" navId="<?=$d['id']?>" navName="<?=$d['name']?>"  onclick="add_child(this)">添加子菜单</a> |
                            <a href="javascript:void(0);" navId="<?=$d['id']?>" navName="<?=$d['name']?>" navMca="<?=$d['mca']?>" navIco="<?=$d['ico']?>" onclick="edit(this)">修改</a> |
                            <a href="<?='javascript:if(confirm(\'确定删除？\'))location=\''?><?=site_url() . '/admin/nav/delete/' . $d['id'] . '\'';?>">删除</a>
                        </td>

                    </tr>
                <?php endforeach;?>
                <tr>
                    <td colspan="4">
                        <input class="btn btn-success" type="submit" value="排序">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>
<!-- 添加菜单/子菜单模态框开始 -->
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
                <form  class="form-inline" action="<?=site_url()?>/admin/nav/add" method="post">
                    <input type="hidden" name="pid" value="0">
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <tr>
                            <th width="12%">菜单名：</th>
                            <td>
                                <input class="form-control" type="text" name="name">
                            </td>
                        </tr>
                        <tr>
                            <th>连接：</th>
                            <td>
                                <input class="form-control" type="text" name="mca"> 输入模块/控制器/方法即可 例如 admin/nav/index
                            </td>
                        </tr>
                        <tr>
                            <th>图标：</th>
                            <td>
                                <input class="form-control" type="text" name="ico">
                                font-awesome图标 输入fa fa- 后边的即可
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
<!-- 添加菜单/子菜单模态框结束 -->

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
                <form  class="form-inline" action="<?=site_url()?>/admin/nav/edit" method="post">
                    <input type="hidden" name="id">
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <tr>
                            <th width="12%">菜单名：</th>
                            <td>
                                <input class="form-control" type="text" name="name">
                            </td>
                        </tr>
                        <tr>
                            <th>连接：</th>
                            <td>
                                <input class="form-control" type="text" name="mca"> 输入模块/控制器/方法即可 例如 admin/nav/index
                            </td>
                        </tr>
                        <tr>
                            <th>图标：</th>
                            <td>
                                <input class="form-control" type="text" name="ico">
                                font-awesome图标 输入fa fa- 后边的即可
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
        $("input[name='name'],input[name='mca']").val('');
        $("input[name='pid']").val(0);
        $('#joy-add').modal('show');
    }

    // 添加子菜单
    function add_child(obj){
        var navId=$(obj).attr('navId');
        $("input[name='pid']").val(navId);
        $("input[name='name']").val('');
        $("input[name='mca']").val('');
        $("input[name='ico']").val('');
        $('#joy-add').modal('show');
    }

    // 修改菜单
    function edit(obj){
        var navId=$(obj).attr('navId');
        var navName=$(obj).attr('navName');
        var navMca=$(obj).attr('navMca');
        var navIco=$(obj).attr('navIco');
        $("input[name='id']").val(navId);
        $("input[name='name']").val(navName);
        $("input[name='mca']").val(navMca);
        $("input[name='ico']").val(navIco);
        $('#joy-edit').modal('show');
    }

</script>

