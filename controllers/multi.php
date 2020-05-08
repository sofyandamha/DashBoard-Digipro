<?php
require_once('main.php');

class Multi extends Main
{
	function __construct()
	{
		parent::__construct('multi');
		$this->load->library('uploader');

       // $this->load->library('ci_qr_code');

        //$this->load->library('ciqrcode');
        $this->config->load('qr_code');

	}

	function index()
	{


		$this->session->unset_userdata(array(
			"searchterm" => "",
			// "sub_cat_id" => "",
			"cat_id" => "",
			"discount_type_id" => ""
		));

		$pag = $this->config->item('pagination');
		$pag['base_url'] = site_url('multi/index');
		$pag['total_rows'] = $this->item->count_all($this->get_current_shop()->id);

		$data['multi'] = $this->item->get_all($this->get_current_shop()->id, $pag['per_page'], $this->uri->segment(3));
		$data['pag'] = $pag;

		$content['content'] = $this->load->view('multi/view', $data, true);

		$this->load_template($content);
	}

    /**
     * print_qr
     *
     * @access public
     * @param user_id
     * @return
     */
    function print_qr($item_id)
    {

        //$this->load->library('ciqrcode');

        /*$params['data'] = 'This is a text to encode become QR Code';
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH.'tes.png';
        $this->ciqrcode->generate($params);

        echo '<img src="'.site_url().'tes.png" />';*/

        /*$ci=& get_instance();
        $ci->load->library("ci_qr_code");*/

        //echo "print_qr $item_id";
        $qr_code_config = array();
        $qr_code_config['cacheable'] = $this->config->item('cacheable');
        $qr_code_config['cachedir'] = $this->config->item('cachedir');
        $qr_code_config['imagedir'] = $this->config->item('imagedir');
        $qr_code_config['errorlog'] = $this->config->item('errorlog');
        $qr_code_config['ciqrcodelib'] = $this->config->item('ciqrcodelib');
        $qr_code_config['quality'] = $this->config->item('quality');
        $qr_code_config['size'] = $this->config->item('size');
        $qr_code_config['black'] = $this->config->item('black');
        $qr_code_config['white'] = $this->config->item('white');
        // print_r($qr_code_config);

       /* $is_loaded = is_object(@$this->ci_qr_code) ? TRUE : FALSE;// @ sign suppressed error if object didn't exist
        var_dump($is_loaded);*/


        $this->ci_qr_code->initialize($qr_code_config);
        $image_name = $item_id . ".png";
        //echo "print_qr 1 = ". $image_name;
        // get full name and user details
        //$user_details = $this->user->get_users_one($item_id);
        $data= $this->item->get_info($item_id);
        $images=$this->image->get_info_parent_type($item_id,"item");



        // create user content
        $codeContents = "{'item_id':'";
        $codeContents .= $data->id."',";
        $codeContents .= "'item_cat':'";
        $codeContents .= $data->cat_id."',";
        $codeContents .= "'item_sub_cat':'";
        $codeContents .= $data->sub_cat_id."',";
        $codeContents .= "'item_name':'";
        $codeContents .= $data->name."',";
        $codeContents .= "'item_poin':'";
        $codeContents .= $data->unit_price."',";
        $codeContents .= "'item_url':'";
        $codeContents .= $data->url."',";
        $codeContents .= "'item_img':'";
        $codeContents .= $images->path."',";
        $codeContents .= "'item_desc':'";
        $codeContents .= $data->description."'}";
				// print_r($codeContents);
        $params['data'] = $codeContents;
        $params['level'] = 'H';
        $params['size'] = 10;

        $params['savename'] = FCPATH . $qr_code_config['imagedir'] . $image_name;
        $this->ci_qr_code->generate($params);
        echo "<br>print_qr 1 = ".FCPATH . $qr_code_config['imagedir'] . $image_name;
        $this->data['qr_code_image_url'] = base_url() . $qr_code_config['imagedir'] . $image_name;
        echo "<br>url= ". base_url().$qr_code_config['imagedir'] . $image_name;

        // save image path in tree table
        $item_data = array();
        $item_data["qr_code"]=$image_name;

        if ($this->item->save($item_data, $item_id)) {
            $this->session->set_flashdata('success','Item is successfully updated.');
            echo "success update";
        } else {
            echo "db error";
            $this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
        }
        redirect(site_url('multi'));
        // then redirect to see image link
        /*$file = $params['savename'];
        if(file_exists($file)){
            //echo "file ada";
            header('Content-Description: File Transfer');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            unlink($file); // deletes the temporary file

            //exit;
            //
        }*/




//        $content['content'] = $this->load->view('multi/qr_code', $data, true);
//
//        $this->load_template($content);
    }

