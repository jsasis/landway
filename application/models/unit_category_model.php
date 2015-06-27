<?php

class Unit_Category_Model extends CI_Model{

	private $table = 'unit_category';

	function __construct(){
		parent::__construct();
	}

	function create($data){
		if(isset($data['unit_category_id'])){

			$this->db->where('unit_category_id', $data['unit_category_id']);
			if($this->db->update($this->table, $data)){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			$this->db->trans_start();

			$this->db->insert($this->table, $data['item']);
			$unit_category_id = $this->db->insert_id();

			$this->load->model('costing_model');
			$data = array(
				'unit_category_id' => $unit_category_id,
				'cost'			   => $data['item_cost'],
				'effective_date'			   => date('Y-m-d H:i:s')
			);
			$this->costing_model->create($data);

			$this->db->trans_complete();
			if($this->db->trans_status()){
				return TRUE;
			}else{
				return FALSE;
			}

		}
	}

	function read($unitID = null){
		if($unitID == null){
			$this->db->order_by('unit_category_id','DESC');
			$query = $this->db->get($this->table);

			return $query->result();
		}else{
			$this->db->select('unit.unit_id,unit.description as unit,unit_category_id,unit_category.description');
			$this->db->join('unit','unit_category.unit_id = unit.unit_id');
			$this->db->where('unit_category_id',$unitID);
			$query = $this->db->get($this->table);

			return $query->row_array();
		}
	}

	function deleteAll($data){
		for($i=0; $i<sizeof($data); $i++){
			$this->db->where('unit_category_id',$data[$i]);
			$this->db->delete($this->table);
		}
		return true;
	}

	function recordCount(){
		$this->db->select('uc.unit_category_id, u.unit_code as unit, uc.description, ct.cost as unit_cost');
		$this->db->join('unit_category uc', 'uc.unit_category_id = ct.unit_category_id');
		$this->db->join('unit u', 'u.unit_id = uc.unit_id');
		
		$this->db->where('effective_date = ( SELECT MAX(effective_date) FROM costing c WHERE c.unit_category_id = ct.unit_category_id )');

		return $this->db->count_all_results('costing ct');
	}

	function fetchUnit($limit, $start) {
		$this->db->select('uc.unit_category_id, u.unit_code as unit, uc.description, ct.cost as unit_cost');
		$this->db->join('unit_category uc', 'uc.unit_category_id = ct.unit_category_id');
		$this->db->join('unit u', 'u.unit_id = uc.unit_id');
		$this->db->where('effective_date = ( SELECT MAX(effective_date) FROM costing c WHERE c.unit_category_id = ct.unit_category_id )');

		$query = $this->db->get('costing ct', $limit, $start);

		if ($query->num_rows > 0) {
			return $query->result();
		}else{
			return FALSE;
		}
	}

	function getSubItems($unit_id) {
		$this->db->select('uc.unit_category_id, u.unit_code as unit, uc.description, ct.cost as unit_cost');
		$this->db->join('unit_category uc', 'uc.unit_category_id = ct.unit_category_id');
		$this->db->join('unit u', 'u.unit_id = uc.unit_id');
		$this->db->where('effective_date = ( SELECT MAX(effective_date) FROM costing c WHERE c.unit_category_id = ct.unit_category_id )');
		$this->db->where('u.unit_id', $unit_id);

		$query = $this->db->get("costing ct");

		return $query->result();
	}

	function getCurrentRates() {
		$this->db->select('uc.unit_category_id, u.unit_code as unit, uc.description, ct.cost as unit_cost');
		$this->db->join('unit_category uc', 'uc.unit_category_id = ct.unit_category_id');
		$this->db->join('unit u', 'u.unit_id = uc.unit_id');
		$this->db->where('effective_date = ( SELECT MAX(effective_date) FROM costing c WHERE c.unit_category_id = ct.unit_category_id )');

		$query = $this->db->get('costing ct', 5);

		if ($query) {
			return $query->result();

		}else{
			return FALSE;
		}
	}

	// get item and cost details
	function getItemPrice(){
		/*$data = $this->input->post('description');
		$this->db->select('unit.unit_code as unit, unit_category_id, unit_cost');
		$this->db->join('unit','unit_category.unit_id = unit.unit_id');
		$this->db->where('unit_category.description', $data[0]);
		$query = $this->db->get($this->table);*/

		// $data = $this->input->post('description');
		$data = $this->input->post('unit');
		$sql = "SELECT u.unit_code as unit, uc.unit_category_id, c.cost_id, c.cost as unit_cost, MAX(effective_date)
				FROM costing c
				JOIN unit_category uc ON uc.unit_category_id = c.unit_category_id
				JOIN unit u ON u.unit_id = uc.unit_id
				WHERE uc.description = ? 
				AND c.effective_date = (
					SELECT MAX(effective_date) FROM costing ct WHERE ct.unit_category_id = c.unit_category_id
					)";

		/*$sql = "SELECT u.unit_code as unit, uc.unit_category_id, c.cost_id, c.cost as unit_cost, MAX(effective_date)
				FROM costing c
				JOIN unit_category uc ON uc.unit_category_id = c.unit_category_id
				JOIN unit u ON u.unit_id = uc.unit_id";*/

		$query = $this->db->query($sql, array($data[0]));

		if($query->num_rows() > 0){
			return $query->row_array();
		}
	}

	// get items to feed typeahead
	function getItems(){
		/*$sql = "SELECT CONCAT(description,' - ',unit_cost) as description FROM unit_category";
		$query = $this->db->query($sql);
		return $query->result(); // get data for typeahead*/
		
		$sql = "SELECT CONCAT(uc.description,' - ', ct.cost) as description
				FROM costing ct
				JOIN unit_category uc ON uc.unit_category_id = ct.unit_category_id
				JOIN unit u ON u.unit_id = uc.unit_id
				WHERE effective_date = 
						(
							SELECT MAX(effective_date) FROM costing c WHERE c.unit_category_id = ct.unit_category_id
						)";

		$query = $this->db->query($sql);

		return $query->result();
	}	
}
?>