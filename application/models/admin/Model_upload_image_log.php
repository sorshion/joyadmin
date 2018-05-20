<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_upload_image_log extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'upload_image_log';
    }
}