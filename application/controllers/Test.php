<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Sales_model');
    }
    
    public function index($page = 'test')
    {
        $offset = 10;
        $limit = 5;
        $data['offset'] = $offset;
        $data['limit'] = $limit;
        $data['models'] = json_encode($this->Sales_model->get_models($offset, $limit), true);
        $this->load->view('pages/'.$page, $data);
    }
}