<?php 

defined('BASEPATH') || exit('No direct script access allowed');

class Finance_model extends CI_Model {
    /**
     * Infor ERP LN Software Architecture
     * http://www.baanboard.com/node/42
     * tf	Finance	master data
     */

     /**
     * ERP Database table information
     * @table_fiscal string ERP Fiscal Table
     * @meta_table_fiscalDesc string ERP Fiscal meta
     * @meta_table_periodDesc string ERP Period meta
     * ptyp     Period type (ttfgld007)
     * year     Fiscal year (ttfgld07)
     * prno     Period (ttfgld006)
     * ydsc     Fiscal year description (ttfgld006)
     * desc     Period month description (ttfgld005)
     */
    function __construct()
    {
        parent::__construct();
        $this->bonfire = $this->load->database('bonfire', TRUE);
        $this->erplndb = $this->load->database('erplnDB', TRUE);
    }

     public function fiscal_get()
     {
         
     }
}
