<?php
require_once('main.php');
class Touches extends Main
{
	function __construct()
	{
		parent::__construct('touches');
		$this->load->library('common');
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
		$pag['base_url'] = site_url('touches/index');
		$pag['total_rows'] = $this->touch->count_all($this->get_current_shop()->id);
		
		$data['touches'] = $this->touch->get_all($this->get_current_shop()->id,$pag['per_page'],$this->uri->segment(3));
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

		
		$content['content'] = $this->load->view('touches/view',$data,true);		
		// print_r($pag['total_rows']);
		$this->load_template($content);
	}
	function dompdf() {
		$data = array();
		$pag = $this->config->item('pagination');
		$pag['base_url'] = site_url('touches/index');
		$pag['total_rows'] = $this->touch->count_all($this->get_current_shop()->id);
		
		$data['touches'] = $this->touch->get_all($this->get_current_shop()->id,$pag['per_page'],$this->uri->segment(3));
		$data['pag'] = $pag;
		$html = $this->output->get_output();
		$this->load->library('pdf');
		$this->dompdf->loadHtml($html);
		$this->dompdf->setPaper('A4', 'potrait');
		$this->dompdf->render();
		$this->dompdf->stream("btn.pdf", array("Attachment"=>0));
	}

	

	function detail($appuser_id)
	{
		$data['touches'] = $this->touch->get_info($appuser_id);
		
		$content['content'] = $this->load->view('touches/detail',$data,true);
		
		$this->load_template($content);
	}

	
}

