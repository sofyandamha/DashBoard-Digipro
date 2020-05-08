<?php 
class Item extends Base_Model
{
	protected $table_name;
	
	function __construct()
	{
		parent::__construct();
		$this->table_name = 'mk_items';
		//table mk_stock_reserved
        $this->table_reserved='mk_stock_reserved';
        //table transaction header
        $this->table_trans = 'mk_transaction_header';
	}
	
	function exists($data)
	{
		$this->db->from($this->table_name);
		
		if (isset($data['id'])) {
			$this->db->where('id', $data['id']);
		}
		
		if (isset($data['sub_cat_id'])) {
			$this->db->where('sub_cat_id', $data['sub_cat_id']);
		}
		
		if (isset($data['name'])) {
			$this->db->where('name', $data['name']);
		}
		
		if (isset($data['shop_id'])) {
			$this->db->where('shop_id', $data['shop_id']);
		}
		
		$query = $this->db->get();
		return ($query->num_rows() == 1);
	}

	function save(&$data, $id = false)
	{
		if (!$id && !$this->exists(array('id' => $id, 'shop_id' => $data['shop_id']))) {
			if ($this->db->insert($this->table_name, $data)) {
				$data['id'] = $this->db->insert_id();
				return true;
			}
		} else {
			$this->db->where('id', $id);
			return $this->db->update($this->table_name, $data);
		}	
		return false;
	}

    function substract_stock(&$data, $id=false,$qty ,$trans_id)
    {

            //query item data by id
            $item=$this->get_info($id);
            $stock_curr=$item->stock;
            $shop_id=$item->shop_id;
            $name=$item->name;
            //process substarct stock/reserve stock
            $stock_update=$stock_curr-$qty;
            $detail_data_stock = array(

                'stock'                   => $stock_update,
            );

            //query transaction_header_id
            $transaction_id=$trans_id;
            $trans=$this->get_trans_info($transaction_id);

            $added_transaction=$trans->added;

            $data_reserved=array(
                'item_id'                   => $id,
                'shop_id'                   => $shop_id,
                'name'                   => $name,
                'transaction_header_id'                   => $transaction_id,
                'added_transaction'     =>$added_transaction,
                'stock'                   => $qty,

            );
            //check table reserved for decideing svae or update or even add new since table is empty
            if (!$this->exists_reserved($data_reserved)) {
                if ($this->db->insert($this->table_reserved, $data_reserved)) {
                   $this->db->insert($this->table_reserved, $data_reserved);

                }
            } else {
                $this->db->where('transaction_header_id', $transaction_id);
                $this->db->update($this->table_reserved, $data_reserved);
            }

            //update table items dengan stock baru
            $this->db->where('id', $id);
            return $this->db->update($this->table_name, $detail_data_stock);

        //return false;
    }

    function exists_reserved($data)
    {
        $this->db->from($this->table_reserved);

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
        }

        if (isset($data['item_id'])) {
            $this->db->where('item_id', $data['item_id']);
        }

        if (isset($data['name'])) {
            $this->db->where('name', $data['name']);
        }

        if (isset($data['shop_id'])) {
            $this->db->where('shop_id', $data['shop_id']);
        }
        if (isset($data['stock'])) {
            $this->db->where('stock', $data['stock']);
        }
        if (isset($data['transaction_header_id'])) {
            $this->db->where('transaction_header_id', $data['transaction_header_id']);
        }

       /* `stock` int(11) NOT NULL,
  `transaction_header_id` int(11) NOT NULL,
  `added_transaction` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,*/

        $query = $this->db->get();
        return ($query->num_rows() == 1);
    }

	function get_all($limit=false,$offset=false)
	{
		$this->db->from($this->table_name);
		// $this->db->where('shop_id', $shop_id);
		// $this->db->where('is_published',);
		
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

	function get_info($id)
	{
		$query = $this->db->get_where($this->table_name,array('id'=>$id));
		
		if ($query->num_rows()==1) {
			return $query->row();
		} else {
			return $this->get_empty_object($this->table_name);
		}
	}

    function get_trans_info($id)
    {
        $query = $this->db->get_where($this->table_trans,array('id'=>$id));

        if ($query->num_rows()==1) {
            return $query->row();
        } else {
            return $this->get_empty_object($this->table_trans);
        }
    }
	
	function get_multiple_info($ids)
	{
		$this->db->from($this->table_name);
		$this->db->where_in($ids);
		return $this->db->get();
	}
	
	function count_all()
	{
		$this->db->from($this->table_name);
		// $this->db->where('shop_id', $shop_id);
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