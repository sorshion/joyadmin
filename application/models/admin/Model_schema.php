<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_schema extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'schema';
    }

    public function is_key_exist($key)
    {
        $select = $this->where(array("redis_key" => $key))->order_by("update_time DESC")->limit(1)->find();
        if (empty($select)) {
            return array();
        }
        return $select;
    }
}