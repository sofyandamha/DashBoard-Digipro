<?php
class Touch extends Base_Model
{
	protected $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'mk_touches';
	}

	function exists($data)
	{
		$this->db->from($this->table_name);
		
		if (isset($data['id'])) {
			$this->db->where('id', $data['id']);
		}
		
		if (isset($data['appuser_id'])) {
			$this->db->where('appuser_id', $data['appuser_id']);
		}
		
		if (isset($data['item_id'])) {
			$this->db->where('item_id', $data['item_id']);
		}
		
		$query = $this->db->get();
		return ($query->num_rows()==1);
	}

	function save(&$data, $id = false)
	{
		//if there is no data with this id, create new
		if (!$id && !$this->exists(array('item_id' => $data['item_id'], 'appuser_id' => $data['appuser_id']))) {
			if ($this->db->insert($this->table_name,$data)) {
				$data['id'] = $this->db->insert_id();
				return true;
			}
		} else {
			//else update the data
			$this->db->where('id',$id);
			return $this->db->update($this->table_name,$data);
		}
		
		return $false;
	}

	function get_all( $limit = false, $offset = false)
	{
		$this->db->from($this->table_name);
		// $this->db->where('shop_id', $shop_id);
		
		if ($limit) {
			$this->db->limit($limit);
		}
		
		if ($offset) {
			$this->db->offset($offset);
		}
		
		$this->db->order_by('added','desc');
		return $this->db->get();
	}

	function get_all_item($header_id)
	{
		$this->db->from($this->table_name);
		$this->db->where('item_id',$header_id);
		$this->db->order_by('id','asc');
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


	function count_all($shop_id, $item_id = false)
	{
		$this->db->from($this->table_name);
		$this->db->where('shop_id', $data['shop_id']);
		
		if ($item_id) {
			$this->db->where('item_id',$item_id);
		}
		
		return $this->db->count_all_results();
	}
	
	function count_all_id($item_id=false)
	{
		$this->db->from($this->table_name);
		
		if ($item_id) {
			$this->db->where('item_id',$item_id);
		}
		
		return $this->db->count_all_results();
	}	
	function delete_by_shop($shop_id)
	{
		$this->db->where('shop_id', $shop_id);
		return $this->db->delete($this->table_name);
	}

	function get_touch()
	{
	    
		$sql	= "SELECT * FROM mk_touches INNER JOIN mk_items ON mk_items.id = mk_touches.item_id" ;
		// $que = $this->db->order_by("item_id", "asc");
		$query		= $this->db->query($sql);
		$data		= $query->result_array();
		return $data;
	}
}
?>