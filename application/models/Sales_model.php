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

    public function get_partners($offset = NULL, $limit = NULL) {
        $this->erplndb->set_dbprefix('dbo_');
        
        $partners['status'] = true;
        $partners['total'] = strval($this->erplndb->query(
            "SELECT * FROM ttccom100111, ttccom130111 
            WHERE ttccom100111.t_cadr = ttccom130111.t_cadr AND ttccom100111.t_prst = 2")->num_rows());
        $partners['limit'] = $limit;
        $partners['offset'] = $offset;

        $query = $this->erplndb->query("SELECT ttccom100111.t_bpid AS id, ttccom100111.t_nama AS partners, ttccom130111.t_namc AS address, ttccom130111.t_pstc AS zipcode, ttccom130111.t_ccit AS city, ttccom130111.t_ccty AS country, ttccom130111.t_telp AS telephone, ttccom100111.t_ccur AS currency,
        CASE (ttccom100111.t_bprl)
        WHEN 1 THEN 'unknown'
        WHEN 2 THEN 'customer'
        WHEN 3 THEN 'supplier'
        WHEN 4 THEN 'customer and supplier'
        END AS role,
        CASE (ttccom100111.t_prst)
        WHEN 1 THEN 'inactive'
        WHEN 2 THEN 'active'
        END AS status FROM ttccom100111, ttccom130111 WHERE ttccom100111.t_cadr = ttccom130111.t_cadr AND ttccom100111.t_prst = 2");

        $partners['rows'] = $query->result();
        return $partners;
    }

    public function get_partner($id) {
        $this->erplndb->set_dbprefix('dbo_');
        $this->erplndb->protect_identifiers($id);
        $query = $this->erplndb->query("SELECT ttccom100111.t_nama AS partners, ttccom130111.t_namc AS address, ttccom130111.t_pstc AS zipcode, ttccom130111.t_ccit AS city, ttccom130111.t_ccty AS country, ttccom130111.t_telp AS telephone, ttccom100111.t_ccur AS currency,
        CASE (ttccom100111.t_bprl)
        WHEN 1 THEN 'unknown'
        WHEN 2 THEN 'customer'
        WHEN 3 THEN 'supplier'
        WHEN 4 THEN 'customer and supplier'
        END AS role,
        CASE (ttccom100111.t_prst)
        WHEN 1 THEN 'inactive'
        WHEN 2 THEN 'active'
        END AS status FROM ttccom100111, ttccom130111 WHERE ttccom100111.t_cadr = ttccom130111.t_cadr AND ttccom100111.t_bpid = '$id'");
        return $query->result();
    }

    public function get_models($offset = NULL, $limit = NULL) {
        $this->bonfire->set_dbprefix('bf_');
        // $this->bonfire->where('deleted', '!=1'); // for users without deleted records
        $models['status'] = true;
        $models['total'] = strval($this->bonfire->get('models')->num_rows());
        $this->bonfire->select("models.id, models.desc, users.username, models.deleted, models.created_on, models.modified_on");
        $this->bonfire->from('models');
        $this->bonfire->join('users', 'users.id = models.created_by');
        $this->bonfire->limit($limit, $offset);
        $models['rows'] = $this->bonfire->get()->result_array();
        return $models;
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
    
    public function get_forecast($id)
    {
        if ($id == '') {
            $this->bonfire->get('forecast');
            return $this->bonfire->affected_rows();
        } else {
            $this->bonfire->where('id', $id);
            $query = $this->bonfire->get('forecast');
            return $query->result();
        }
    }
    
    public function create_forecasts($data)
    {
        $this->bonfire->insert('forecast', $data);
        return $this->bonfire->affected_rows();
    }
    
    public function update_forecasts($data, $id)
    {
        $this->bonfire->update('forecast', $data, ['id' => $id]);
        return $this->bonfire->affected_rows();
    }

    public function delete_forecasts($id)
    {
        $this->bonfire->delete('forecast', ['id' => $id]);
        return $this->bonfire->affected_rows();
    }

}
