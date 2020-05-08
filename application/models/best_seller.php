<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 12/6/17
 * Time: 2:37 PM
 */


class Best_seller extends Base_Model
{
    protected $table_name;

    function __construct()
    {
        parent::__construct();
        $this->table_name = 'mk_bestseller';
    }

    function check_empty()
    {

        if(empty($row[$this->table_name])){
            echo $row[$this->table_name];
            return 0;
        }else{
            return 1;
        }
        //return 0;
    }

    function exists($data)
    {
        $this->db->from($this->table_name);

        /*if (isset($data['id_a'])) {
             echo "id_a ada";
             $this->db->where('id_a', $data['id_a']);
             $query = $this->db->get();
         }*/

        if (isset($data['item_id'])) {
            //echo "item_id ada = ".$data['id'];
            $this->db->where('item_id', $data['item_id']);
            $query = $this->db->get();
            //echo "--- ".($query->num_rows());
            return ($query->num_rows());
        }

        if (isset($data['sub_cat_id'])) {
            $this->db->where('sub_cat_id', $data['sub_cat_id']);
            $query = $this->db->get();
        }

        if (isset($data['name'])) {
            $this->db->where('name', $data['name']);
            $query = $this->db->get();
        }

        if (isset($data['shop_id'])) {
            $this->db->where('shop_id', $data['shop_id']);
            $query = $this->db->get();
        }


        //$query = $this->db->get();
        return ($query->num_rows() == 1);
    }


    function save(&$data, $id = false)
    {
         //echo " $this->table_name id = $id  <pre>";
         //exit;
        //echo " test ".$this->exists(array('id' => $id, 'shop_id' => $data['shop_id'],'id_a' => $data['id_a']))."--";
        $data['item_id']=$id;
        if ($this->exists(array('item_id' =>  $data['item_id']))==0) {
            //echo "save";
            //unset($data['id']);
            if ($this->db->insert($this->table_name, $data)) {
                $data['item_id'] = $this->db->insert_id();
                return true;
            }
        } else {
           // echo "<br> update =". $data['item_id']." <br>";

            //unset($data['id']);
            // print_r($data);

            $this->db->where('item_id', $data['item_id']);

            return $this->db->update($this->table_name, $data);
        }
        return false;
    }

    function get_all($shop_id, $limit=false,$offset=false)
    {

        $this->db->from($this->table_name);
        $this->db->where('shop_id', $shop_id);
        $this->db->where('is_published',1);

        if ($limit) {
            $this->db->limit($limit);
        }

        if ($offset) {
            $this->db->offset($offset);
        }

        $this->db->order_by('added','desc');
        return $this->db->get();
    }

    function get_all_by_cat($cat_id)
    {
        $this->db->from($this->table_name);
        $this->db->where('cat_id',$cat_id);
        return $this->db->get();
    }

    function get_all_by_sub_cat($sub_cat_id, $keyword=false, $limit = false, $offset = false)
    {
        $this->db->from($this->table_name);
        $this->db->where('sub_cat_id',$sub_cat_id);
        $this->db->where('is_published',1);


        if ($keyword && trim($keyword) != "") {

            $this->db->like(name,$keyword);
            $this->db->or_like(description,$keyword);
            $this->db->or_like(search_tag,$keyword);

        }

        if ($limit) {
            $this->db->limit($limit);
        }

        if ($offset) {
            $this->db->offset($offset);
        }
        return $this->db->get();
    }

    function get_info($id,$limit=false,$offset=false)
    {
        if ($limit) {
            $this->db->limit($limit);
        }

        if ($offset) {
            $this->db->offset($offset);
        }

        $query = $this->db->get_where($this->table_name,array('item_id'=>$id), $limit, $offset);

        if ($query->num_rows()==1) {
            return $query->row();
        } else {
            return $this->get_empty_object($this->table_name);
        }
    }

    function get_multiple_info($ids)
    {
        $this->db->from($this->table_name);
        $this->db->where_in($ids);
        return $this->db->get();
    }

    function count_all($shop_id)
    {
        $this->db->from($this->table_name);
        $this->db->where('shop_id', $shop_id);
        //$this->db->where('is_published',1);
        return $this->db->count_all_results();
    }

