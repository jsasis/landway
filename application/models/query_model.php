<?php
class Query_Model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}

	public function deleteAll() {
		$query = $this->db->truncate('ci_query');

		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

?>