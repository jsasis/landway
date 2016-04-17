<?php
	class Payment_model extends CI_Model {
		
		private $table = 'payment';

		public function __construct() {
			parent::__construct();
		}

		public function create($data){
			if($this->db->insert($this->table, $data)){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function delete($data){
			for($i=0; $i<sizeof($data); $i++){
				$this->db->where('payment_id', $data[$i]);
				$this->db->delete($this->table);
			}

			return TRUE;
		}

		public function recordCount(){
			return $this->db->count_all($this->table);
		}

		public function fetch($limit, $start) {
			$this->db->limit($limit, $start);
			$query = $this->db->get($this->table);
			
			if ($query->num_rows > 0) {
				return $query->result();
			}else{
				return FALSE;
			}
		}

		public function getPayment($waybill_number = NULL){
			$sql = "SELECT payment_id, w.waybill_number, payment.payment_terms, amount, date
					FROM payment 
					JOIN waybill w 
					ON w.waybill_number = payment.waybill_number
					WHERE payment.waybill_number = ?
					ORDER BY payment_terms, payment_id DESC";
			$query = $this->db->query($sql, array($waybill_number));

			if($query->num_rows > 0){
				return $query->result();
			}else{
				return FALSE;
			}
		}
	}
?>