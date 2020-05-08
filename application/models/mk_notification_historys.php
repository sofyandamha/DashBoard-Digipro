<?php
class mk_notification_historys extends Base_Model
{
	protected $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'mk_notification_historys';
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
	
	
	function appuserid($appuser_id)
	{
		$this->db->from($this->table_name);
		$this->db->where('appuser_id', $appuser_id);
		$this->db->join('mk_notification_contents','mk_notification_contents.notification_id = '.$this->table_name.'.notification_id');
		$query = $this->db->get();
		return $query->result_array();
	}	
	
	function updateIsRead($notifid,$appuserid)
	{
		$data = array(
			'is_read' => 1
		);
		$this->db->where('notification_id',$notifid);
		$this->db->where('appuser_id',$appuserid);
		return $this->db->update( $this->table_name, $data );
	
	}

	function save( &$data )
	{
		$this->db->from($this->table_name);
		
		if ( isset($data['notification_id'] )) {
			$this->db->where( 'notification_id', $data['notification_id'] );
			$this->db->where( 'appuser_id', $data['appuser_id'] );
		}
		$query = $this->db->get();
		if($query->num_rows()==0){
			if ($this->db->insert($this->table_name,$data)) {
				$data['id'] = $this->db->insert_id();
				return true;
			}
		}

		
		return false;
	}

	function saves( &$data, $platform, $id=false )
	{
		
		if($platform  == "android") {
			
			if ( ! $id && ! $this->exists(array('id'=>$id))) {
				if ( $this->db->insert( $this->table_name,$data )) {
					$data['id'] = $this->db->insert_id();
					return true;
				}
			} else {
				//else update the data
				$this->db->where('id',$id);
				return $this->db->update( $this->table_name, $data );
			}
		
		} else {
			
			//if there is no data with this id, create new
			if ( $id && ! $this->exists(array('device_id'=>$id))) {
				if ( $this->db->insert( $this->table_name,$data )) {
					$data['id'] = $this->db->insert_id();
					return true;
				}
			} else {
				//else update the data
				$this->db->where('device_id',$id);
				return $this->db->update( $this->table_name, $data );
			}
		
		}
		
		return false;
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
		
		$this->db->order_by('added','desc');
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