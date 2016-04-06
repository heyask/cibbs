<?php 
class Membermodel extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	public function get_member_data($params)
	{
		foreach($params as $item)
			$this->db->where($item['column'], $item['value']);
		
		return $this->db->get('member');
	}
}
?>