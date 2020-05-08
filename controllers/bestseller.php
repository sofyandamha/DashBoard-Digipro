<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 12/6/17
 * Time: 2:34 PM
 */
require_once('main.php');

class Bestseller extends Main
{
    function __construct()
    {
        parent::__construct('bestseller');
        $this->load->library('uploader');
    }

//
    function index()
    {
        echo "index best selller";
        $this->session->unset_userdata(array(
            "searchterm" => "",
            "sub_cat_id" => "",
            "cat_id" => "",
            "discount_type_id" => ""
        ));

        $id=$this->get_current_shop()->id;
        //
        $pag = $this->config->item('pagination');
        $pag['base_url'] = site_url('bestseller/index');
        $pag['total_rows'] = $this->item->count_all($this->get_current_shop()->id);

       //
        $allTrans = $this->transaction_header->get_all_by(
            $id,
            array(),
            $pag['per_page'],
            $this->uri->segment(3)
        )->result();
        //get all transaction and short
        $transaction=array();
        $details=array();
        $items=array();

         // $i=0;
        foreach ($allTrans as $trans){

            //kodisi transaksi valid
            if($trans->transaction_status==2){
                $transaction[] = $trans;
                foreach ($transaction as $singTrans){
                    $details[] = $this->transaction_detail->get_all_by_header($trans->id)->result();
                }
               // $i++;
            }

        }
        //get all item id and short most frequent appears
        foreach ($details as $val){
            foreach ($val as $val1){
//
                if(count($val1->item_id)>0){
                    $items[]=$val1->item_name;
                    $items_id[]=$val1->item_id;
                }
            }

        }


        $values = array_count_values($items);
        arsort($values);
        $bestSeller = array_slice(array_keys($values), 0, 10, true);

       /* echo "item dari bestSeller <pre>";
        print_r($bestSeller);
        echo "</pre>";*/
        /*(
        [conn_id] => Resource id #9
    [result_id] => Resource id #225
    [result_array] => Array
    (
    )

    [result_object] => Array
    (
    )

    [custom_result_object] => Array
    (
    )

    [current_row] => 0
    [num_rows] => 123
    [row_data] =>
)*/

        $allitems=$this->item->get_all($this->get_current_shop()->id);//, $pag['per_page'], $this->uri->segment(3));
       // $allitems=$this->best_seller->get_all($this->get_current_shop()->id, $pag['per_page'], $this->uri->segment(3));
        foreach($allitems->result_array() as $item){
            /*echo "item dari allitems <pre>";
            print_r($item);
            echo "</pre>";*/
         // echo $item['name']."<br>";
            foreach ($bestSeller as $key){
                //echo $key."<br>";
                //echo $item['name']."<br>";
                if($item['name'] === $key){
                    echo $item['id']."ok ada<br>";
                    //
                    $this->autoSave($item,$item['id']);

                }

            }



        }

      /*  if (empty($allitems->result_object()))
        {
            echo "array is empty";

            foreach($allitems->result_object() as $item){

                foreach ($bestSeller as $key){
                    //echo $key."<br>";
                    if($item->name === $key){
                        echo $item->id."<br>";
                        //
                        $this->autoSave($item,$item->id);

                    }

                }


            }
        }
        else
        {
            echo "not empty";
            foreach($allitems->result_object() as $item){

                foreach ($bestSeller as $key){
                    //echo $key."<br>";
                    if($item->name === $key){
                        echo $item->id."<br>";
                        //
                        $this->autoSave($item,$item->id);

                    }

                }


            }
        }*/
//exit();
        $data['bestSeller']=$bestSeller;

        $data["transaction"] =$transaction;
        $data["transaction_detail"]=$details;

        $data['items'] = $allitems;

       // $data['items'] = $this->item->get_all($this->get_current_shop()->id, $pag['per_page'], $this->uri->segment(3));

        //$data['categories'] = $this->best_seller->get_all($this->get_current_shop()->id, $pag['per_page'],$this->uri->segment(3));

        $data['pag'] = $pag;

        $data['shop_id']=$id;

        if(count($data)>0){
            echo "<script> console.log('tidak ada data dr transaksi');</script>";
            $allitems=$this->best_seller->get_all($this->get_current_shop()->id);
            //$data['items'] = $allitems;
//            echo "<pre>";
//            print_r($allitems);
//            echo "</pre>";
            foreach($allitems->result_array() as $item){
//                echo "<pre>";
//                print_r($item);
//                echo "</pre>";
                $data['items'] = $allitems;
            }

        }else{
            echo "<script> console.log('else data dr transaksi');</script>";

        }

        $content['content'] = $this->load->view('bestseller/view',$data,true);

        $this->load_template($content);


    }

