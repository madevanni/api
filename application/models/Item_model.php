<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Item_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->bonfire = $this->load->database('bonfire', TRUE);
        $this->erplndb = $this->load->database('erplnDB', TRUE);
    }

    /**
     * get_items
     * only get Manufacture items only (1:Purchased, 2:Manufacture, 4:Cost, 10:List)
     * 
     * @since 0.0.2
     * 
     * @link pagination SQL Server 2008 R2 pagination reference http://zawaruddin.blogspot.com/2012/12/codeigniter-sql-server-pagination.html
     * 
     * @param bool $status true - if returning value /false - no value returned
     * @param int $total return total rows of items
     * @param array $rows return data items in array format
     * @param int $offset pagination offset variable
     * @param int $limit paginanation per page limit
     *
     * @return void
     */
    public function get_items($offset = NULL, $limit = NULL)
    {
        $this->erplndb->set_dbprefix('dbo_');

        $items['status'] = true;

        $items['total'] = strval($this->erplndb->query(
            "SELECT * FROM ttcibd001111 
            INNER JOIN ttcmcs023111 ON ttcibd001111.t_citg = ttcmcs023111.t_citg")->num_rows());
        $items['limit'] = $limit;
        $items['offset'] = $offset;

        $query = $this->erplndb->query("SELECT REPLACE(ttcibd001111.t_item, ' ', '') AS id, ttcibd001111.t_dsca AS description, 
        CASE (ttcibd001111.t_kitm)
        WHEN 1 THEN 'Purchased'
        WHEN 2 THEN 'Manufactured'
        WHEN 3 THEN ''
        WHEN 4 THEN 'Cost'
        WHEN 10 THEN 'List'
        END AS item_type, ttcibd001111.t_seak AS search_key, ttcibd001111.t_citg AS item_group, ttcmcs023111.t_dsca AS item_group_desc, ttcibd001111.t_cuni AS unit 
        FROM ttcibd001111, ttcmcs023111
        WHERE ttcibd001111.t_citg = ttcmcs023111.t_citg AND ttcmcs023111.t_dsca <> '#DELETE#'");
        $items['rows'] = $query->result();
        return $items;
    }

    public function get_item($id)
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
        WHERE ttcibd001111.t_citg = ttcmcs023111.t_citg AND ttcibd001111.t_item LIKE '%$id'");
        return $query->result();
    }

    public function get_stock_all()
    {
        $this->erplndb->set_dbprefix('dbo_');
        $item['total'] = $this->erplndb->query('SELECT COUNT (*) FROM ttcibd100111')->result();
        $query = $this->erplndb->query("SELECT REPLACE(ttcibd100111.t_item, ' ','') AS item, ttcibd100111.t_stoc AS stock, ttcibd100111.t_ltdt AS last_transaction FROM ttcibd100111");
        $item['rows'] = $query->result();
        return $item;
    }

    public function get_stock($id)
    {
        $this->erplndb->set_dbprefix('dbo_');
        $this->erplndb->select('t_item, t_stoc, t_ltdt')->from('ttcibd100111')->where('t_item', $id);
        return $this->erplndb->get()->result();
    }

    public function get_bom($id)
    {
        $this->erplndb->set_dbprefix('dbo_');
        $query = $this->erplndb->query("SELECT t_pono AS position, 
        REPLACE(t_sitm, ' ', '') AS item, 
        t_qana AS net_qty, 
        t_scpf AS scrap_percent, 
        t_scpq AS scrap_qty, 
        t_cwar AS warehouse, 
        t_indt AS effective_date, 
        t_exdt AS expired_date 
        FROM ttibom010111 
        WHERE ttibom010111.t_mitm LIKE '%$id'");

        return $query->result();
    }
}
