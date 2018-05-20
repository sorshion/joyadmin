<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_nav_content extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'nav_content';
    }
}