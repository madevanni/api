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
     * API for items details with pagination
     * 
     * @param string $status if returning value /false - no value returned
     * @param int $total return total rows of items
     * @param array $rows return data items in array format
     *
     * @return JSON
     */
    public function details_get()
    {
        $limit = $this->get('limit');
        $offset = $this->get('offset');
        $id = $this->get('id');
        if ($id == '') {
            $items = $this->Item_model->get_items($offset, $limit);
        } else {
            $items = $this->Item_model->get_item($id);
        }

        if ($items) {
            $this->response($items, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Items not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function stock_get()
    {
        $id = $this->get('id');
        if ($id == '') {
            $items = $this->Item_model->get_stock_all();
        } else {
            $items = $this->Item_model->get_stock($id);
        }

        if ($items) {
            $this->response([
                'status' => true,
                'data' => $items
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Items stock not found!'
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
