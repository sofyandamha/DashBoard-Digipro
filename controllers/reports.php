<?php
require_once('main.php');
class Reports extends Main
{
	function __construct()
	{
		parent::__construct('reports','touchs');
		$this->load->library('pdf2');
		$this->load->library("excel");
		$this->load->model ('usermodels');
		$this->load->helper('url');
	}

	function index()
	{

		$this->session->unset_userdata('searchterm');

		$pag = $this->config->item('pagination');
		$pag['base_url'] = site_url('appusers/index');
		$pag['total_rows'] = $this->appuser->count_all();

		$data['appusers'] = $this->appuser->get_all($pag['per_page'],$this->uri->segment(3));
		$data['pag'] = $pag;
		// $data['ex'] = $this->us->get_all()->result();
		$content['content'] = $this->load->view('reports/view',$data,true);


		$this->load_template($content);
	}
	function searchreport()
	{
		$search_term = $this->searchterm_handler(htmlentities($this->input->post('searchterm')));

		$pag = $this->config->item('pagination');

		$pag['base_url'] = site_url('reports/search');
		$pag['total_rows'] = $this->appuser->count_all_by(array('searchterm'=>$search_term));

		$data['searchterm'] = $search_term;
		$data['appusers'] = $this->appuser->get_all_by(array('searchterm'=>$search_term),$pag['per_page'],$this->uri->segment(3));
		$data['pag'] = $pag;

		$content['content'] = $this->load->view('reports/search',$data,true);

		$this->load_template($content);
	}
	function searchterm_handler($searchterms = array())
	{
		$data = array();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			foreach ($searchterms as $name=>$term) {
				if ($term && trim($term) != " ") {
					$this->session->set_userdata($name,$term);
					$data[$name] = $term;
				} else {
					$this->session->unset_userdata($term);
					$data[$name] = "";
				}
			}
		} else {
			foreach ($searchterms as $name=>$term) {
				if ($this->session->userdata($name)) {
					$data[$name] = $this->session->userdata($name);
				} else {
					$data[$name] = "";
				}
			}
		}

