<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_redis_log extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'redis_log';
    }

    public function add_redis_log($type, $desc = '', $data_str = '')
    {
        if (empty($type)) {
            return false;
        }
        $data['log_type'] = $type;
        $data['log_desc'] = $desc;
        $data['log_data'] = $data_str;
        $data['update_user'] = $_SESSION['user']['username'];
        $data['update_date'] = time();
        return $this->add($data);
    }

}