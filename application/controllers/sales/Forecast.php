<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forecast extends CI_Controller {

	public function index()
	{
		
	}
	public function hapus()
	{
		$inputan = $this->input->post();
		$inputan = json_decode($inputan['data'],TRUE);
		$kode = $inputan['kode_aplikasi'];

		$id = $inputan['id'];
		$user_id = $inputan["user_id"];

		$this->db->where('kode_aplikasi', $kode);
		$ambil = $this->db->get('aplikasi');
		if($this->config->item("kode_aplikasi") == $kode )
		{
			$this->db->where('id', $id);
			$this->db->set('deleted','1');
			$this->db->set('deleted_by',$user_id);
			$this->db->update('bf_forecast');
			$respon['hasil'] = "sukses";
		}
		else
		{
			$respon["hasil"] = "gagalkode";
			$respon['pesan'] = "kode aplikasi tidak benar";
		}
		echo json_encode($respon);
	}

}

/* End of file Forecast.php */
/* Location: ./application/controllers/sales/Forecast.php */