	function add()
	{
		// print_r($this->input->post());
		// die();
		if(!$this->session->userdata('is_shop_admin')) {
		      $this->check_access('add');
		}

		$action = "save";
		unset($_POST['save']);
		if (htmlentities($this->input->post('gallery'))) {
			$action = "gallery";
			unset($_POST['gallery']);
		}

        if (htmlentities($this->input->post('qr_code'))) {
            $action = "qr_code";
            unset($_POST['qr_code']);
        }

		if ($this->input->server('REQUEST_METHOD')=='POST') {


			$input_data_post = array_merge($this->input->post(),array("qr_code"=>""));
			$item_data = array();
			foreach ( $input_data_post as $key=>$value) {
				$item_data[$key] = htmlentities($value);
			}

			$item_data['shop_id'] = $this->get_current_shop()->id;
			$item_data['is_published'] = 1;

			//unset($item_data['cat_id']);

			if ($this->item->save($item_data)) {
				$this->session->set_flashdata('success','Item is successfully added.');
			} else {
				$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
			}

			if ($action == "gallery") {
				redirect(site_url('multi/gallery/'.$item_data['id']));
			} elseif ($action == "qr_code"){
                redirect(site_url('multi/qr_code/'.$item_data['id']));
            }
			else {
				redirect(site_url('multi'));
			}
		}

		$cat_count = $this->category->count_all($this->get_current_shop()->id);
		$sub_cat_count = $this->sub_category->count_all($this->get_current_shop()->id);

		if($cat_count <= 0 && $sub_cat_count <= 0) {
			$this->session->set_flashdata('error','Oops! Please create the category and sub category first before you create multi.');
			redirect(site_url('multi'));
		} else {
			if($cat_count <= 0) {
				$this->session->set_flashdata('error','Oops! Please create the category first before you create multi.');
				redirect(site_url('multi'));
			} else if ($sub_cat_count <= 0) {
				$this->session->set_flashdata('error','Oops! Please create the sub category first before you create multi.');
				redirect(site_url('multi'));
			}
		}

		$content['content'] = $this->load->view('multi/add',array(),true);
		$this->load_template($content);
	}

