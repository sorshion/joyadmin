<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_terminalinfo extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'terminalinfo';
    }

    public function updateTerminalinfo()
    {
        $infos = $this->redisclient->keys('TERMINALINFO:*');

        foreach ($infos as $info) {
            // 判断key是否已存在
            $exist = $this->Model_schema->is_key_exist($info);

            if (isset($exist['redis_type']) && $exist['redis_type'] == 'hash') {
                $len = $this->redisclient->hlen($info);
                if ($len == $exist['list_len']) {
                    continue;
                } else {
                    $this->delete(array('info_key' => $exist['redis_key']));
                }
            } else {
                $type = $this->redisclient->type($info);
                if ($type == 'hash') {
                    $len = $this->redisclient->hlen($info);
                } else {
                    continue;
                }
            }

            $arr = [
                'redis_key' => $info,
                'redis_type' => 'hash',
                'list_len'  => $len,
                'update_time' => date('Y-m-d H:i:s')
            ];

            // 插入新的一条记录
            $this->Model_schema->add($arr);

            $key_arr = explode(":", $info);
            $arr2 = [
                'info_key'       => $info,
                'info_date'      => $key_arr[1]
            ];

            $res = $this->redisclient->hgetall($info);

            foreach ($res as $k => $r) {
                if (($k % 2) == 0) {
                    // 偶数为key
                    continue;
                }

                $val = json_decode($r, true);

                $arr3 = [];
                foreach ($val as $k1 => $_v) {
                    if ($k1 == 'like') {
                        $arr3['info_'.$k1] = $_v;
                    } else {
                        if ($_v == null) {
                            $_v = '';
                        }
                        $arr3[$k1] = $_v;
                    }
                }

                $this->add(array_merge($arr2, $arr3));
            }
        }

        return true;
    }

}