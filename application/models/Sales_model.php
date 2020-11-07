<?php

defined('BASEPATH') || exit('No direct script access allowed');

class Sales_model extends CI_Model
{
    /*
     * Infor ERP LN Software Architecture
     * http://www.baanboard.com/node/42
     * tc	Common	master data
     */

    /** @var string The name of the db table this model primarily uses. */
    protected $forecast_tableName = 'forecast';
    protected $models_tableName = 'models';


    /** @var string The primary key of the table. Used as the 'id' throughout. */
    protected $forecastKey = 'id';

    protected $log_user 	= true;
	protected $set_created	= true;
	protected $set_modified = true;
	protected $soft_deletes	= true;

    protected $created_field     = 'created_on';
    protected $created_by_field  = 'created_by';
	protected $modified_field    = 'modified_on';
    protected $modified_by_field = 'modified_by';
    protected $deleted_field     = 'deleted';
    protected $deleted_by_field  = 'deleted_by';

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
    function __construct()
    {
        parent::__construct();

        $this->bonfire = $this->load->database('bonfire', TRUE);
        $this->erplndb = $this->load->database('erplnDB', TRUE);
    }



    public function get_forecasts($offset = NULL, $limit = NULL)
    {
        $this->bonfire->set_dbprefix('bf_');
        $forecast['total'] = strval($this->bonfire->get($this->forecast_tableName)->num_rows());
        $this->bonfire->select("forecast.*, models.desc");
        $this->bonfire->from($this->forecast_tableName);
        $this->bonfire->join('models', 'models.id = forecast.model_id');
        // $this->bonfire->where('forecast.deleted', '!=1');
        $this->bonfire->limit($limit, $offset);
        $result = $this->bonfire->get()->result_array();

        /**
         * try using cache to combine mysql and SQL server
         */
        // $this->db = $this->load->database('erplnDB', TRUE);
        // $this->db->start_cache();
        // $this->db->select('*');
        // $this->db->stop_cache();
        // $this->db->get('dbo.ttccom100111');

        // $this->db = $this->load->database('bonfire', TRUE);
        // $this->db->select('bf_forecast.*, bf_models.desc');
        // $this->db->from('bf_forecast');
        // $this->db->join('dbo.ttccom100111', 'dbo.ttccom100111.t_bpid = bf_forecast.bp_id');
        // $this->db->join('bf_models', 'bf_models.id = bf_forecast.model_id');
        // $this->db->where('bf_forecast.deleted', '!=1');
        // $this->db->limit($limit, $offset);
        // $result = $this->db->get()->result_array();
        return $result;
    }
    
    public function get_forecast($id)
    {
        $this->bonfire->set_dbprefix('bf_');
        $this->bonfire->select("forecast.*, models.desc");
        $this->bonfire->from($this->forecast_tableName);
        $this->bonfire->join('models', 'models.id = forecast.model_id');
        $this->bonfire->where('forecast.id', $id);
        $result = $this->bonfire->get()->row();
        return $result;
    }

    public function get_forecastsCountAll()
    {
        $this->bonfire->where('deleted', '!=1');
        $this->bonfire->from($this->forecast_tableName);
        return $this->bonfire->count_all_results();
    }

    public function get_stocks($offset = NULL, $limit = NULL)
    {
        $this->erplndb->set_dbprefix('dbo_');

        $stock['status'] = true;
        $stock['total'] = strval($this->erplndb->query("SELECT * FROM ttcibd100111")->num_rows());
        $stock['limit'] = $limit;
        $stock['offset'] = $offset;

        $query = $this->erplndb->query("SELECT * FROM ttcibd100111");
        $stock['rows'] = $query->result();
        return $stock;
    }

