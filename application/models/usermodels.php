<?php
class Usermodels extends Base_Model
{
	protected $table_name;

	function __construct()
	{
		parent::__construct();
		$this->table_name = 'mk_touches';
	}

	function get_touch($id)
	{
	    
		$sql	= "SELECT * FROM mk_touches JOIN mk_items ON mk_touches.item_id = mk_items.id JOIN mk_appusers ON mk_touches.appuser_id = mk_appusers.id WHERE mk_touches.appuser_id =".$id." GROUP BY mk_touches.item_id" ;
		// $que = $this->db->order_by("item_id", "asc");
		$query		= $this->db->query($sql);
		$data		= $query->result_array();
		return $data;
	}

	function get_item()
	{
	    
		$sql	= "SELECT mk_categories.name, mk_items.name AS name1, mk_items.qr_code, IF(`mk_items`.`is_published` = 1, 'publish', 'unpublish') AS `status` FROM mk_items
INNER JOIN mk_categories ON mk_categories.id = mk_items.cat_id";
		$query		= $this->db->query($sql);
		$data		= $query->result_array();
		return $data;
	}

	function get_app($id)
	{
		$sql	= "SELECT mk_touches.appuser_id , mk_appusers.username, mk_items.name,
  mk_touches.item_id, mk_touches.added FROM mk_items
  INNER JOIN mk_touches ON mk_items.id = mk_touches.item_id
  INNER JOIN mk_appusers ON mk_touches.appuser_id = mk_appusers.id
  WHERE mk_appusers.id =".$id."
   " ;
		// $que = $this->db->order_by("item_id", "asc");
		$query		= $this->db->query($sql);
		$data		= $query->result_array();
		return $data;
		
	}

	


	function get_users()
	{

		$sql	= "SELECT * FROM	mk_appusers ORDER BY added desc";
		$query		= $this->db->query($sql);
		$data		= $query->result_array();
		return $data;
	}
	function get_loyalty()
	{

		$sql	= "SELECT `mk_appusers`.`username`,`mk_appusers`.`email`,Sum(`mk_poins`.`poin`) AS `Count_poin`,`mk_poins`.`id` 	FROM `mk_poins` INNER JOIN `mk_appusers` ON `mk_appusers`.`id` = `mk_poins`.`appuser_id`
					GROUP BY `mk_appusers`.`username`, `mk_appusers`.`email` ORDER BY `Count_poin` DESC";
		$query		= $this->db->query($sql);
		$data		= $query->result_array();
		return $data;
	}

	function getfaqs(){
 
    $response = array();
 
    // Select record
    $this->db->select('*');
    $q = $this->db->get('mk_faqs');

    return $q;
  }
}
?>
