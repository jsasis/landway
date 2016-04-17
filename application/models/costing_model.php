<?php

class Costing_Model extends CI_Model{

	private $table = 'costing';

	public function __construct(){
		parent::__construct();
	}

	public function create($data){
		if($this->db->insert($this->table, $data)){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
?>