	function search()
	{
		$search_arr = array(
			"searchterm" => htmlentities($this->input->post('searchterm')),
			"sub_cat_id" => htmlentities($this->input->post('sub_cat_id')),
			"cat_id" => htmlentities($this->input->post('cat_id')),
			"discount_type_id" => htmlentities($this->input->post('discount_type_id'))
		);

		$search_term = $this->searchterm_handler($search_arr);
		$data = $search_term;

		$pag = $this->config->item('pagination');

		$pag['base_url'] = site_url('multi/search');
		$pag['total_rows'] = $this->item->count_all_by($this->get_current_shop()->id, $search_term);

		$data['multi'] = $this->item->get_all_by($this->get_current_shop()->id, $search_term, $pag['per_page'], $this->uri->segment(3));
		$data['pag'] = $pag;

		$content['content'] = $this->load->view('multi/search',$data,true);

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

	function edit($item_id=0)
	{
		if(!$this->session->userdata('is_shop_admin')) {
		    $this->check_access('edit');
		}

		if ($this->input->server('REQUEST_METHOD')=='POST') {

			$item_data = array();
			foreach ( $this->input->post() as $key=>$value) {
				$item_data[$key] = htmlentities($value);
			}

			if(!htmlentities($this->input->post('is_published'))) {
				$item_data['is_published'] = 0;
			}

			if ($this->item->save($item_data, $item_id)) {
				$this->session->set_flashdata('success','Item is successfully updated.');
			} else {
				$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
			}
			redirect(site_url('multi'));
		}

		$data['item'] = $this->item->get_info($item_id);

		$content['content'] = $this->load->view('multi/edit',$data,true);

		$this->load_template($content);
	}

	function gallery($id)
	{
		session_start();
		$_SESSION['parent_id'] = $id;
        $_SESSION['type'] = 'item';
        $data['item'] = $this->item->get_info($id);
        $data['img']=$this->image->get_info_parent_type($id,"item");
    	$content['content'] = $this->load->view('multi/gallery', array('id' => $id), true);

    	$this->load_template($content);
	}

    function qr_code($id)
    {
        session_start();
        $_SESSION['parent_id'] = $id;
        $_SESSION['type'] = 'item';
        //echo "load";
        $data['item'] = $this->item->get_info($id);
        $data['img']=$this->image->get_info_parent_type($id,"item");
				// print_r($data);
				// die();
        $content['content'] = $this->load->view('multi/qr_code', $data, true);

        $this->load_template($content);
    }

	function upload($item_id=0)
	{
		if(!$this->session->userdata('is_shop_admin')) {
		    $this->check_access('edit');
		}

		$upload_data = $this->uploader->upload($_FILES);

		if (!isset($upload_data['error'])) {
			
			foreach ($upload_data as $upload) {
				$image = array(
								'item_id'=>$item_id,
								'path' => $upload['file_name'],
								'width'=>$upload['image_width'],
								'height'=>$upload['image_height']
							);
				$this->image->save($image);
			}
		} else {
			$data['error'] = $upload_data['error'];
		}
		 

		$data['item'] = $this->item->get_info($item_id);

		$content['content'] = $this->load->view('multi/edit',$data,true);

		$this->load_template($content);
	}

	function publish($id = 0)
	{
		if(!$this->session->userdata('is_shop_admin')) {
			$this->check_access('publish');
		}

		$item_data = array(
			'is_published'=> 1
		);

		if ($this->item->save($item_data, $id)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}

	function unpublish($id = 0)
	{
		if(!$this->session->userdata('is_shop_admin')) {
			$this->check_access('publish');
		}

		$item_data = array(
			'is_published'=> 0
		);

		if ($this->item->save($item_data, $id)) {
			echo 'true';
		} else {
			echo 'false';
		}
	}

	function delete($item_id=0)
	{
		if(!$this->session->userdata('is_shop_admin')) {
		     $this->check_access('delete');
		}

		$images = $this->image->get_all_by_type($item_id, 'item');
		foreach ($images->result() as $image) {
			$this->image->delete($image->id);
			unlink('./uploads/'.$image->path);
		}

		if ($this->item->delete($item_id)) {
			$this->attribute_header->delete_by_item($item_id);
			$this->attribute_detail->delete_by_item($item_id);
			$this->session->set_flashdata('success','The item is successfully deleted.');
		} else {
			$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
		}
		redirect(site_url('multi'));
	}

	function delete_image($item_id, $image_id, $image_name)
	{
		if(!$this->session->userdata('is_shop_admin')) {
		    $this->check_access('edit');
		}

		if ($this->image->delete($image_id)) {
			unlink('./uploads/'.$image_name);
			$this->session->set_flashdata('success','The image is successfully deleted.');
		} else {
			$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
		}
		redirect(site_url('multi/edit/'.$item_id));
	}

	function get_sub_cats($cat_id)
	{
		$sub_categories = $this->sub_category->get_all_by_cat_id($cat_id);
		echo json_encode($sub_categories->result());
	}

	function exists($item_id = 0)
	{
		$name = trim($_REQUEST['name']);
		$cat_id = $_REQUEST['cat_id'];
		$sub_cat_id = $_REQUEST['sub_cat_id'];

		if (trim(strtolower($this->item->get_info($item_id)->name)) == strtolower($name)) {
			echo "true";
		} else if($this->item->exists(array(
			'name'=> $name,
			'sub_cat_id' => $sub_cat_id
		))) {
			echo "false";
		} else {
			echo "true";
		}
	}
}
?>
