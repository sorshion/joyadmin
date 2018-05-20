<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_slider_rule extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'slider_rule';
    }

    // 获取国家信息
    public function countrys($str = '')
    {
        $string = '';
        $sql = 'SELECT abbr,cninfo FROM elk_country';
        $query = $this->db->query($sql);
        $results = $query->result_array();
        if (!empty($results)) {
            foreach ($results as $key => $val) {
                $selected = $str == $val['abbr'] ? ' selected' : '';
                $string .= '<option value="' . $val['abbr'] . '"' . $selected . '>' . $val['cninfo'] . '</option>';
            }
        }
        return $string;
    }
    // 获取国家language
    public function counLanguage($str = '')
    {
        $string = '';
        //$sql = 'SELECT code FROM elk_country group by code';
        //$query = $this->db->query($sql);
        //$results = $query->result_array();
        $results = array(
            array('code'=>'CN', 'name'=>'中文'),
            array('code'=>'ID', 'name'=>'印尼语'),
            array('code'=>'IN', 'name'=>'印度语'),
            array('code'=>'RU', 'name'=>'俄文'),
            array('code'=>'TR', 'name'=>'土耳其语'),
            array('code'=>'US', 'name'=>'英文'),
        );
        if (!empty($results)) {
            foreach ($results as $key => $val) {
                $selected = $str == $val['code'] ? ' selected' : '';
                $string .= '<option value="' . $val['code'] . '"' . $selected . '>' . $val['name'] . '</option>';
            }
        }
        return $string;
    }
    //记录同步时的信息
    public function saveRedisLog($data=array())
    {
        if( !empty($data) || is_array($data) ){
            $value = array(
                'data'          => implode(',', $data),
                'admin_id'      => $_SESSION['user']['id'],
                'admin_user'    => $_SESSION['user']['username'],
                'addtime'       => time(),
            );
            return $this->db->insert('elk_slider_toredis_log', $value);
        }
        return false;
    }
    // 检测运营数据同步后是否一致
    public function checkRedisData()
    {
        $flag = false;
        $data = $this->redisclient->lrange('SLIDERINFO', 0, -1);
        $query = $this->db->query('SELECT * FROM elk_slider_toredis_log ORDER BY addtime DESC');
        $results = $query->row_array();
        if( !empty($data)&&!empty($results) ){
            $md5_arr = explode(',', $results['data']);
            foreach ($data as $key => $val) {
                if( !in_array(md5($val), $md5_arr) ){
                    $flag = false;break;
                }else{
                    $flag = true;
                }
            }
        }
        return $flag;
    }

}