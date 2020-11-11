<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Planning_model extends CI_Model
{
    /** @var string The name of the db table this model primarily uses. */
    protected $forecast_tableName = 'forecast';
    protected $models_tableName = 'models';
    protected $production_tableName = 'production';
    protected $inventory_tableName = 'inventory';
    
    function __construct()
    {
        $this->bonfire = $this->load->database('bonfire', TRUE);
        $this->erplndb = $this->load->database('erplnDB', TRUE);

        $this->load->model('Sales_model');
    }
}
