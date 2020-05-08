<?php
class Gcm_token extends Base_Model
{
	protected $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'mk_gcm_tokens';
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
		if ( isset($conds['appuser_id'] )) {
			$this->db->where( 'appuser_id', $conds['appuser_id'] );
		}
		
		$query = $this->db->get();
		return ($query->num_rows()==1);
	}
	
	function updateByAppUserId($reg_id,$appuser_id)
	{
		$data = array(
			'reg_id' => $reg_id
		);
		$this->db->where('appuser_id',$appuser_id);
		return $this->db->update( $this->table_name, $data );
	}

	function update( &$data, $platform, $id=false )
	{
		
		if($platform  == "android") {
			
			if ( ! $id && ! $this->exists(array('id'=>$id))) {
				if ( $this->db->update( $this->table_name,$data )) {
					//$data['id'] = $this->db->insert_id();
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
				if ( $this->db->update( $this->table_name,$data )) {
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

	function save( &$data, $platform, $id=false )
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

	function getbyRegId($reg_id)
	{
		$this->db->select('*');
		$this->db->from('mk_gcm_tokens');
		$this->db->where('reg_id', $reg_id);
		return $this->db->get()->result_array();
		//return $this->db->get();
	}
	
	function get_all_data($limit=false, $offset=false)
	{
		$this->db->select('*');
		$this->db->from('mk_gcm_tokens');
		$this->db->join('mk_appusers', 'mk_gcm_tokens.appuser_id = mk_appusers.id');
		$this->db->order_by('mk_gcm_tokens.added','desc');
		//$query = $this->db->get()->result_array();
		return $this->db->get();
	}

	function get_all_regid($limit=false, $offset=false)
	{
		$this->db->select('reg_id');
		$this->db->from('mk_gcm_tokens');
		$this->db->order_by('mk_gcm_tokens.added','desc');
		//$query = $this->db->get()->result_array();
		return $this->db->get()->result_array();
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