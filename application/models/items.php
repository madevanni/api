<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Item_model extends CI_model {
    
    /*
     * Infor ERP LN Software Architecture
     * http://www.baanboard.com/node/42
     * tc	Common	master data
     */
    
    protected $table_items = 'tcibd001';
    protected $table_items_meta = 'Items general data';
}

?>