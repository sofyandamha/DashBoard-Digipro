<?php
require_once('main.php');

class Appusers extends Main
{
	function __construct()
	{
		parent::__construct('appusers');
		$this->load->model ('usermodels');
		$this->load->helper('text');
		$this->load->helper('url');
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->library('pdf');
	}

	//retrieve
	function index()
	{
		$this->session->unset_userdata('searchterm');

		$pag = $this->config->item('pagination');
		$pag['base_url'] = site_url('appusers/index');
		$pag['total_rows'] = $this->appuser->count_all();

		$data['appusers'] = $this->appuser->get_all($pag['per_page'],$this->uri->segment(3));
		$data['pag'] = $pag;

		$cat_id = 0;
		$cat_name = "";
		
		$sub_cat_id = 0;
		$sub_cat_name = "";
		
		if (htmlentities($this->input->post('cat_id'))) {
			$cat_id = htmlentities($this->input->post('cat_id'));
		}
		
		if (htmlentities($this->input->post('sub_cat_id'))) {
			$sub_cat_id = htmlentities($this->input->post('sub_cat_id'));
		}
		
		$data['cat_id'] = $cat_id;
		$data['sub_cat_id'] = $sub_cat_id;
		$data['appuser']=$this->appuser->get_online();

		$items = $this->item->get_all_by_sub_cat($sub_cat_id)->result();

		$item_arr = array();
		foreach ($items as $item) {
			$item_arr[$item->name] = $this->touch->count_all_id($item->id);
		}
		
		$graph_arr = array();
		foreach ($item_arr as $name=>$count) {
			$graph_arr[] = "['".$name."',".$count."]";
		}
		
		arsort($item_arr);
		$pie_arr = array();
		$i = 0;
		foreach ($item_arr as $name=>$count) {
			if(($i++) < 5){
				$pie_arr[] = "['".$name."',".$count."]";
			}
		}




		$data['count'] = count($items);
		$data['cat_name'] = $this->category->get_cat_name_by_id($cat_id);
		$data['sub_cat_name'] = $this->sub_category->get_sub_cat_name_by_id($sub_cat_id);

		$data['graph_items'] = "[['Items','Touches'],".implode(',',$graph_arr)."]";
		$data['pie_items'] = "[['Items','Touches'],".implode(',',$pie_arr)."]";

        $users_online=$this->appuser->get_online()->result();
        $users_offline=$this->appuser->get_offline()->result();
//        echo "<pre>";
//        print_r($users_online);
//        echo "</pre>";
        $user_arr = array();
        foreach ($users_online as $user) {
            //echo "user=".$user->username."<br>";
            $user_arr[$user->username] = $this->appuser->count_all_by_online();
        }

        $graph_uarr = array();
        foreach ($user_arr as $name=>$count) {

            $graph_uarr[] = "['".$name."',".$count."]";
        }

        arsort($user_arr);
        $pie_uarr = array();
        $i = 0;
        foreach ($user_arr as $name=>$count) {
            if(($i++) < 5){
                $pie_uarr[] = "['".$name."',".$count."]";
            }
        }
        $data['count_online']=count($users_online);
        $data['count_offline']=count($users_offline);

       // echo "count_online".$count($users_online);
        $data['graph_users'] = "[['Items','Touches'],".implode(',',$graph_uarr)."]";
        $data['pie_users'] = "[['Items','Touches'],".implode(',',$pie_uarr)."]";

		$content['content'] = $this->load->view('appusers/view',$data,true);
		// $text = 'abcde';
		// echo word_limiter($text, 2);

		$this->load_template($content);
	}

	function search()
	{
		$search_term = $this->searchterm_handler(htmlentities($this->input->post('searchterm')));

		$pag = $this->config->item('pagination');

		$pag['base_url'] = site_url('appusers/search');
		$pag['total_rows'] = $this->appuser->count_all_by(array('searchterm'=>$search_term));

		$data['searchterm'] = $search_term;
		$data['appusers'] = $this->appuser->get_all_by(array('searchterm'=>$search_term),$pag['per_page'],$this->uri->segment(3));
		$data['pag'] = $pag;

		$content['content'] = $this->load->view('appusers/search',$data,true);

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

		$data['appuser'] 		= $this->appuser->get_info($appuser_id);
		$data['touch'] 			= $this->usermodels->get_touch($this->uri->segment(3));


		$content['content'] = $this->load->view('appusers/detail',$data,true);

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

}
?>
