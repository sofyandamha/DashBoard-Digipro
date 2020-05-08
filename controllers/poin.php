<?php
require_once('main.php');

class Poin extends Main
{
	function __construct()
	{
		parent::__construct('poin');
		$this->load->database(); 
	}

	//retrieve
	function index()
	{
		$this->session->unset_userdata('searchterm');

		// $config['pagination'] = array('class' => 'page-link');
		$pag = $this->config->item('pagination');
		$pag['base_url'] = site_url('poin/index');
		$pag['total_rows'] = $this->appuser->count_all_poin();
		
		$data['poin'] = $this->appuser->get_all_poin($pag['per_page'],$this->uri->segment(3));

		// print_r($pag['total_rows']);
		// print_r($data['poin']);
		$data['pag'] = $pag;
		
		$content['content'] = $this->load->view('poin/view',$data,true);
		
		$this->load_template($content);
	}
	
	function search()
	{
		$search_term = $this->searchterm_handler(htmlentities($this->input->post('searchterm')));
		
		$pag = $this->config->item('pagination');
		
		$pag['base_url'] = site_url('poin/search');
		$pag['total_rows'] = $this->appuser->count_all_by(array('searchterm'=>$search_term));
		
		$data['searchterm'] = $search_term;
		$data['poin'] = $this->appuser->get_all_by(array('searchterm'=>$search_term),$pag['per_page'],$this->uri->segment(3));
		$data['pag'] = $pag;
		
		$content['content'] = $this->load->view('poin/search',$data,true);
		
		$this->load_template($content);
	}
	
	function searchterm_handler($searchterm)
	{
	    if($searchterm){
	        $this->session->set_userdata('searchterm', $searchterm);
	        return $searchterm;
	    } elseif ($this->session->userdata('searchterm')) {
	        $searchterm = $this->session->userdata('searchterm');
	        return $searchterm;
	    } else {
	        $searchterm ="";
	        return $searchterm;
	    }
	}

	//create
	function detail($appuser_id)
	{
		$data['appuser'] = $this->appuser->get_info($appuser_id);
		
		$content['content'] = $this->load->view('poin/detail',$data,true);
		
		$this->load_template($content);
	}

	function ban($appuser_id = 0)
	{
		$this->check_access('ban');
		
		$data = array(
			'is_banned'=> 1
		);
			
		if ($this->appuser->save($data,$appuser_id)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
	
	function unban($appuser_id = 0)
	{
		$this->check_access('ban');
		
		$data = array(
			'is_banned'=> 0
		);
			
		if ($this->appuser->save($data,$appuser_id)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
	function edit($appuser_id=0)
	{
		if(!$this->session->userdata('is_shop_admin')) {
		    $this->check_access('edit');
		}
		
		
		if ($this->input->server('REQUEST_METHOD')=='POST') {

			$appuser_data = array();
			$this->load->model('poins');
			foreach ( $this->input->post() as $key=>$value) {
				  $s = $this->poins->get_info($appuser_id)->poin;
				$b = $s + htmlentities($value);
				$appuser_data[$key] = $b;
				// print_r($b);
				// print_r($key);
				// print_r($s);
				// print_r($value);
				// die();

			}

			if ( $this->poins->save( $appuser_data, $appuser_id )) {
				$this->session->set_flashdata('success','Poin is successfully updated.');
			} else {
				$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
			}
			redirect(site_url('poin'));
		}
		$this->load->model('poins');
		$data['poin'] = $this->poins->get_info($appuser_id);
		$content['content'] = $this->load->view('poin/edit',$data,true);		
		
		$this->load_template($content);
	}

}
?>