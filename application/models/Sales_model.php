<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Sales_model extends CI_Model {
    /*
     * Infor ERP LN Software Architecture
     * http://www.baanboard.com/node/42
     * tc	Common	master data
     */

    /**
     * ERP Database table information
     * @table_businessPartners string ERP Business Partners Table
     * @meta_table_bp string Business Partners Meta
     * bpid     Business Partners ID
     * nama     BP Name
     * bprl     Role
     * prst     Status
     * btbv     To be verified
     * prbp     Parent BP
     */
    protected $table_businessPartners = 'ttccom100110';
    protected $meta_table_bp = 'Business partners meta';
    protected $table_bp_details = 'ttccom130110';
    protected $meta_table_bp_details = 'Business partners detail meta';
    protected $table_salesOrders = 'ttdsls400110';

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('erplnDB', TRUE);
    }

    public function get_partners() {
        $this->db->set_dbprefix('dbo_');
        $this->db->select("SELECT ttccom100110.t_bpid AS id, ttccom100110.t_nama AS customer, ttccom130110.t_namc AS address, ttccom130110.t_pstc AS zipcode, ttccom130110.t_ccit AS city, ttccom130110.t_ccty AS country, ttccom130110.t_telp AS telephone,
        CASE (ttccom100110.t_bprl)
        WHEN 1 THEN 'unknown'
        WHEN 2 THEN 'customer'
        WHEN 3 THEN 'supplier'
        WHEN 4 THEN 'customer and supplier'
        END AS role,
        CASE (ttccom100110.t_prst)
        WHEN 1 THEN 'inactive'
        WHEN 2 THEN 'active'
        END AS status FROM ttccom100110, ttccom130110 WHERE  ttccom100110.t_cadr = ttccom130110.t_cadr");
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function get_partner($id) {
        $this->db->where('t_bpid', $id);
        $query = $this->db->get($this->table_businessPartners);
        $result = $query->result();
        return $result;
    }

}
