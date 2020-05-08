<?php 
require_once(APPPATH.'/libraries/REST_Controller.php');

class notification extends REST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database(); 
	}
	
	function index_get()
	{
		$string = "";
		$data = $this->faqs->get_alls()->result_array();
		foreach ($data as $key => $value){ 
			$string = $string . $value['question'];
			$string = $string . '---';
			$string = $string . $value['answer'];
			$string = $string . '---';
			//$string = $string + $value[$key];
			//$string =  $string + "<br>";
			//code to be executed; 
		} 
		//print_r($data);
		$this->response(array(
			'status'=>count($data) > 0 ? true : false,
			'data'	=> count($data) > 0 ? $string : false
		));
	}
}
?>