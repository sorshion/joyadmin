<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_auth_group_access extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'auth_group_access';
    }

    /**
     * 根据group_id获取全部用户id
     * @param  int $group_id 用户组id
     * @return array         用户数组
     */
    public function getUidsByGroupId($group_id)
    {
        $user_ids = $this
            ->where(array('group_id' => $group_id))
            ->get();
        $res = [];
        foreach ($user_ids as $id) {
            $res[] = $id['uid'];
        }
        return $res;
    }

    /**
     * 获取管理员权限列表
     */
    public function getAllData()
    {
        $sql = "SELECT u.id,u.username,u.email,aga.group_id,ag.title FROM elk_auth_group_access aga RIGHT JOIN elk_users u ON aga.uid=u.id LEFT JOIN elk_auth_group ag ON aga.group_id=ag.id";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        // 获取第一条数据
        $first = $data[0];
        $first['title'] = array();
        $user_data[$first['id']] = $first;
        // 组合数组
        foreach ($data as $k => $v) {
            foreach ($user_data as $m => $n) {
                $uids = array_map(function ($a) {
                    return $a['id'];
                }, $user_data);
                if (!in_array($v['id'], $uids)) {
                    $v['title'] = array();
                    $user_data[$v['id']] = $v;
                }
            }
        }
        // 组合管理员title数组
        foreach ($user_data as $k => $v) {
            foreach ($data as $m => $n) {
                if ($n['id'] == $k) {
                    $user_data[$k]['title'][] = $n['title'];
                }
            }
            $user_data[$k]['title'] = implode('、', $user_data[$k]['title']);
        }
        // 管理组title数组用顿号连接
        return $user_data;

    }
}