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

class Planning extends REST_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('Planning_model');

        date_default_timezone_set('Asia/Jakarta');
    }

    // PSI API - Production Sales Inventory
    public function psi_get()
    {
        $id = $this->get('id');
        $limit = $this->get('limit');
        $offset = $this->get('offset');
        if($id == '') {
            $psi = $this->Sales_model->get_forecasts($offset, $limit);
        } else {
            $psi = $this->Sales_model->get_forecast($id);
        }

        if ($psi) {
            $this->response($psi, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data not found!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