    public function get_partners($offset = NULL, $limit = NULL)
    {
        $this->erplndb->set_dbprefix('dbo_');

        $partners['status'] = true;
        $partners['total'] = strval($this->erplndb->query(
            "SELECT * FROM ttccom100111, ttccom130111 
            WHERE ttccom100111.t_cadr = ttccom130111.t_cadr AND ttccom100111.t_prst = 2"
        )->num_rows());
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

    public function get_partner($id)
    {
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

    public function get_models($offset = NULL, $limit = NULL)
    {
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
    public function get_modelsCountAll()
    {
        $this->bonfire->where('deleted', '!=1');
        $this->bonfire->from($this->models_tableName);
        return $this->bonfire->count_all_results();
    }

    public function get_model($id)
    {
        $this->bonfire->where('id', $id);
        $query = $this->bonfire->get('models');
        return $query->row();
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

    public function create_forecasts($data)
    {
        $this->bonfire->insert($this->forecast_tableName, $data);
        return $this->bonfire->affected_rows();
    }

    public function update_forecasts($data, $id)
    {
        $this->bonfire->update($this->forecast_tableName, $data, ['id' => $id]);
        return $this->bonfire->affected_rows();
    }

    public function delete_forecasts($id, $user_id)
    {
        // $this->bonfire->delete($this->forecast_tableName, ['id' => $id]);
        // return $this->bonfire->affected_rows();
        
        $this->trigger('before_delete', $id);

        // Set the where clause to be used in the update/delete below.
        $this->bonfire->where($this->forecastKey, $id);

        if ($this->soft_deletes === true) {
            $data = array($this->deleted_field => 1);
            if ($this->log_user === true) {
                $data[$this->deleted_by_field] = $user_id;
            }

            $result = $this->bonfire->update($this->forecast_tableName, $data);
        } else {
            $result = $this->bonfire->delete($this->forecast_tableName);
        }

        if ($result) {
            $this->trigger('after_delete', $id);
            return true;
        }

        $this->error = sprintf(lang('model_db_error'), $this->get_db_error_message());

        return false;
    }

    //--------------------------------------------------------------------------
    // !UTILITY FUNCTIONS
    //--------------------------------------------------------------------------

    /**
     * Trigger a model-specific event and calls each of its observers.
     *
     * @param string    $event  The name of the event to trigger.
     * @param mixed     $data   The data to be passed to the callback functions.
     *
     * @return mixed
     */
    public function trigger($event, $data = false)
    {
        if (! isset($this->$event) || ! is_array($this->$event)) {
            return $data;
        }

        foreach ($this->$event as $method) {
            if (strpos($method, '(')) {
                preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);
                $this->callback_parameters = explode(',', $matches[3]);
            }

            $data = call_user_func_array(array($this, $method), array($data));
        }

        return $data;
    }

    /**
     * Retrieve error messages from the database
     *
     * @return string
     */
    protected function get_db_error_message()
    {
        if (substr(CI_VERSION, 0, 1) != '2') {
            $error = $this->db->error();
            return isset($error['message']) ? $error['message'] : '';
        }

        switch ($this->db->platform()) {
            case 'cubrid':
                return cubrid_errno($this->db->conn_id);
            case 'mssql':
                return mssql_get_last_message();
            case 'mysql':
                return mysql_error($this->db->conn_id);
            case 'mysqli':
                return mysqli_error($this->db->conn_id);
            case 'oci8':
                // If the error was during connection, no conn_id should be passed
                $error = is_resource($this->db->conn_id) ? oci_error($this->db->conn_id) : oci_error();
                return $error['message'];
            case 'odbc':
                return odbc_errormsg($this->db->conn_id);
            case 'pdo':
                $error_array = $this->db->conn_id->errorInfo();
                return $error_array[2];
            case 'postgre':
                return pg_last_error($this->db->conn_id);
            case 'sqlite':
                return sqlite_error_string(sqlite_last_error($this->db->conn_id));
            case 'sqlsrv':
                $error = array_shift(sqlsrv_errors());
                return !empty($error['message']) ? $error['message'] : null;
            default:
                // !WARNING! $this->db->_error_message() is supposed to be private
                // and possibly won't be available in future versions of CI.
                return $this->db->_error_message();
        }
    }
}
