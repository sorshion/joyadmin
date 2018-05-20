<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    protected $_rule_status = 0;

    protected function init()
    {
        $this->load->helper('url'); // 载入url helper
        $this->load->helper('array_helper');

        $params = [
            'host' => $this->config->item('redis_default')['host'],
            'port' => $this->config->item('redis_default')['port'],
        ];
        $this->load->library('redisclient', $params);// 加载自定义redis
        $this->load->library('data');
        $this->load->library('session');
        $this->load->library('pager');
        $this->load->library('s3');

        $this->load->model('Model_auth');

        $this->auth();
    }

    public function auth()
    {
        $data       = $this->config->item('rule_uri');
        $rule_name  = filter_url_string($this->uri->uri_string());
        $c_name     = $this->uri->rsegment(1);

        $flag = false;
        foreach ($data['float'] as $_d) {
            if ($c_name == $_d){
                $flag = true;
            }
        }

        foreach ($data['nofloat'] as $_d) {
            if ($rule_name == $_d) {
                $flag = true;
            }
        }

        if (!$flag) {
            $result = $this->Model_auth->check($rule_name, isset($_SESSION['user']['id'])?$_SESSION['user']['id']:0);
            $this->_rule_status = $result ? 1 : 0;
        } else {
            $this->_rule_status = 1;
        }
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function error($message = '', $jumpUrl = '', $ajax = false)
    {
        $this->dispatchJump($message, 0, $jumpUrl, $ajax);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @return void
     */
    protected function success($message = '', $jumpUrl = '', $ajax = false)
    {
        $this->dispatchJump($message, 1, $jumpUrl, $ajax);
    }

    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string $message 提示信息
     * @param Boolean $status 状态
     * @param string $jumpUrl 页面跳转地址
     * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @access private
     * @return void
     */
    private function dispatchJump($message, $status = 1, $jumpUrl = '', $ajax = false)
    {
        $assign_array = array();
        if (true === $ajax || $this->input->is_ajax_request()) {
            // AJAX提交
            $data           = is_array($ajax) ? $ajax : array();
            $data['info']   = $message;
            $data['status'] = $status;
            $data['url']    = $jumpUrl;
            $this->ajaxReturn($data);
        }
        if (is_int($ajax)) {
            $assign_array['waitSecond'] = $ajax;
        }

        if (!empty($jumpUrl)) {
            $assign_array['jumpUrl'] = site_url().'/'.ltrim($jumpUrl, '/');
        }

        // 提示标题
        $assign_array['msgTitle'] = $status ? '操作成功！' : '操作失败！';

        // 状态
        $assign_array['status'] = $status;

        if ($status) {
            //发送成功信息
            $assign_array['message'] = $message;// 提示信息
            // 成功操作后默认停留1秒
            if (!isset($assign_array['waitSecond'])) {
                $assign_array['waitSecond'] = 1;
            }

            // 默认操作成功自动返回操作前页面
            if (!isset($assign_array['jumpUrl'])) {
                if (check_login()) {
                    if ($this->_rule_status) {
                        $assign_array['jumpUrl'] = strpos($_SERVER["HTTP_REFERER"], '/admin/admin') ? site_url().'/admin/admin/sys_admin' : $_SERVER["HTTP_REFERER"];
                    } else {
                        $assign_array['jumpUrl'] = strpos($_SERVER["HTTP_REFERER"], '/admin/admin') ? site_url().'/admin/login/home' : $_SERVER["HTTP_REFERER"];
                    }
                } else {
                    $assign_array['jumpUrl'] = site_url().'/admin/login/index';
                }
            }
            $this->load->view('dispatch_jump', $assign_array);
        } else {
            $assign_array['error'] = $message;
            //发生错误时候默认停留3秒
            if (!isset($assign_array['waitSecond'])) {
                $assign_array['waitSecond'] = 1;
            }

            // 默认发生错误的话自动返回上页
            if (!isset($assign_array['jumpUrl'])) {

                if (check_login()) {
                    if ($this->_rule_status) {
                        $assign_array['jumpUrl'] = strpos($_SERVER["HTTP_REFERER"], '/admin/admin') ? site_url().'/admin/admin/sys_admin' : 'javascript:history.back(-1);';
                    } else {
                        if (isset($_SERVER["HTTP_REFERER"])) {
                            $assign_array['jumpUrl'] = strpos($_SERVER["HTTP_REFERER"], '/admin/admin') ? site_url().'/admin/login/home' : $_SERVER["HTTP_REFERER"];
                        } else {
                            $assign_array['jumpUrl'] = site_url().'/admin/login/index';
                        }
                    }
                } else {
                    $assign_array['jumpUrl'] = site_url().'/admin/login/index';
                }
            }

            $this->load->view('dispatch_jump', $assign_array);
            echo $this->output->get_output();
            exit();
        }
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    protected function ajaxReturn($data, $type = 'json', $json_option = 0)
    {
        switch (strtoupper($type)) {
            case 'JSON':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data, $json_option));
            case 'XML':
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler = $this->config->item('json_type') ? $this->config->item('json_type') : 'callback';
                exit($handler . '(' . json_encode($data, $json_option) . ');');
            case 'EVAL':
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default:
                exit($data);
        }
    }

    protected function makePassword($password, $salt = 'aFXBxYmkClsw46y7b8C5qN56zs')
    {
        return md5($password . $salt);
    }
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->_rule_status) {
            $this->error('您没有权限访问');
        }
    }
}

class Login_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/Model_users');
    }
}
