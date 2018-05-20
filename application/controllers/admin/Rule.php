<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rule extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_auth');

        $this->load->model('admin/Model_users');
        $this->load->model('admin/Model_auth_rule');
        $this->load->model('admin/Model_auth_group');
        $this->load->model('admin/Model_auth_group_access');
    }

    /**
     * 权限列表
     */
    public function index()
    {
        $data = $this->Model_auth_rule->getTreeData('tree', 'id', 'title');
        $this->load->view('admin/rule/index', array('data' => $data));
    }

    /**
     * 添加权限
     * 默认给超级管理员所有权限
     */
    public function add()
    {
        $data       = $this->input->post();
        $status = $insert_id  = $this->Model_auth_rule->add($data);

        // 默认第一个为超级管理员
        $root = $this->Model_auth_group->where(1)->find();

        $save_arr = ['rules' => $root['rules'] . ',' . $insert_id];
        $status = $status && $this->Model_auth_group->where(1)->save($save_arr);

        if ($status) {
            $this->success('添加成功', 'admin/rule/index');
        } else {
            $this->error('添加失败,未事务控制');
        }
    }

    /**
     * 修改权限
     */
    public function edit()
    {
        $data = $this->input->post();
        $result = $this->Model_auth_rule->where($data['id'])->save($data);
        if ($result) {
            $this->success('修改成功', 'admin/rule/index');
        } else {
            $this->error('修改失败');
        }
    }

    /**
     * 删除权限
     */
    public function delete($id)
    {
        $result = $this->Model_auth_rule->deleteData($id);

        if ($result) {
            $this->success('删除成功', 'admin/rule/index');
        } else {
            $this->error('请先删除子权限');
        }

    }

    /*******************用户组**********************/
    /**
     * 用户组列表
     */
    public function group()
    {
        $data = $this->Model_auth_group->get();

        $this->load->view('admin/rule/group', array('data' => $data));
    }

    /**
     * 添加用户组
     */
    public function add_group()
    {
        $data = $this->input->post();

        if (!$data['title']) {
            $this->error('填写名称');
        }

        $count = $this->Model_auth_group->where(array('title' => $data['title']))->count();

        if ($count) {
            $this->error('名称不可重复');
        }

        $result = $this->Model_auth_group->add($data);
        if ($result) {
            $this->success('添加成功', 'admin/rule/group');
        } else {
            $this->error('添加失败');
        }
    }

    /**
     * 修改用户组
     */
    public function edit_group()
    {
        $data = $this->input->post();

        if (!$data['title']) {
            $this->error('填写名称');
        }

        $count = $this->Model_auth_group->where(array('title' => $data['title']))->count();

        if ($count) {
            $this->error('名称不可重复');
        }

        $result = $this->Model_auth_group->where($data['id'])->save($data);
        if ($result) {
            $this->success('修改成功', 'admin/rule/group');
        } else {
            $this->error('修改失败');
        }
    }

    /**
     * 删除用户组
     */
    public function delete_group($id)
    {
        if ($id == 1) {
            $this->error('不可删除超级管理员');
        }

        $status = $this->Model_auth_group->delete($id);
        // 用户组还未增加成员时，返回0，暂时这样处理
        $this->Model_auth_group_access->where(array('group_id' => $id))->delete();
        if ($status) {
            $this->success('删除成功', 'admin/rule/group');
        } else {
            $this->error('删除失败');
        }
    }

    /*****************权限-用户组*****************/
    /**
     * 分配权限
     */
    public function rule_group($id)
    {
        if (IS_POST) {
            $data = $this->input->post();
            $data['rules'] = implode(',', $data['rule_ids']);
            unset($data['rule_ids']);
            $result = $this->Model_auth_group->where($data['id'])->save($data);
            if ($result) {
                $this->success('操作成功', 'admin/rule/group');
            } else {
                $this->error('操作失败');
            }
        } else {
            // 获取用户组数据
            $group_data = $this->Model_auth_group->where($id)->find();
            $group_data['rules'] = explode(',', $group_data['rules']);
            // 获取规则数据
            $rule_data = $this->Model_auth_rule->getTreeData('level', 'id', 'title');

            $data = array(
                'group_data' => $group_data,
                'rule_data' => $rule_data
            );
            $this->load->view('admin/rule/rule_group', $data);
        }
    }

    /******************用户-用户组*******************/
    /**
     * 添加成员
     */
    public function check_user($group_id, $username = '')
    {
        if (IS_POST) {
            $username = $this->input->post('username');
        }
        $query = $this->Model_auth_group->where($group_id)->find();
        $group_name = $query['title'];
        $uids = $this->Model_auth_group_access->getUidsByGroupId($group_id);

        // 判断用户名是否为空
        if (empty($username)) {
            $user_data = [];
        } else {
            $user_data = $this->Model_users->where(array('username' => $username))->get();
        }

        $data = array(
            'group_name' => $group_name,
            'uids' => $uids,
            'user_data' => $user_data,
            'group_id' => $group_id,
            'username' => $username
        );

        $this->load->view('admin/rule/check_user', $data);
    }

    /**
     * 添加用户到用户组
     */
    public function add_user_to_group($uid, $group_id, $username)
    {
        $map = array(
            'uid' => $uid,
            'group_id' => $group_id
        );
        $count = $this->Model_auth_group_access->where($map)->count();
        if ($count == 0) {
            $this->Model_auth_group_access->add($map);
        }
        $this->success('操作成功', 'admin/rule/check_user/' . $group_id . '/' . $username);
    }

    /**
     * 将用户移除用户组
     */
    public function delete_user_from_group()
    {
        $map = $this->input->get();
        $result = $this->Model_auth_group_access->delete($map);

        if ($result) {
            $this->success('操作成功', 'admin/rule/admin_user_list');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 管理员列表
     */
    public function admin_user_list()
    {
        $data = $this->Model_auth_group_access->getAllData();
        $this->load->view('admin/rule/admin_user_list', array('data' => $data));
    }

    /**
     * 添加管理员
     */
    public function add_admin()
    {
        if (IS_POST) {
            $data = $this->input->post();

            if (!empty($data['username'])) {
                $count = $this->Model_users->where(array('username' => $data['username']))->count();
                if ($count) {
                    $this->error('管理员名称不可重复');
                }
            } else {
                $this->error('管理员名称不可为空');
            }

            if (empty($data['password1']) && empty($data['password2'])) {
                $this->error('密码不能为空');
            }
            if ($data['password1'] != $data['password2']) {
                $this->error('密码不一致');
            }
            $data['password'] = $this->makePassword($data['password1']);

            unset($data['password1']);
            unset($data['password2']);
            $map = $data;
            if (isset($map['group_ids'])) {
                unset($map['group_ids']);
            }
            $result = $this->Model_users->add($map);
            if ($result) {
                if (!empty($data['group_ids'])) {
                    foreach ($data['group_ids'] as $k => $v) {
                        $group = array(
                            'uid' => $result,
                            'group_id' => $v
                        );
                        $this->Model_auth_group_access->add($group);
                    }
                }
                // 操作成功
                $this->success('添加成功', 'admin/rule/admin_user_list');
            } else {
                $this->error('添加失败');
            }
        } else {
            $data = $this->Model_auth_group->get();
            $this->load->view('admin/rule/add_admin', array('data' => $data));
        }
    }

    /**
     * 修改管理员
     */
    public function edit_admin($id)
    {
        if (IS_POST) {
            $data = $this->input->post();
            // 组合where数组条件
            $uid = $data['id'];

            // 修改权限
            $this->Model_auth_group_access->delete(array('uid' => $uid));
            if (isset($data['group_ids'])) {
                foreach ($data['group_ids'] as $k => $v) {
                    $group = array(
                        'uid' => $uid,
                        'group_id' => $v
                    );
                    $this->Model_auth_group_access->add($group);
                }
            }

            $data = array_filter($data);

            if (!empty($data['password1']) && !empty($data['password2'])) {
                if ($data['password1'] == $data['password2']) {
                    $data['password'] = password_hash($data['password1'], PASSWORD_DEFAULT);
                    unset($data['password1']);
                    unset($data['password2']);
                } else {
                    $this->error('密码不一致');
                }
            }

            if (isset($data['group_ids'])) {
                unset($data['group_ids']);
            }

            $this->Model_users->where($uid)->save($data);
            $this->success('编辑成功', 'admin/rule/admin_user_list');
        } else {
            // 获取用户数据
            $user_data = $this->Model_users->where($id)->find();
            // 获取已加入用户组
            $query = $this->Model_auth_group_access->where(array('uid' => $id))->get();

            $group_data = [];
            foreach ($query as $q) {
                $group_data[] = $q['group_id'];
            }
            $group_data = implode(",", $group_data);

            // 全部用户组
            $data = $this->Model_auth_group->get();
            $data = array(
                'data' => $data,
                'user_data' => $user_data,
                'group_data' => $group_data
            );
            $this->load->view('admin/rule/edit_admin', $data);
        }
    }

    /**
     * 删除用户
     */
    public function delete_admin($id)
    {
        if ($id == 1) {
            $this->error('超级用户不可删除');
        }

        // 删除用户
        $this->Model_users->delete($id);
        // 删除用户对应的权限组
        $this->Model_auth_group_access->delete(array('uid' => $id));

        $this->success('删除成功');
    }
}