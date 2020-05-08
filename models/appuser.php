<?php
class Appuser extends Base_Model
{
	protected $table_name;
	var $column_order = array(null, 'username','email','phone','id_card','card_type','online','added');
	var $column_search = array('username','email','phone','id_card','card_type','online','added');
    var $order = array('id' => 'asc'); // default order 

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'mk_appusers';
	}
	
	function exists($data)
	{
		$this->db->from($this->table_name);
		
		if (isset($data['id'])) {
			$this->db->where('id',$data['id']);
		}
		
		if (isset($data['email'])) {
			$this->db->where('email',$data['email']);
		}

        if (isset($data['id_card'])) {
            $this->db->where('id_card',$data['id_card']);
        }
		
		$query = $this->db->get();
		return ($query->num_rows()==1);
	}
	
	function save(&$data, $id=false)
	{
		//if there is no data with this id, create new
		if (!$id && !$this->exists(array('id'=>$id))) {
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
	
	function get_all($limit=false, $offset=false)
	{
		$this->db->from($this->table_name);
		$this->db->where('status',1);
		if ($limit) {
			$this->db->limit($limit);
		}
		
		if ($offset) {
			$this->db->offset($offset);
		}
		
		$this->db->order_by('added','desc');
		return $this->db->get();
	}
	
	function get_all_poin ($limit=false, $offset=false){
		$sql	= "SELECT
  `mk_appusers`.`username`,
  `mk_appusers`.`email`,
  Sum(`mk_poins`.`poin`) AS `Count_poin`,
  `mk_poins`.`id`,
  `mk_poins`.`appuser_id`
FROM
  `mk_poins`
  INNER JOIN `mk_appusers` ON `mk_appusers`.`id` = `mk_poins`.`appuser_id`
GROUP BY
  `mk_appusers`.`username`,
  `mk_appusers`.`email`,
  `mk_poins`.`appuser_id`
ORDER BY
  `Count_poin` DESC" ;
		// $que = $this->db->order_by("item_id", "asc");
		if ($limit) {
			$this->db->limit($limit);
		}
		
		if ($offset) {
			$this->db->offset($offset);
		}
		return  $this->db->query($sql);
		
	}

    function get_all_shop($limit=false, $offset=false)
    {

        $query = $this->db->select('*')->from($this->table_name)->where('status',1)->get();
        //print_r($query->result());
        return $query->result();

       // return $this->db->get($this->table_name);
    }

    function get_online($limit=false, $offset=false)
    {
        $this->db->from($this->table_name);
        $this->db->where('online',1);
        if ($limit) {
            $this->db->limit($limit);
        }

        if ($offset) {
            $this->db->offset($offset);
        }

        $this->db->order_by('added','desc');
        return $this->db->get();
    }

    function get_offline($limit=false, $offset=false)
    {
        $this->db->from($this->table_name);
        $this->db->where('online',0);
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
		$this->db->where('status',1);
		return $this->db->count_all_results();
	}
	function count_all_poin(){
		$sql	= "SELECT Count(DISTINCT `mk_poins`.`appuser_id`) AS `Count_appuser_id` FROM `mk_poins`" ;
		$query		= $this->db->query($sql);
		// $data		= $query;
		return $this->db->count_all_results();
	}

	
	
	function count_all_by($conditions=array())
	{
		$this->db->from($this->table_name);
		
		if (isset($conditions['searchterm'])) {
			$this->db->like('username',$conditions['searchterm']);
			$this->db->or_like('username',$conditions['searchterm']);
		}
			
		$this->db->where('status',1);
		return $this->db->count_all_results();
	}

    function count_all_by_online()
    {
        $this->db->from($this->table_name);
        $this->db->where('online',1);
        return $this->db->count_all_results();
    }
	
	function get_all_by($conditions=array(),$limit=false,$offset=false)
	{
		$this->db->from($this->table_name);
		
		if (isset($conditions['searchterm'])) {
			$this->db->like('username',$conditions['searchterm']);
			$this->db->or_like('email',$conditions['searchterm']);
			$this->db->or_like('phone',$conditions['searchterm']);
			$this->db->or_like('id_card',$conditions['searchterm']);
		}
			
		$this->db->where('status',1);
		if ($limit) {
			$this->db->limit($limit);
		}
		
		if ($offset) {
			$this->db->offset($offset);
		}
		
		$this->db->order_by('added','desc');
		return $this->db->get();
	}

	function login($user_name,$user_pass)
	{
		$query = $this->db->get_where($this->table_name,array('email'=>$user_name,'password'=>md5($user_pass),'status'=>1),1);
		if ($query->num_rows()==1) {
			$this->db->where('email', $user_name);
			$this->db->update($this->table_name, array('online'=> 1));
			return $query->row();
		}
		return false;
	}
	public function logout(&$data ,$id=true)
	{
		$query = $this->db->get_where($this->table_name,array('id'=>$data),1);
		if ($query->num_rows()==1) {
			$now = mysql_to_unix($this->category->get_now());
			$date =  date("Y-m-d H:i:s", $now);
			$this->db->where('id', $data);
			$this->db->update($this->table_name, array('co' => $date, 'online'=> 0));
			return $query->row();
		}
		$this->session->sess_destroy();
		return false;
	}

	function delete($id)
	{
		$this->db->where('id',$id);
		return $this->db->delete($this->table_name);
	}

	function ago($time)
	{
		$time = mysql_to_unix($time);
		$now = mysql_to_unix($this->category->get_now());
		
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");
	
	   $difference     = $now - $time;
	   $tense         = "ago";
	
	   for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	       $difference /= $lengths[$j];
	   }
	
	   $difference = round($difference);
	
	   if ($difference != 1) {
	       $periods[$j].= "s";
	   }
	   
	   if ($difference==0) {
	   		return "Just Now";
	   } else {
	   		return "$difference $periods[$j] ago";
	   }
	}

	function fetch_data()
	 {
	  $this->db->order_by("id", "DESC");
	  $query = $this->db->get($this->table_name);
	  return $query->result();
	 }

	private function _get_datatables_query()
    {
         
        $this->db->from($this->table_name);
 
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables()
    {

        $this->_get_datatables_query();
        if(isset($_POST["length"]) && $_POST["length"] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
	public function count_all_sort()
	{
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
	}
	
}
?>