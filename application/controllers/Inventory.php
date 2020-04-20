<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Inventory extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Inventory_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function stock_get()
    {
        $id = $this->get('id');
        $warehouse = $this->get('warehouse');
        if ($id == '' && $warehouse == '') {
            $stock = $this->Inventory_model->get_inventoryAll();
        } else {
            if ($warehouse <> '') {
                $stock = $this->Inventory_model->get_inventoryWH($warehouse);
            } elseif ($id <> '') {
                $stock = $this->Inventory_model->get_inventoryItem($id);
            }
        }

        if ($stock) {
            $this->response([
                'status' => true,
                'data' => $stock
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Stock not found, please try again!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}

?>