		return $data;
	}
	function dompdf() {
		$pdf = new FPDF('l','mm','A4');
		 // membuat halaman baru
		 $pdf->AddPage();
		 // setting jenis font yang akan digunakan
		 $pdf->SetFont('Arial','B',16);	
		 $pdf->SetFont('Arial','B',12);

		 if ($this->input->post('generate')) {
			$file_format = $this->input->post('file_format');
			$id = $this->input->post('id');
			$cs = $this->input->post('cs');
			$lm = $this->input->post('lm');
			$data['appusers'] = $this->usermodels->get_users();
			$data['loyalty'] =$this->usermodels->get_loyalty();
			$data['items'] = $this->usermodels->get_item();

			if ($file_format == "pdf") {

				if ($id == 'cs'){

				$pdf->Cell(250,7,'Customer Management',0,1,'C');
				 $pdf->Cell(10,7,'',0,1);
				 $pdf->SetFont('Arial','B',10);
				 $pdf->Cell(8,6,'No.',1,0);
				 $pdf->Cell(46,6,'Name',1,0);
				 $pdf->Cell(46,6,'Card',1,0);
				 $pdf->Cell(50,6,'Category',1,0);
				 $pdf->Cell(50,6,'Email',1,0);
				 $pdf->Cell(50,6,'Phone',1,1);
				 

				 $pdf->SetFont('Arial','',10);
				$n=0;
				for ($i = 0;  $i < count($data['appusers']) ; $i++) {
					$value   = substr_replace($data['appusers'][$i]['id_card'], str_repeat("x", 4), 8, 4);
					 $pdf->Cell(8,6,++$n,1,0);
					 $pdf->Cell(46,6,$data['appusers'][$i]['username'],1,0);
					 $pdf->Cell(46,6,$value,1,0);
					 $pdf->Cell(50,6,$data['appusers'][$i]['card_type'],1,0);
					 $pdf->Cell(50,6,$data['appusers'][$i]['email'],1,0);
					 $pdf->Cell(50,6,$data['appusers'][$i]['phone'],1,1);
					 
				 }
				 $filename = date('Y-M-d_H:i:s', time()) . ".pdf";
				 $pdf->Output('',$filename.'.pdf', false);
				}
				else if ($id == "lm"){
				$pdf->Cell(250,7,'Loyalty Management',0,1,'C');
				 // Memberikan space kebawah agar tidak terlalu rapat
				 $pdf->Cell(10,7,'',0,1);
				 $pdf->SetFont('Arial','B',10);
				 $pdf->Cell(75,6,'Username',1,0);
				 $pdf->Cell(100,6,'Email',1,0);
				 $pdf->Cell(25,6,'Poin',1,1);
				 $pdf->SetFont('Arial','',10);

				for ($i = 0; $i < count($data['loyalty']) ; $i++) {
					 $pdf->Cell(75,6,$data['loyalty'][$i]['username'],1,0);
					 $pdf->Cell(100,6,$data['loyalty'][$i]['email'],1,0);
					 $pdf->Cell(25,6,$data['loyalty'][$i]['Count_poin'],1,1);
				 }
				 $filename = date('Y-M-d_H:i:s', time()) . ".pdf";
				 $pdf->Output('',$filename.'.pdf', false);
				}
				else {
				$pdf->Cell(250,7,'Object management',0,1,'C');
				 // Memberikan space kebawah agar tidak terlalu rapat
				 $pdf->Cell(10,7,'',0,1);
				 $pdf->SetFont('Arial','B',10);
				 $pdf->Cell(75,6,'Object Name',1,0);
				 $pdf->Cell(100,6,'Category Name',1,0);
				 $pdf->Cell(25,6,'Item Qr Code',1,0);
				 $pdf->Cell(25,6,'Status',1,1);

				for ($i = 0; $i < count($data['items']) ; $i++) {
					 $pdf->Cell(75,6,$data['items'][$i]['name1'],1,0);
					 $pdf->Cell(100,6,$data['items'][$i]['name'],1,0);
					 $pdf->Cell(25,6,$data['items'][$i]['qr_code'],1,0);
					 $pdf->Cell(25,6,$data['items'][$i]['status'],1,1);
				 }
				 $filename = date('Y-M-d_H:i:s', time()) . ".pdf";
				 $pdf->Output('',$filename.'.pdf', false);
				}
				
				 
			}
			else if ($file_format == "csv") {
				
				if($id==cs){
					$object = new PHPExcel();

					  $object->setActiveSheetIndex(0);

					  $table_columns = array("Name", "Card","Category","Email","Phone");

					  $column = 0;

					  foreach($table_columns as $field){

					    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);

					    $column++;

					  }

					  $employee_data = $this->usermodels->get_users();

					  $excel_row = 2;

					  foreach($data['appusers'] as $row){
					  	$value   = substr_replace($row['id_card'], str_repeat("x", 4), 8, 4);
					    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['username']);
					    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $value);
					    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['card_type']);
					    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['email']);
					    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['email']);
					    $excel_row++;

					  }

					  $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');

					  header('Content-Type: application/vnd.ms-excel');

					  header('Content-Disposition: attachment;filename="customer.xls"');

					  $object_writer->save('php://output');


				}
				elseif ($id==lm){
					$object = new PHPExcel();

				  $object->setActiveSheetIndex(0);

				  $table_columns = array("Username", "Email","Poin");

				  $column = 0;

				  foreach($table_columns as $field){

				    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);

				    $column++;

				  }


				  $excel_row = 3;

				  foreach($data['loyalty'] as $row){

				    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['username']);
				    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['email']);
				    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['Count_poin']);
				    $excel_row++;

				  }

				  $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');

				  header('Content-Type: application/vnd.ms-excel');

				  header('Content-Disposition: attachment;filename="loyalty.xls"');

				  $object_writer->save('php://output');
				}

				else {
					$object = new PHPExcel();

					  $object->setActiveSheetIndex(0);

					  $table_columns = array("Object Name", "Category Name","Item QR Code", "Status");

					  $column = 0;

					  foreach($table_columns as $field){

					    $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);

					    $column++;

					  }

					  $excel_row = 2;

					  foreach($data['items'] as $row){

					    $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['name1']);
					    $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['name']);
					    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['qr_code']);
					    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['status']);
					    $excel_row++;

					  }

					  $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');

					  header('Content-Type: application/vnd.ms-excel');

					  header('Content-Disposition: attachment;filename="Object.xls"');

					  $object_writer->save('php://output');

				}
			   
			}

		}

	}

	
	function detail($appuser_id)
	{

		$data['appuser'] 		= $this->appuser->get_info($appuser_id);
		$data['touch'] 			= $this->usermodels->get_touch($this->uri->segment(3));


		$content['content'] = $this->load->view('customor_report/detail',$data,true);

		$this->load_template($content);
	}

	function analytic()
	{
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

		$content['content'] = $this->load->view('reports/analytic',$data,true);

		$this->load_template($content);
	}
}
?>
