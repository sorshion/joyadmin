<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_event extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'event';
    }

    public function updateEvent()
    {
        $events = $this->redisclient->keys('EVENTSLOG:*');

        foreach ($events as $event) {
            // 判断key是否已存在
            $exist = $this->Model_schema->is_key_exist($event);
            if (empty($exist)) {
                $type = $this->redisclient->type($event);
                if ($type == 'list') {
                    $len = $this->redisclient->llen($event);
                    $exist_len = 0;
                } else {
                    continue;
                }
            } else {
                if (isset($exist['redis_type']) && $exist['redis_type'] == 'list') {
                    // 判断类型，若是list,查看长度是否发生变化
                    $len = $this->redisclient->llen($event);
                    $exist_len = $exist['list_len'];
                    if ($len <= $exist_len) {
                        continue;
                    }
                } else {
                    continue;
                }
            }


            $arr = [
                'redis_key' => $event,
                'redis_type' => 'list',
                'list_len'  => $len,
                'update_time' => date('Y-m-d H:i:s')
            ];

            //插入新的一条记录
            $this->Model_schema->add($arr);

            $key_arr = explode(":", $event);
            $arr2 = [
                'event_key'    => $event,
                'event_date'   => $key_arr[1],
            ];

            $count = ceil(($len - $exist_len) / 100);

            for ($i = 0; $i < $count; $i++) {
                $res = $this->redisclient->lrange($event, $exist_len + 100 * $i, $exist_len - 1 + 100 * ($i + 1));

                foreach ($res as $k => $r) {
                    $val = json_decode($r, true);

                    $val['event_uuid'] = $event.':'.($exist_len + $k);

                    $this->add(array_merge($arr2, $val));
                }
            }
        }

        return true;
    }

}