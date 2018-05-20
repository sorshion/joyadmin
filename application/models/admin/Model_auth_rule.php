<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_auth_rule extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'auth_rule';
    }

    public function getTreeData($type = 'tree', $order = '', $name = 'name', $child = 'id', $parent = 'pid')
    {

        // 判断是否需要排序
        if (empty($order)) {
            $data = $this->get();
        } else {
            $query = $this->db->query("SELECT * FROM elk_auth_rule order by $order is null, $order");
            $data = $query->result_array();
        }
        // 获取树形或者结构数据
        if ($type == 'tree') {
            $data = $this->data->tree($data, $name, $child, $parent);
        } elseif ($type = "level") {
            $data = $this->data->channelLevel($data, 0, '&nbsp;', $child);
        }
        return $data;
    }

    public function deleteData($id)
    {
        $count = $this->where(array('pid'=>$id))->count();
        if ($count != 0) {
            return false;
        }
        $result = $this->where($id)->delete();
        return $result;
    }
}