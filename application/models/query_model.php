<?php
class Query_Model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	function deleteAll() {
		$query = $this->db->truncate('ci_query');

		if($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

?>