<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_admin_nav extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'admin_nav';
        $this->load->model('Model_auth');
    }

    /**
     * 获取全部菜单
     * @param  string $type tree获取树形结构 level获取层级结构
     * @return array        结构数据
     */
    public function getTreeData($type = 'tree', $order = '')
    {
        // 判断是否需要排序
        if (empty($order)) {
            $data = $this->get();
        } else {
            $sql = "SELECT * FROM `elk_admin_nav` ORDER BY order_number is null, $order";
            $query = $this->db->query($sql);
            $data = $query->result_array();
        }

        // 获取树形或者结构数据
        if ($type == 'tree') {
            $data = $this->data->tree($data, 'name', 'id', 'pid');
        } elseif ($type = "level") {
            $data = $this->data->channelLevel($data, 0, '&nbsp;', 'id');

            // 显示有权限的菜单
            foreach ($data as $k => $v) {
                if ($this->Model_auth->check($v['mca'], isset($_SESSION['user']['id'])?$_SESSION['user']['id']:0)) {
                    foreach ($v['_data'] as $m => $n) {
                        if (!$this->Model_auth->check($n['mca'], isset($_SESSION['user']['id'])?$_SESSION['user']['id']:0)) {
                            unset($data[$k]['_data'][$m]);
                        }
                    }
                } else {
                    // 删除无权限的菜单
                    unset($data[$k]);
                }
            }
        }
        return $data;
    }

    /**
     * 数据排序
     * @param  array $data   数据源
     * @param  string $id    主键
     * @param  string $order 排序字段
     * @return boolean       操作是否成功
     */
    public function orderData($data, $id='id' ,$order = 'order_number')
    {
        foreach ($data as $k => $v) {
            $v = empty($v) ? null : $v;
            $this->where(array($id => $k))->save(array($order => $v));
        }
        return true;
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