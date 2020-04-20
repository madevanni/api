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
        $id = $this->get('id');
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
    public function models_get()
    {
        $id = $this->get('id');
        if ($id == '') {
            $models = $this->Sales_model->get_models();
        } else {
            $models = $this->Sales_model->get_model($id);
        }

        if ($models) {
            $this->response([
                'status' => true,
                'data' => $models
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Models not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function models_post()
    {
        $data = [
            'name' => $this->post('name'),
            'created_by' => 1,
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
            'name' => $this->put('name'),
            'modified_by' => 1,
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
            'deleted_by' => 1,
            'deleted_on' => date('Y-m-d H:i:s')
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
        if ($id == '') {
            $forecasts = $this->Sales_model->get_forecast();
        } else {
            $forecasts = $this->Sales_model->get_forecast($id);
        }

        if ($forecasts) {
            $this->response([
                'status' => true,
                'data' => $forecasts
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Models not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function forecasts_post()
    {
        $data = [
            'model_id' => $this->post('model_id'),
            'psi_part_id' => $this->post('psi_part_id'),
            'cust_id' => $this->post('cust_id'),
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
            'model_id' => $this->post('model_id'),
            'psi_part_id' => $this->post('psi_part_id'),
            'cust_id' => $this->post('cust_id'),
            'sales_qty' => $this->post('sales_qty'),
            'fy' => $this->post('fy'),
            'period' => $this->post('period'),
            'modified_by' => 1,
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
        $data = [
            'deleted' => 1,
            'deleted_by' => 1,
            'deleted_on' => date('Y-m-d H:i:s')
        ];
        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'Provide an id to delete.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if ($this->Sales_model->delete_forecasts($id) > 0) {
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
}
