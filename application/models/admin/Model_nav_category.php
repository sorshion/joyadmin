<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_nav_category extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'nav_category';
    }

    public function get_country()
    {
        $sql = 'SELECT abbr,cninfo FROM elk_country';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_select_nav()
    {
        $arrs = $this->select('id,country,en_name')->get();

        $result = [];
        foreach ($arrs as $arr) {
            $result[$arr['country']][] = $arr;
        }
        return $result;
    }
}