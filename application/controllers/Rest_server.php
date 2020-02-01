<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_server extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->model('Sales_model');
    }

    public function index()
    {
        $this->load->helper('url');
        $data['partners'] = $this->Sales_model->get_partners();
        $this->load->view('rest_server', $data);
    }
}
