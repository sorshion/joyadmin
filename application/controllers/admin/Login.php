<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Login_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 登陆
     */
    public function index()
    {
        if (IS_POST) {
            // 做一个简单的登录 组合where数组条件
            $map = $this->input->post();
            $hash_password = $this->makePassword($map['password']);
            $data = $this->Model_users->where(array('username' => $map['username']))->find();
            if ($hash_password != $data['password']) {
               $this->error('账号或密码错误', 'admin/login/logout');
            } else {
                $arr = [
                    'user' => [
                        'id' => $data['id'],
                        'username' => $data['username'],
                        'avatar' => $data['avatar']
                    ],

                ];
                $this->session->set_userdata($arr);
                $this->success('登录成功、前往管理后台', 'admin/admin/index');
            }
        } else {
            if (check_login()) {
                redirect('admin/admin/index');
            } else {
                $this->load->view('admin/admin/login');
            }
        }

    }

    /**
     * 登出
     */
    public function logout()
    {
        $this->session->unset_userdata('user');
        redirect('admin/login/index');
    }

    public function home()
    {
        $this->load->view('admin/admin/home');
    }
}
