<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_auth');
        $this->load->model('admin/Model_users');
        $this->load->model('admin/Model_admin_nav');
    }


    /**
    // 自定义三级目录示例，勿删
    $datas  = [
        0 => [
            'title' => '新闻1',
            'url'   => 'admin/news/show',
            'next'  => '',
        ],
        1 => [
            'title' => '新闻2',
            'url'   => '#',
            'next'  => [
                0 => ['title' => '子新闻1','url' => 'admin/news/index','next' => ''],
                1 => [
                    'title' => '子新闻2',
                    'url' => '#',
                    'next' => [
                        0 => ['title' => '三级新闻1', 'url' => 'admin/news/edit/3', 'next' => ''],
                        1 => ['title' => '三级新闻2', 'url' => 'admin/terminalinfo/show', 'next' => ''],
                    ]],
            ]
        ],
    ];
     */
    public function index()
    {
        $nav_data = $this->Model_admin_nav->getTreeData('level','order_number,id');

        foreach ($nav_data as $k => &$_data) {
            if (empty($_data['_data'])) {
                unset($nav_data[$k]);
            }
        }

        $data['sys_data'] = $nav_data;

        $data['datas'] = [];
        $data['rule_status'] = $this->_rule_status;
        $this->load->view('admin/admin/index', $data);
    }

    public function sys_admin()
    {
        $this->load->view('admin/admin/home');
    }
}
