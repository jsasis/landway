<?php
	
	class Unit_Model extends CI_Model{

		public function __construct(){
		
			parent::__construct();
		}

		public function create($data = null) {
			if(isset($data['unit_id'])){
				$this->db->where('unit_id',$data['unit_id']);
				if($this->db->update('unit',$data)){
					return true;
				}else{
					return false;
				}
			}else{
				if($this->db->insert('unit',$data)){
					return true;
				}else{
					return false;
				}
			}
		}

		public function read($unitID = null) {
			if($unitID == null) {
				$this->db->order_by('unit_id','desc');
				$query = $this->db->get('unit');
				
				return ($query) ? $query->result() : FALSE;

			} else {
				$this->db->where('unit_id',$unitID);
				$query = $this->db->get('unit');

				return ($query) ? $query->row_array() : FALSE;
			}
		}

		public function deleteAll($data) {
			for($i=0; $i<sizeof($data); $i++){

				$this->db->where('unit_id',$data[$i]);
				$this->db->delete('unit');
			}
			return true;
		}

		public function recordCount() {
			
			return $this->db->count_all('unit');
		}

		public function fetchUnit($limit, $start) {
		  $sql = "SELECT unit_id, unit_code, description from unit ORDER BY unit_id desc  limit ?,?";
		  $query = $this->db->query($sql, array(intval($limit), intval($start)));

		  if ($query->num_rows > 0) {
		    return $query->result();
		  }
		  return false;
		}

		public function search() {
			$data = $this->input->post('search');
			$this->db->like('description',$data);
			$this->db->limit('5');
			$query = $this->db->get('unit');

			if($query->num_rows() > 0){
				return $query->result();;
			}else{
				return false;
			}
		}

	}
?>