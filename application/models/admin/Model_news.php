<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_news extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'news';
    }

    public function updateNews()
    {
        $news = $this->redisclient->keys('NEWS:*');

        foreach ($news as $new) {
            // 判断key是否已存在
            $exist = $this->Model_schema->is_key_exist($new);

            if (empty($exist)) {
                $type = $this->redisclient->type($new);
                if ($type == 'list') {
                    $len = $this->redisclient->llen($new);
                    $exist_len = 0;
                } else {
                    continue;
                }
            } else {
                if (isset($exist['redis_type']) && $exist['redis_type'] == 'list') {
                    // 判断类型，若是list,查看长度是否发生变化
                    $len = $this->redisclient->llen($new);
                    $exist_len = $exist['list_len'];
                    if ($len <= $exist_len) {
                        continue;
                    }
                } else {
                    continue;
                }
            }

            $arr = [
                'redis_key'     => $new,
                'redis_type'    => 'list',
                'list_len'      => $len,
                'update_time'   => date('Y-m-d H:i:s')
            ];

            //插入新的一条记录
            $this->Model_schema->add($arr);

            $key_arr = explode(":", $new);
            $arr2 = [
                'new_key'       => $new,
                'new_country'   => $key_arr[1],
                'new_category'  => $key_arr[2],
                'new_date'      => $key_arr[3]
            ];

            $count = ceil(($len - $exist_len) / 100);

            for ($i = 0; $i < $count; $i++) {

                $res = $this->redisclient->lrange($new, $exist_len + 100 * $i, $exist_len - 1 + 100 * ($i + 1));
                foreach ($res as $k => $r) {
                    $val = json_decode($r, true);

                    $arr3 = [];
                    foreach ($val as $k1 => $_v) {
                        $arr3['new_'.$k1] = $_v;
                    }

                    $arr3['new_uuid'] = $new.':'.($exist_len + $k);

                    $this->add(array_merge($arr2, $arr3));
                }

            }

        }

        return true;

    }
}