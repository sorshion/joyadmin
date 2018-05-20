<?php $this->load->view('admin/header');?>

<h1 class="text-center">为<span style="color:red"><?=$group_data['title']?></span>分配权限</h1>
<form action="" method="post">
    <input type="hidden" name="id" value="<?=$group_data['id']?>">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <?php foreach ($rule_data as $v):?>
            <?php if (count($v['_data'])):?>
                <tr class="b-group">
                    <th width="10%">
                        <label>
                            <?=$v['title']?>
                            <input type="checkbox" name="rule_ids[]" value="<?=$v['id']?>" <?php if(in_array($v['id'], $group_data['rules'])){echo 'checked="checked"';}?> onclick="checkAll(this)">
                        </label>
                    </th>
                    <td class="b-child">
                        <?php foreach ($v['_data'] as $n):?>
                            <table class="table table-striped table-bordered table-hover table-condensed">
                                <tr class="b-group">
                                    <th width="10%">
                                        <label>
                                            <?=$n['title']?><input type="checkbox" name="rule_ids[]" value="<?=$n['id']?>" <?php if(in_array($v['id'], $group_data['rules'])){echo 'checked="checked"';}?> onclick="checkAll(this)">
                                        </label>
                                    </th>
                                    <td>
                                        <?php if (count($n['_data'])):?>
                                            <?php foreach ($n['_data'] as $c):?>
                                                <label>
                                                    <?=$c['title']?><input type="checkbox" name="rule_ids[]" value="<?=$c['id']?>" <?php if(in_array($c['id'], $group_data['rules'])){echo 'checked="checked"';}?>>
                                                </label>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            </table>
                        <?php endforeach;?>
                    </td>
                </tr>
            <?php else:?>
                <tr class="b-group">
                    <th width="10%">
                        <label>
                            <?=$v['title']?><input type="checkbox" name="rule_ids[]" value="<?=$v['id']?>" <?php if(in_array($v['id'], $group_data['rules'])){echo 'checked="checked"';}?> onclick="checkAll(this)" >
                        </label>
                    </th>
                    <td></td>
                </tr>
            <?php endif;?>
        <?php endforeach;?>
        <tr>
            <th></th>
            <td>
                <input class="btn btn-success" type="submit" value="提交">
            </td>
        </tr>
    </table>
</form>

<?php $this->load->view('admin/footer');?>

<script>
    function checkAll(obj){
        $(obj).parents('.b-group').eq(0).find("input[type='checkbox']").prop('checked', $(obj).prop('checked'));
    }
</script>
