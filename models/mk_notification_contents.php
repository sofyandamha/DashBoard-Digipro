<?php
class mk_notification_contents extends Base_Model
{
	protected $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'mk_notification_contents';
	}

	function exists( $conds )
	{
		$this->db->from($this->table_name);
		
		if ( isset($conds['id'] )) {
			$this->db->where( 'id', $conds['id'] );
		}

		if ( isset($conds['reg_id'] )) {
			$this->db->where( 'reg_id', $conds['reg_id'] );
		}

		if ( isset($conds['device_id'] )) {
			$this->db->where( 'device_id', $conds['device_id'] );
		}
		
		$query = $this->db->get();
		return ($query->num_rows()==1);
	}
	
	function save( &$data )
	{
		
		if ($this->db->insert($this->table_name,$data)) {
			$data['id'] = $this->db->insert_id();
			return true;
		}
		
		return false;
	}

	function updateReachUser($notification_id)
	{
		$this->db->set('reach_user', 'reach_user + 1',FALSE); 
		$this->db->where('notification_id', $notification_id); 
		return $this->db->update($this->table_name); 
	}
	
	function get_all($limit=false, $offset=false)
	{
		$this->db->from($this->table_name);

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
	
	function get_info_by_email($email)
	{
		$query = $this->db->get_where($this->table_name,array('email'=>$email));
		
		if ($query->num_rows()==1) {
			return $query->row();
		} else {
			return $this->get_empty_object($this->table_name);
		}
	}

	function get_info_by_appuserid($appuser_id)
	{
		$query = $this->db->get_where($this->table_name,array('appuser_id'=>$appuser_id));
		return $query->get()->result_array();
	}	
	
	function get_multiple_info($user_ids)
	{
		$this->db->from($this->table_name);
		$this->db->where_in($user_ids);
		return $this->db->get();
	}

	function count_all()
	{
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
	}
	
	function count_all_by($conditions=array())
	{
		$this->db->from( $this->table_name );
		$this->db->where( $conditions );
		return $this->db->count_all_results();
	}
	
	function get_all_by( $conditions = array(), $limit = false, $offset = false )
	{
		$this->db->from( $this->table_name );
		$this->db->where( $conditions );

		if ( $limit ) {
			$this->db->limit($limit);
		}
		
		if ( $offset ) {
			$this->db->offset($offset);
		}
		
		$this->db->order_by('added','desc');
		return $this->db->get();
	}

	function delete_by( $conditions ) 
	{
		$this->db->where( $conditions );
		return $this->db->delete( $this->table_name );
	}
}
?>