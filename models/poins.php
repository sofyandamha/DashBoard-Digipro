<?php
class Poins extends Base_Model
{
	protected $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'mk_poins';
	}

	function exists($data)
	{
		$this->db->from($this->table_name);
		
		if (isset($data['id'])) {
			$this->db->where('id',$data['id']);
		}
		
		if (isset($data['item_id'])) {
			$this->db->where('item_id',$data['item_id']);
		}
		
		if (isset($data['appuser_id'])) {
			$this->db->where('appuser_id',$data['appuser_id']);
		}
		
		if (isset($data['shop_id'])) {
			$this->db->where('shop_id', $data['shop_id']);
		}
		
		$query = $this->db->get();
		return ($query->num_rows() >= 1);
		
	}

	function save(&$data, $id = false)
	{
		//if there is no data with this id, create new
		if (!$id && !$this->exists(array('id' => $id, 'shop_id' => $data['shop_id']))) {
			if ($this->db->insert('mk_poins', $data)) {
				$data['id'] = $this->db->insert_id();
				return true;
			}
		} else {
			//else update the data
			$this->db->where('id', $id);
			return $this->db->update($this->table_name, $data);
		}
		
		return $false;
	}

    function save_from_review(&$data, $id = false)
    {
        //unset eyvalue review
        unset($data['review']);
        //if there is no data with this id, create new
        if (!$id && !$this->exists(array('id' => $id, 'shop_id' => $data['shop_id']))) {
            if ($this->db->insert($this->table_name, $data)) {
                $data['id'] = $this->db->insert_id();
                return true;
            }
        } else {
            //else update the data
            $this->db->where('id', $id);
            return $this->db->update($this->table_name, $data);
        }

        return $false;
    }

	function get_info($id)
	{
		$query = $this->db->get_where($this->table_name, array('id' => $id));
		
		if ($query->num_rows()==1) {
			return $query->row();
		} else {
			return $this->get_empty_object($this->table_name);
		}
	}

	function get_all($shop_id, $limit=false, $offset=false)
	{
		$this->db->from($this->table_name);
		$this->db->where('shop_id', $shop_id);
		
		if ($limit) {
			$this->db->limit($limit);
		}
		
		if ($offset) {
			$this->db->offset($offset);
		}
		
		$this->db->order_by('added','desc');
		return $this->db->get();
	}

	function get_all_by_item_id($item_id)
	{
		$this->db->from($this->table_name);
		$this->db->where('item_id',$item_id);
		$this->db->order_by('added','desc');
		return $this->db->get();
	}

	function count_all($shop_id, $item_id=false)
	{
		$this->db->from($this->table_name);
		$this->db->where('shop_id', $shop_id);
		
		if ($item_id) {
			$this->db->where('item_id',$item_id);
		}
		
		return $this->db->count_all_results();
	}
	
	function count_all_by($shop_id, $conditions=array())
	{
		$this->db->from($this->table_name);
		$this->db->where('shop_id', $shop_id);
		
		if (isset($conditions['searchterm'])) {
			$this->db->like('review',$conditions['searchterm']);
		}
		
		return $this->db->count_all_results();
	}
	
	function get_all_by($shop_id, $conditions=array(),$limit=false,$offset=false)
	{
		$this->db->from($this->table_name);
		$this->db->where('shop_id', $shop_id);
		
		if (isset($conditions['searchterm'])) {
			$this->db->like('review',$conditions['searchterm']);
		}
			
		if ($limit) {
			$this->db->limit($limit);
		}
		
		if ($offset) {
			$this->db->offset($offset);
		}
		
		$this->db->order_by('added','desc');
		return $this->db->get();
	}

	function delete($id)
	{
		$this->db->where('id',$id);
		return $this->db->update($this->table_name,array('status'=>0));
	}
	
	function get_rating_by_id($shop_id, $item_id=false)
	{
		$query = $this->db->query('SELECT sum( rating ) as total_rating
			FROM `mk_ratings`
			Where shop_id='.$shop_id.' and item_id='.$item_id);
		//print_r($query);die;	
		if ($query->num_rows()==1) {
			return $query->row();
		} else {
			return $this->get_empty_object($this->table_name);
		}
	}
	
	function delete_by_shop($shop_id)
	{
		$this->db->where('shop_id', $shop_id);
		return $this->db->delete($this->table_name);
	}

	function get_poin_by_id($id, $item_id=false)
	{
		$query = $this->db->query('SELECT sum( poin ) as poin
			FROM `mk_poins`
			Where appuser_id='.$id.'');
		//print_r($query);die;	
		if ($query->num_rows()==1) {
			return $query->row();
		} else {
			return $this->get_empty_object($this->table_name);
		}
	}
}
?>