<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Search extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'search';
    }

    public function updateSearch()
    {
        $searcher = $this->redisclient->keys('SEARCHRECORD:*');

        foreach ($searcher as $search) {
            // 判断key是否已存在
            $exist = $this->Model_schema->is_key_exist($search);
            if (empty($exist)) {
                $type = $this->redisclient->type($search);
                if ($type == 'list') {
                    $len = $this->redisclient->llen($search);
                    $exist_len = 0;
                } else {
                    continue;
                }
            } else {
                if (isset($exist['redis_type']) && $exist['redis_type'] == 'list') {
                    // 判断类型，若是list,查看长度是否发生变化
                    $len = $this->redisclient->llen($search);
                    $exist_len = $exist['list_len'];
                    if ($len <= $exist_len) {
                        continue;
                    }
                } else {
                    continue;
                }
            }

            $arr = [
                'redis_key' => $search,
                'redis_type' => 'list',
                'list_len'  => $len,
                'update_time' => date('Y-m-d H:i:s')
            ];

            //插入新的一条记录
            $this->Model_schema->add($arr);

            $key_arr = explode(":", $search);
            $arr2 = [
                'search_key'    => $search,
                'search_date'   => $key_arr[1],
            ];

            $count = ceil(($len - $exist_len) / 100);

            for ($i = 0; $i < $count; $i++) {
                $res = $this->redisclient->lrange($search, $exist_len + 100 * $i, $exist_len - 1 + 100 * ($i + 1));

                foreach ($res as $k => $r) {
                    $val = json_decode($r, true);

                    $val['search_uuid'] = $search.':'.($exist_len + $k);

                    $this->add(array_merge($arr2, $val));
                }
            }
        }

        return true;
    }

}