<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_analysis_search extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'analysis_search';
    }
}