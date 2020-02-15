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
    function __construct() {
        parent::__construct();
        $this->bonfire = $this->load->database('bonfire', TRUE);
        $this->erplndb = $this->load->database('erplnDB', TRUE);
    }

    public function get_partners() {
        $this->erplndb->set_dbprefix('dbo_');
        $query = $this->erplndb->query("SELECT ttccom100110.t_bpid AS id, ttccom100110.t_nama AS partners, ttccom130110.t_namc AS address, ttccom130110.t_pstc AS zipcode, ttccom130110.t_ccit AS city, ttccom130110.t_ccty AS country, ttccom130110.t_telp AS telephone, ttccom100110.t_ccur AS currency,
        CASE (ttccom100110.t_bprl)
        WHEN 1 THEN 'unknown'
        WHEN 2 THEN 'customer'
        WHEN 3 THEN 'supplier'
        WHEN 4 THEN 'customer and supplier'
        END AS role,
        CASE (ttccom100110.t_prst)
        WHEN 1 THEN 'inactive'
        WHEN 2 THEN 'active'
        END AS status FROM ttccom100110, ttccom130110 WHERE ttccom100110.t_cadr = ttccom130110.t_cadr");
        return $query->result();
    }

    public function get_partner($id) {
        $this->erplndb->set_dbprefix('dbo_');
        $this->erplndb->protect_identifiers($id);
        $query = $this->erplndb->query("SELECT ttccom100110.t_nama AS partners, ttccom130110.t_namc AS address, ttccom130110.t_pstc AS zipcode, ttccom130110.t_ccit AS city, ttccom130110.t_ccty AS country, ttccom130110.t_telp AS telephone, ttccom100110.t_ccur AS currency,
        CASE (ttccom100110.t_bprl)
        WHEN 1 THEN 'unknown'
        WHEN 2 THEN 'customer'
        WHEN 3 THEN 'supplier'
        WHEN 4 THEN 'customer and supplier'
        END AS role,
        CASE (ttccom100110.t_prst)
        WHEN 1 THEN 'inactive'
        WHEN 2 THEN 'active'
        END AS status FROM ttccom100110, ttccom130110 WHERE ttccom100110.t_cadr = ttccom130110.t_cadr AND ttccom100110.t_bpid = '$id'");
        return $query->result();
    }

    public function get_models() {
        // $this->bonfire->set_dbprefix('bf_');
        // $this->bonfire->select("id, name model, created_by, created_on, modified_on");
        // $query = $this->bonfire->get('models');
        $query = $this->bonfire->query("SELECT bf_models.id, bf_models.name model, bf_users.username created_by, bf_models.created_on, bf_models.modified_on, bf_users.username FROM bf_models, bf_users WHERE bf_models.created_by=bf_users.id");
        return $query->result();
    }

    public function get_model($id) {
        $this->bonfire->where('id', $id);
        $query = $this->bonfire->get('models');
        return $query->result();
    }

    public function create_model($data)
    {
        $this->bonfire->insert('models', $data);
        return $this->bonfire->affected_rows();
    }

    public function update_model($data, $id)
    {
        $this->bonfire->update('models', $data, ['id' => $id]);
        return $this->bonfire->affected_rows();
    }

    public function delete_model($data, $id)
    {
        $this->bonfire->update('models', $data, ['id' => $id]);
        return $this->bonfire->affected_rows();
    }

}
