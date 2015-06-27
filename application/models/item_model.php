<?php
	
	class Item_Model extends CI_Model {

		function __construct() {
			parent::__construct();
		}

		function create() {
			$data = array(
				'quantity' => $this->input->post('quantity'),
				'description' => $this->input->post('description'),
				'unit' => $this->input->post('unit'),
				'unit_cost' => $this->input->post('unit_cost'),
				);

			if($this->db->insert('item', $data)) {
				return true;
			}else{
				return false;
			}
		}
	}
?>