    function autoSave($items,$item_id)
    {
        //echo "<br>autoSave init<br>";
        if(!$this->session->userdata('is_shop_admin')) {
            $this->check_access('edit');
        }
       // echo "<br>autoSave init session ok<br>";
        $item_data = array();
        foreach ( $items as $key=>$value) {
            //echo "key = $key val = $value<br>";
            $item_data[$key] = $value;
            //$item['item_id']=$item_id;
           // echo "id = $item_id key[$key] = ".$item_data[$key] = $value."<br>";

        }

//            if ($this->best_seller->save($item_data, $item_id)) {
//                $this->session->set_flashdata('success','Best Seller is successfully updated.');
//            } else {
//                $this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
//            }

            if ($this->best_seller->save($item_data,$item_id)) {
               // echo "<br>autoSave success<br>";
                $this->session->set_flashdata('success','Item is successfully added.');
            } else {
                //$this->best_seller->update($item_data, $item_id);
                //echo "<br>autoSave error<br>";
                $this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
            }

        //echo "<br>autoSave end<br>";

    }

    function item(){

        $this->session->unset_userdata(array(
            "searchterm" => "",
            "sub_cat_id" => "",
            "cat_id" => "",
            "discount_type_id" => ""
        ));

        $pag = $this->config->item('pagination');
        $pag['base_url'] = site_url('items/index');
        $pag['total_rows'] = $this->item->count_all($this->get_current_shop()->id);

        $data['items'] = $this->item->get_all($this->get_current_shop()->id, $pag['per_page'], $this->uri->segment(3));
        $allBest=array();
          $bestseller  =$this->best_seller->get_all($this->get_current_shop()->id);
          foreach ($bestseller->result() as $best){
              //$data['bestseller']=$best;

              //print_r($best);

              array_push($allBest,$best);
          }
        //print_r($allBest);
        $data['bestseller']=$allBest;
        $data['pag'] = $pag;

        $content['content'] = $this->load->view('bestseller/item', $data, true);

        $this->load_template($content);
    }

    function add()
    {
        if(!$this->session->userdata('is_shop_admin')) {
            $this->check_access('add');
        }

        $action = "save";
        unset($_POST['save']);
        if (htmlentities($this->input->post('gallery'))) {
            $action = "gallery";
            unset($_POST['gallery']);
        }

        if ($this->input->server('REQUEST_METHOD')=='POST') {

            $item_data = array();
            foreach ( $this->input->post() as $key=>$value) {
                $item_data[$key] = htmlentities($value);
            }

            $item_data['shop_id'] = $this->get_current_shop()->id;
            $item_data['is_published'] = 1;

            //unset($item_data['cat_id']);

            if ($this->best_seller->save($item_data)) {
                $this->session->set_flashdata('success','Item is successfully added.');
            } else {
                $this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
            }

            if ($action == "gallery") {
                redirect(site_url('bestseller/gallery/'.$item_data['id']));
            } else {
                redirect(site_url('bestseller'));
            }
        }

        $cat_count = $this->category->count_all($this->get_current_shop()->id);
        $sub_cat_count = $this->sub_category->count_all($this->get_current_shop()->id);

        if($cat_count <= 0 && $sub_cat_count <= 0) {
            $this->session->set_flashdata('error','Oops! Please create the category and sub category first before you create items.');
            redirect(site_url('bestseller'));
        } else {
            if($cat_count <= 0) {
                $this->session->set_flashdata('error','Oops! Please create the category first before you create items.');
                redirect(site_url('bestseller'));
            } else if ($sub_cat_count <= 0) {
                $this->session->set_flashdata('error','Oops! Please create the sub category first before you create items.');
                redirect(site_url('bestseller'));
            }
        }

        $content['content'] = $this->load->view('bestseller/add',array(),true);
        $this->load_template($content);
    }

