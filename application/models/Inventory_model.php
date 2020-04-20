<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Inventory_model extends CI_model {
    function __construct()
    {
        parent::__construct();
        $this->bonfire = $this->load->database('bonfire', TRUE);
        $this->erplndb = $this->load->database('erplnDB', TRUE);
    }
    
    public function get_inventoryAll()
    {
        $this->erplndb->set_dbprefix('dbo_');
        $query = $this->erplndb->query("SELECT TOP 100 REPLACE(t_item, ' ', '') AS item, t_stoc AS stock, t_blck AS blocked, t_ordr AS on_order, t_allo AS allocated FROM ttcibd100111");
        return $query->result();
    }
    
    public function get_inventoryItem($id)
    {
        $this->erplndb->set_dbprefix('dbo_');
        $query = $this->erplndb->query("SELECT REPLACE(t_item, ' ', '') AS item, t_cwar AS warehouse, t_qhnd AS inventory_on_hand, t_qblk AS inventory_blocked, t_qall AS inventory_allocated, t_qord AS inventory_on_order FROM twhwmd215111 WHERE t_item LIKE '%$id'");
        return $query->result();
    }

    public function get_inventoryWH($warehouse)
    {
        $this->erplndb->set_dbprefix('dbo_');
        $query = $this->erplndb->query("SELECT TOP 100 REPLACE(t_item, ' ', '') AS item, t_cwar AS warehouse, t_qhnd AS inventory_on_hand, t_qblk AS inventory_blocked, t_qall AS inventory_allocated, t_qord AS inventory_on_order FROM twhwmd215111 WHERE t_cwar = '$warehouse'");
        return $query->result();
    }
}
?>