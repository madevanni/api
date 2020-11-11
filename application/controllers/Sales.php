<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Sales extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Sales_model');

        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Business partners API
     */
    public function partners_get()
    {
        $id = $this->get('bp_id');
        if ($id == '') {
            $partners = $this->Sales_model->get_partners();
        } else {
            $partners = $this->Sales_model->get_partner($id);
        }

        if ($partners) {
            $this->response($partners, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Business partners not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function partnersDesc_get()
    {
        $id = $this->get('bp_id');
        $partners = $this->Sales_model->get_name($id);

        if ($partners) {
            $this->response($partners, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Business partners not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function partners_post()
    {
        $limit = $this->post('limit');
        $offset = $this->post('offset');
        $id = $this->get('t_bpid');
        if ($id == '') {
            $partners = $this->Sales_model->get_partners();
        } else {
            $partners = $this->Sales_model->get_partner($id);
        }
        $this->response([
            'status' => true,
            'data' => $partners
        ], REST_Controller::HTTP_OK);
    }

    /**
     * Sales Models API
     */
    // Model
    public function models_get()
    {
        $id = $this->get('id');
        $limit = $this->get('limit');
        $offset = $this->get('offset');
        if ($id == '') {
            $models = $this->Sales_model->get_models($offset, $limit);
        } else {
            $models = $this->Sales_model->get_model($id);
        }

        if ($models) {
            $this->response($models, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'model not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function modelsCountAll_get()
    {
        $countAll = $this->Sales_model->get_modelsCountAll();
        if ($countAll) {
            $this->response($countAll, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No records found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function models_post()
    {
        $data = [
            'desc' => $this->post('name'),
            'created_by' => $this->post('created_by'),
            'created_on' => date('Y-m-d H:i:s')
        ];

        if ($this->Sales_model->create_model($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'New model created.'
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed to create new model!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function models_put()
    {
        $id = $this->put('id');
        $data = [
            'desc' => $this->put('name'),
            'modified_by' => $this->put('modified_by'),
            'modified_on' => date('Y-m-d H:i:s')
        ];

        if ($this->Sales_model->update_model($data, $id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'Model has been updated.'
            ], REST_Controller::HTTP_ACCEPTED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed to modified the model!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function models_delete()
    {
        $id = $this->delete('id');
        $data = [
            'deleted' => 1,
            'deleted_by' => $this->delete('deleted_by')
        ];
        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'Provide an id to delete.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if ($this->Sales_model->delete_model($data, $id) > 0) {
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'Model has been deleted.'
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'ID Model not found.'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
    /**
     * SALES Model API End
     */

    /**
     * Sales Forecast API
     */
    public function forecasts_get()
    {
        $id = $this->get('id');
        $limit = $this->get('limit');
        $offset = $this->get('offset');
        if ($id == '') {
            $forecasts = $this->Sales_model->get_forecasts($offset, $limit);
        } else {
            $forecasts = $this->Sales_model->get_forecast($id);
        }

        if ($forecasts) {
            $this->response($forecasts, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Forecast not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function forecastsCountAll_get()
    {
        $countAll = $this->Sales_model->get_forecastsCountAll();

        if ($countAll) {
            $this->response($countAll, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No records found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function forecasts_post()
    {
        $data = [
            'model_id' => $this->post('model_id'),
            'item_id' => $this->post('item_id'),
            'bp_id' => $this->post('bp_id'),
            'sales_qty' => $this->post('sales_qty'),
            'cust_part' => $this->post('cust_part'),
            'fy' => $this->post('fy'),
            'period' => $this->post('period'),
            'sales_qty' => $this->post('sales_qty'),
            'created_by' => 1,
            'created_on' => date('Y-m-d H:i:s')
        ];

        if ($this->Sales_model->create_forecasts($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'New forecast created.'
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed to create new forecast!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function forecasts_put()
    {
        $id = $this->put('id');
        $data = [
            'model_id' => $this->put('model_id'),
            'item_id' => $this->put('item_id'),
            'bp_id' => $this->put('bp_id'),
            'sales_qty' => $this->put('sales_qty'),
            'fy' => $this->put('fy'),
            'period' => $this->put('period'),
            'modified_by' => $this->put('user_id'),
            'modified_on' => date('Y-m-d H:i:s')
        ];

        if ($this->Sales_model->update_forecasts($data, $id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'Forecasts has been updated.'
            ], REST_Controller::HTTP_ACCEPTED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed to modified the forecasts!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function forecasts_delete()
    {
        $id = $this->delete('id');
        $user_id = $this->delete('user_id');
        $data = [
            'deleted' => 1,
            'deleted_by' => $user_id,
            'deleted_on' => date('Y-m-d H:i:s')
        ];
        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'Provide an id to delete.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if ($this->Sales_model->delete_forecasts($id, $user_id) > 0) {
                $this->response([
                    'status' => true,
                    'id' => $id,
                    'message' => 'Forecasts has been deleted.'
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'ID forecasts not found.'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
    /**
     * SALES Model API End
     */

    /**
     * Sales Forecast API
     */
    public function stock_get()
    {
        $id = $this->get('id');
        $limit = $this->get('limit');
        $offset = $this->get('offset');
        if ($id == '') {
            $stocks = $this->Sales_model->get_stocks($offset, $limit);
        } else {
            $stocks = $this->Sales_model->get_stock($id);
        }

        if ($stocks) {
            $this->response($stocks, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Stock not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }


    
}