    function search()
    {
        $search_arr = array(
            "searchterm" => htmlentities($this->input->post('searchterm')),
            "sub_cat_id" => htmlentities($this->input->post('sub_cat_id')),
            "cat_id" => htmlentities($this->input->post('cat_id')),
            "discount_type_id" => htmlentities($this->input->post('discount_type_id')),
            "all_item"=>htmlentities($this->input->post('allitems'))
        );

        $search_term = $this->searchterm_handler($search_arr);
        $data = $search_term;
        //print_r($data);
        echo "data search =". $data['searchterm']." data allitems = ".$data['allitems'];
        if(empty($data['all_item'])){
            $pag = $this->config->item('pagination');

            $pag['base_url'] = site_url('bestseller/search');
            $pag['total_rows'] = $this->best_seller->count_all_by($this->get_current_shop()->id, $search_term);

            $data['items'] = $this->best_seller->get_all_by($this->get_current_shop()->id, $search_term, $pag['per_page'], $this->uri->segment(3));
            $data['pag'] = $pag;

            $content['content'] = $this->load->view('bestseller/search',$data,true);
            $this->load_template($content);
        }else{
            $this->search_item();
        }




    }

    function search_item(){
        $search_arr = array(
            "searchterm" => htmlentities($this->input->post('searchterm')),
            "sub_cat_id" => htmlentities($this->input->post('sub_cat_id')),
            "cat_id" => htmlentities($this->input->post('cat_id')),
            "discount_type_id" => htmlentities($this->input->post('discount_type_id')),
            "all_item"=>htmlentities($this->input->post('allitems'))
        );

        $search_term = $this->searchterm_handler($search_arr);
        $data = $search_term;
        //print_r($data);
        echo "data search =". $data['searchterm']." data allitems = ".$data['allitems'];
        echo "ke search_item";
        $pag = $this->config->item('pagination');

        $pag['base_url'] = site_url('bestseller/search_item');
        $pag['total_rows'] = $this->item->count_all_by($this->get_current_shop()->id, $search_term);

        $data['items'] = $this->item->get_all_by($this->get_current_shop()->id, $search_term, $pag['per_page'], $this->uri->segment(3));
        $data['pag'] = $pag;

        $content['content'] = $this->load->view('bestseller/search_item',$data,true);

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

            if ($this->best_seller->save($item_data, $item_id)) {
                $this->session->set_flashdata('success','Best Seller is successfully updated.');
            } else {
                $this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
            }
            redirect(site_url('bestseller'));
        }

        $data['item'] = $this->best_seller->get_info($item_id);

        $content['content'] = $this->load->view('bestseller/edit',$data,true);

