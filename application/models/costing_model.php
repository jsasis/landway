<?php

class Costing_Model extends CI_Model{

	private $table = 'costing';

	function __construct(){
		parent::__construct();
	}

	function create($data){
		if($this->db->insert($this->table, $data)){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
?>