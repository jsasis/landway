<?php 
	if(!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Customer_Model extends CI_Model {
		private $table = 'customer';
		public function __construct(){
			parent::__construct();
		}

		public function create($data){
			if(isset($data['customer_id'])){
				$this->db->where('customer_id',$data['customer_id']);
				if($this->db->update($this->table,$data)){
					return true;
				}else{
					return false;
				}
			}else{
				if($this->db->insert($this->table,$data)){
					return true;
				}else{
					return false;
				}
			}
		}

		public function read($customerID = null){
			if($customerID == null){
				$this->db->order_by('customer_id','desc');
				$query = $this->db->get($this->table);
				return $query->result();
			}else{
				$this->db->where('customer_id',$customerID);
				$query = $this->db->get($this->table);
				return $query->row_array();
			}
		}

		public function delete($data){
			for($i=0; $i<sizeof($data); $i++){
				$this->db->where('customer_id',$data[$i]);
				$this->db->delete($this->table);
			}
			return true;
		}

		public function fetch($query_array, $limit, $start){

			if(strlen($query_array['search_key'])){
				$this->db->like('name', $query_array['search_key']);
				$this->db->or_like('complete_address', $query_array['search_key']);
			}

			$query = $this->db->get($this->table, $limit, $start);

			$ret['rows']= $query->result();

			if(strlen($query_array['search_key'])){
				$this->db->like('name', $query_array['search_key']);
				$this->db->or_like('complete_address', $query_array['search_key']);
			}

			$ret['num_rows'] = $this->db->count_all_results($this->table);

			return $ret;
		}

		public function getCustomers($customer_type){ //feed typeahead with customer data
			$this->db->where('customer_type', $customer_type);
			$query = $this->db->get($this->table);
			
			return $query->result();
		}

		public function searchCustomer($data){ //get customer_id. used to set hidden field value after typeahead
			$this->db->select('customer_id,complete_address');
			$this->db->where('name',$data);

			$query = $this->db->get($this->table);
			if($query->num_rows() > 0){
				return $query->row_array();
			}
		}

		/*public function recordCount(){

			return $this->db->count_all($this->table);
		}

		public function fetchUnit($limit, $start){
			$sql = 'SELECT * FROM customer ORDER BY customer_id DESC LIMIT ?,?';

			$query = $this->db->query($sql,array(intval($limit),intval($start)));

			if ($query->num_rows > 0) {
				return $query->result();
			}
		}*/

		
	}
?>