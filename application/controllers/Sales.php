<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

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
class Sales extends REST_Controller {

    function __construct() {
        // Construct the parent class
        parent::__construct();

        $this->load->model('Sales_model');
    }

    public function partners_get() {
        $id = $this->get('id');
        if ($id == '') {
            $partners = $this->Sales_model->get_partners();
        } else {
            $partners = $this->Sales_model->get_partner($id);
        }
        $this->response($partners, 200);
    }

    public function partners_post() {
        $limit = $this->post('limit');
        $offset = $this->post('offset');
        $id = $this->get('t_bpid');
        if ($id == '') {
            $partners = $this->Sales_model->get_partners();
        } else {
            $partners = $this->Sales_model->get_partner($id);
        }
        $this->response($partners, 200);
    }

}
