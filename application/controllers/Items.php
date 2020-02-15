<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Items extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Item_model');

        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Items API
     */
    public function details_get()
    {
        $id = $this->get('id');
        if ($id == '') {
            $items = $this->Planning_model->get_items();
        } else {
            $items = $this->Planning_model->get_item($id);
        }

        if ($items) {
            $this->response([
                'status' => true,
                'data' => $items
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Items not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function bom_get()
    {
        $id = $this->get('id');
        if ($id == '') {
            $this->response([
                'status' => false,
                'message' => 'Please provide an item ID!'
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $bom = $this->Item_model->get_bom($id);
        }
        
        if ($bom) {
            $this->response([
                'status' => true,
                'data' => $bom
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Build of material not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
        
    }
    /**
     * Planning Items API end
     */
}
