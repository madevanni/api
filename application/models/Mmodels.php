<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmodels extends CI_Model {

	function count()
	{
		$this->bonfire->where('deleted!=', 1);
		return $this->bonfire->get('bf_models')->num_rows();
	}	

}

/* End of file Mmodels.php */
/* Location: ./application/models/Mmodels.php */