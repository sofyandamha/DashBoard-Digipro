<?php 
require_once(APPPATH.'/libraries/REST_Controller.php');

class Setting extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}
	
	function index_get()
	{
		$this->load->model('settings');
		$data = $this->settings->get_all()->result_array();
		// print_r($data);

		if(count($data) > 0){
			$data[0]['objectTypeTarget'] = "image";
			$data[0]['videoName'] = "btn2";
			$data[0]['imageUri'] = "https://img13.jd.id/Indonesia/nHBfsgAAEAAAAAUAB4ZIJgAGVpM.png";
			return $this->response($data[0]);
		}else{
			return false;
		}
		
		
	}

}
?>