<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_game_desc extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'game_desc';
    }

    public function updatePlays()
    {
        $plays = $this->redisclient->hgetall('GAMENUMS');

        $flag = 0;
        $temp1 = $temp2 = [];
        foreach ($plays as $key => $_p) {
            if ($key % 2 == 0) {
                $temp1['id'] = explode("_", $_p)[1];
                $temp1['name'] = $_p;
            } else {
                $flag = 1;
            }
            if ($flag) {
                if ($plays < 100000) {
                    $res_p = $this->select('plays')->where($temp1['id'])->find();
                    if (!empty($res_p)) {
                        $temp2['plays'] = $res_p['plays'] + $_p;
                    } else {
                        $flag  = 0;
                        $temp1 = $temp2 = [];
                        continue;
                    }
                } else {
                    $temp2['plays'] = $_p;
                }
                $this->where($temp1['id'])->save($temp2);
                $this->redisclient->hset('GAMENUMS', $temp1['name'], $temp2['plays']);
                $flag = 0;
                $temp1 = $temp2 = [];
            }
        }

        return true;
    }
}