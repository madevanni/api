<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Item_model extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
        $this->bonfire = $this->load->database('bonfire', TRUE);
        $this->erplndb = $this->load->database('erplnDB', TRUE);
    }

    public function get_items()
    {
        $this->erplndb->set_dbprefix('dbo_');
        $query = $this->erplndb->query("SELECT TOP 100 REPLACE(ttcibd001111.t_item, ' ', '') AS id, ttcibd001111.t_dsca AS description, 
        CASE (ttcibd001111.t_kitm)
        WHEN 1 THEN 'Purchased'
        WHEN 2 THEN 'Manufactured'
        WHEN 3 THEN ''
        WHEN 4 THEN 'Cost'
        WHEN 10 THEN 'List'
        END AS item_type, ttcibd001111.t_seak AS search_key, ttcibd001111.t_citg AS item_group, ttcmcs023111.t_dsca AS item_group_desc, ttcibd001111.t_cuni AS unit 
        FROM ttcibd001111, ttcmcs023111
        WHERE ttcibd001111.t_dsca = ttcmcs023111.t_dsca OR ttcmcs023111.t_dsca = '#DELETE#'");
        return $query->result();
    }
    public function get_bom($id)
    {
        $this->erplndb->set_dbprefix('dbo_');
        $query = $this->erplndb->query("SELECT t_pono AS position, REPLACE(t_sitm, ' ', '') AS item, t_qana AS net_qty, t_scpf AS scrap_percent, t_scpq AS scrap_qty, t_cwar AS warehouse, t_indt AS effective_date, t_exdt AS expired_date FROM ttibom010111 WHERE ttibom010111.t_mitm LIKE '%$id%'");
        
        return $query->result();
    }
}

?>