    function count_all_by($shop_id, $conditions=array())
    {
        $this->db->from($this->table_name);
        $this->db->where('shop_id', $shop_id);

        if ($conditions['sub_cat_id'] != 0) {
            $this->db->where('sub_cat_id', $conditions['sub_cat_id']);
        }

        if ($conditions['cat_id'] != 0) {
            $this->db->where('cat_id', $conditions['cat_id']);
        }

        if (isset($conditions['searchterm']) && trim($conditions['searchterm']) != "") {

            $this->db->like(name,$conditions['searchterm']);
            $this->db->or_like(description,$conditions['searchterm']);
            $this->db->or_like(search_tag,$conditions['searchterm']);

        }

        if (isset($conditions['discount_type_id']) && $conditions['discount_type_id'] != 0) {
            $this->db->where('discount_type_id', $conditions['discount_type_id']);
        }

        $this->db->where('is_published',1);

        return $this->db->count_all_results();
    }

    function get_all_by($shop_id, $conditions=array(),$limit=false,$offset=false)
    {
        $this->db->from($this->table_name);
        $this->db->where('shop_id', $shop_id);

        if ($conditions['sub_cat_id'] != 0) {
            $this->db->where('sub_cat_id', $conditions['sub_cat_id']);
        }

        if ($conditions['cat_id'] != 0) {
            $this->db->where('cat_id', $conditions['cat_id']);
        }

        if (isset($conditions['searchterm']) && trim($conditions['searchterm']) != "") {

            $this->db->like(name,$conditions['searchterm']);
            $this->db->or_like(description,$conditions['searchterm']);
            $this->db->or_like(search_tag,$conditions['searchterm']);

        }

        if (isset($conditions['discount_type_id']) && $conditions['discount_type_id'] != 0) {
            $this->db->where('discount_type_id', $conditions['discount_type_id']);
        }

        $this->db->where('is_published',1);

        if ($limit) {
            $this->db->limit($limit);
        }

        if ($offset) {
            $this->db->offset($offset);
        }

        $this->db->order_by('added','desc');
        return $this->db->get();
    }

    function add_discount_type($discount_type_id, $item_ids = array())
    {
        $this->db->where_in('id', $item_ids);
        return $this->db->update($this->table_name, array('discount_type_id' => $discount_type_id));
    }

    function remove_discount_type($discount_type_id) {
        $this->db->where('discount_type_id', $discount_type_id);
        return $this->db->update($this->table_name, array('discount_type_id' => '0'));
    }

    function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table_name);
    }

    function delete_by_cat($cat_id)
    {
        $this->db->where('cat_id', $cat_id);
        return $this->db->delete($this->table_name);
    }

    function delete_by_sub_cat($sub_cat_id)
    {
        $this->db->where('sub_cat_id', $sub_cat_id);
        return $this->db->delete($this->table_name);
    }

    function get_popular_items($limit=false, $offset=false)
    {
        $filter = "";
        if ($limit && $offset) {
            $filter = "limit $limit offset $offset";
        } else if ($limit){
            $filter = "limit $limit";
        }

        $sql = "
			SELECT count( appuser_id ) as cnt, item_id
			FROM `mk_likes`
			GROUP BY item_id 
			Order By cnt desc
			$filter
		";

        $query = $this->db->query($sql);
        return $query;
    }

    function delete_by_shop($shop_id)
    {
        $this->db->where('shop_id', $shop_id);
        return $this->db->delete($this->table_name);
    }

    function get_all_by_search($city_id = 0, $keyword = false, $limit = false, $offset = false)
    {
        $this->db->from($this->table_name);
        if($city_id != 0) {
            $this->db->where('shop_id',$city_id);
        }
        $this->db->where('is_published',1);


        if ($keyword && trim($keyword) != "") {
            $this->db->where("(
				name LIKE '%". $this->db->escape_like_str( $keyword ) ."%' OR 
				description LIKE '%". $this->db->escape_like_str( $keyword ) ."%' OR 
				search_tag LIKE '%". $this->db->escape_like_str( $keyword ) ."%' 
			)", NULL, FALSE);
        }

        if ($limit) {
            $this->db->limit($limit);
        }

        if ($offset) {
            $this->db->offset($offset);
        }
        return $this->db->get();
    }
}
?>
