<?php $this->load->view('admin/header');?>

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th width="10%">
                搜索用户名：
            </th>
            <td>
                <form class="form-inline" action="" method="post">
                    <input class="form-control" type="text" name="username" value="">
                    <input class="btn btn-success" type="submit" value="搜索">
                </form>
            </td>
        </tr>
    </table>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th width="10%">用户名</th>
            <th>操作</th>
        </tr>
        <?php foreach ($user_data as $v):?>
            <th><?=$v['username']?></th>
            <td>
                <?php if(in_array($v['id'], $uids)):?>
                    已经是<?=$group_name?>
                <?php else:?>
                    <a href="<?=site_url() . '/admin/rule/add_user_to_group/' . $v['id'].'/'.$group_id.'/'.$username?>">设为<?=$group_name?></a>
                <?php endif;?>
            </td>
        <?php endforeach;?>
    </table>

<?php $this->load->view('admin/footer');?>