        $this->load_template($content);
    }

    function gallery($id)
    {
        session_start();
        $_SESSION['parent_id'] = $id;
        $_SESSION['type'] = 'item';
        $content['content'] = $this->load->view('bestseller/gallery', array('id' => $id), true);


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

        $data['item'] = $this->best_seller->get_info($item_id);

        $content['content'] = $this->load->view('bestseller/edit',$data,true);

        $this->load_template($content);
    }

    function publish($id = 0)
    {
       // echo "<br>publish id = $id";
        if(!$this->session->userdata('is_shop_admin')) {
            $this->check_access('publish');
        }
        $items=$this->item->get_info($id);
//        echo "<pre>";
//        print_r($items);
//        echo "</pre>";

        $item_data = array(
            'is_published'=> 1,
            'item_id'=>$items->id,
            'cat_id'=>$items->cat_id,
            'sub_cat_id'=>$items->sub_cat_id,
            'shop_id'=>$items->shop_id,
            'stock'=>$items->stock,
            'qr_code'=>$items->qr_code,
            'url'=>$items->url,
            'discount_type_id'=>$items->discount_type_id,
            'name'=>$items->name,
            'description'=>$items->description,
            'unit_price'=>$items->unit_price,
            'search_tag'=>$items->search_tag,

            'added'=>$items->added,
            'updated'=>$items->updated,

        );

        try{
            if ($this->best_seller->save($item_data, $id)) {
                //print_r($item_data);
                echo 'true';
            } else {
                echo 'false';
            }
        }catch (Exception $e){
            echo "Exception $e";
        }


    }

    function unpublish($id = 0)
    {
        //echo "unpublish id = $id";
        //$id=1;
        if(!$this->session->userdata('is_shop_admin')) {
            $this->check_access('publish');
        }


        $items=$this->item->get_info($id);
        /*echo "<pre>";
        print_r($items);
        echo "</pre>".$items->id;*/

        $item_data = array(
            'is_published'=> 0,
            'item_id'=>$items->id,
            'cat_id'=>$items->cat_id,
            'sub_cat_id'=>$items->sub_cat_id,
            'shop_id'=>$items->shop_id,
            'stock'=>$items->stock,
            'qr_code'=>$items->qr_code,
            'url'=>$items->url,
            'discount_type_id'=>$items->discount_type_id,
            'name'=>$items->name,
            'description'=>$items->description,
            'unit_price'=>$items->unit_price,
            'search_tag'=>$items->search_tag,

            'added'=>$items->added,
            'updated'=>$items->updated,

        );

//        echo "<pre>";
//        print_r($item_data);
//        echo "</pre>";
        //exit();

        if ($this->best_seller->save($item_data,$id)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function publish_view($id = 0)
    {
        // echo "<br>publish id = $id";
        if(!$this->session->userdata('is_shop_admin')) {
            $this->check_access('publish');
        }
        $items=$this->item->get_info($id);
//        echo "<pre>";
//        print_r($items);
//        echo "</pre>";

        $item_data = array(
            'is_published'=> 1,
            'item_id'=>$items->id,
            'cat_id'=>$items->cat_id,
            'sub_cat_id'=>$items->sub_cat_id,
            'shop_id'=>$items->shop_id,
            'stock'=>$items->stock,
            'qr_code'=>$items->qr_code,
            'url'=>$items->url,
            'discount_type_id'=>$items->discount_type_id,
            'name'=>$items->name,
            'description'=>$items->description,
            'unit_price'=>$items->unit_price,
            'search_tag'=>$items->search_tag,

            'added'=>$items->added,
            'updated'=>$items->updated,

        );

        try{
            if ($this->best_seller->save($item_data, $id)) {
                //print_r($item_data);
                echo 'true';
            } else {
                echo 'false';
            }
        }catch (Exception $e){
            echo "Exception $e";
        }


    }

    function unpublish_view($id = 0)
    {
        //echo "unpublish id = $id";
        //$id=1;
        if(!$this->session->userdata('is_shop_admin')) {
            $this->check_access('publish');
        }


        $items=$this->item->get_info($id);
        /*echo "<pre>";
        print_r($items);
        echo "</pre>".$items->id;*/

        $item_data = array(
            'is_published'=> 0,
            'item_id'=>$items->id,
            'cat_id'=>$items->cat_id,
            'sub_cat_id'=>$items->sub_cat_id,
            'shop_id'=>$items->shop_id,
            'stock'=>$items->stock,
            'qr_code'=>$items->qr_code,
            'url'=>$items->url,
            'discount_type_id'=>$items->discount_type_id,
            'name'=>$items->name,
            'description'=>$items->description,
            'unit_price'=>$items->unit_price,
            'search_tag'=>$items->search_tag,

            'added'=>$items->added,
            'updated'=>$items->updated,

        );

//        echo "<pre>";
//        print_r($item_data);
//        echo "</pre>";
        //exit();

        if ($this->best_seller->save($item_data,$id)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function unbest($id = 0)
    {
        echo "unpublish id = $id";
        if(!$this->session->userdata('is_shop_admin')) {
            $this->check_access('publish');
        }

        $item_data = array(
            'is_published'=> 0
        );
        $item_data[]=$this->item->get_info($id);

        if (uto_add_item($item_data,$id)) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function auto_add_item($items,$item_id)
    {

        $item_data = array();
        foreach ( $items as $key=>$value) {
            $item_data[$key] = $value;
            //$item['item_id']=$item_id;
            // echo "id = $item_id key[$key] = ".$item_data[$key] = $value."<br>";

        }

        if ($this->best_seller->save($item_data,$item_id)) {
            $this->session->set_flashdata('success','Item is successfully added.');
        } else {
            //$this->best_seller->update($item_data, $item_id);
            $this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
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

        if ($this->best_seller->delete($item_id)) {
            $this->attribute_header->delete_by_item($item_id);
            $this->attribute_detail->delete_by_item($item_id);
            $this->session->set_flashdata('success','The item is successfully deleted.');
        } else {
            $this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
        }
        redirect(site_url('bestseller'));
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
        redirect(site_url('bestseller/edit/'.$item_id));
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

        if (trim(strtolower($this->best_seller->get_info($item_id)->name)) == strtolower($name)) {
            echo "true";
        } else if($this->best_seller->exists(array(
            'name'=> $name,
            'sub_cat_id' => $sub_cat_id
        ))) {
            echo "false";
        } else {
            echo "true";
        }
    }

}