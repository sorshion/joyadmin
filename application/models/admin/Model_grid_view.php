<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_grid_view extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'grid_view';
    }
}