<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Nav extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/Model_admin_nav');
    }

    /**
     * 菜单列表
     */
    public function index()
    {
        $data = $this->Model_admin_nav->getTreeData('tree','order_number,id');
        $this->load->view('admin/nav/index', array('data' => $data));
    }

    /**
     * 添加菜单
     */
    public function add()
    {
        $data = $this->input->post();
        $result = $this->Model_admin_nav->add($data);
        if ($result) {
            $this->success('添加成功','admin/nav/index');
        } else {
            $this->error('添加失败');
        }
    }

    /**
     * 修改菜单
     */
    public function edit()
    {
        $data = $this->input->post();
        $result = $this->Model_admin_nav->where($data['id'])->save($data);
        if ($result) {
            $this->success('修改成功','admin/nav/index');
        } else {
            $this->error('修改失败');
        }
    }

    /**
     * 删除菜单
     */
    public function delete($id)
    {
        $result = $this->Model_admin_nav->deleteData($id);
        if ($result) {
            $this->success('删除成功','admin/nav/index');
        } else {
            $this->error('请先删除子菜单');
        }
    }

    /**
     * 菜单排序
     */
    public function order(){
        $data = $this->input->post();
        $result = $this->Model_admin_nav->orderData($data);
        if ($result) {
            $this->success('排序成功', 'admin/nav/index');
        } else {
            $this->error('排序失败');
        }
    }
}