<?php
	class Waybill_Model extends CI_Model {
		private $table = 'waybill';

		public function __construct() {

			parent::__construct();
		}
		
		public function fetch($query_array, $limit, $start){
			$this->db->select('w.waybill_number, c1.name as consignee, c2.name as consignor, status, is_backload, notes, total, transaction_date, t.plate_number, mw.delivery_status, m.manifest_number');
			$this->db->select_sum('p.amount','payment');
			$this->db->join('customer c1','c1.customer_id = w.consignee');
			$this->db->join('customer c2','c2.customer_id = w.consignor');
			$this->db->join('payment p'  ,'p.waybill_number = w.waybill_number','left');
			$this->db->join('manifest_waybill mw', 'mw.waybill_number = w.waybill_number', 'left');
			$this->db->join('manifest m', 'm.manifest_number = mw.manifest_number', 'left');
			$this->db->join('truck t', 't.truck_id = m.truck_id', 'left');
			$this->db->group_by('w.waybill_number');
			$this->db->order_by('w.waybill_number DESC, status ASC');

			if(strlen($query_array['search_key'])){
				$this->db->like('w.waybill_number', $query_array['search_key']);
				$this->db->or_like('c1.name', $query_array['search_key']);
				$this->db->or_like('c2.name', $query_array['search_key']);
				$this->db->or_like('w.status', $query_array['search_key']);
				$this->db->or_like('notes', $query_array['search_key']);
				$this->db->or_like('transaction_date', $query_array['search_key']);
			}
			$query = $this->db->get('waybill w', $limit, $start);

			$ret['rows']	= $query->result();

			if(strlen($query_array['search_key'])){
				$this->db->join('customer c1','c1.customer_id = w.consignee');
				$this->db->join('customer c2','c2.customer_id = w.consignor');
				$this->db->join('payment p'  ,'p.waybill_number = w.waybill_number','left');
				$this->db->join('manifest_waybill mw', 'mw.waybill_number = w.waybill_number', 'left');
				$this->db->join('manifest m', 'm.manifest_number = mw.manifest_number', 'left');
				$this->db->join('truck t', 't.truck_id = m.truck_id', 'left');
				$this->db->like('w.waybill_number', $query_array['search_key']);
				$this->db->or_like('c1.name', $query_array['search_key']);
				$this->db->or_like('c2.name', $query_array['search_key']);
				$this->db->or_like('w.status', $query_array['search_key']);
				$this->db->or_like('notes', $query_array['search_key']);
				$this->db->or_like('transaction_date', $query_array['search_key']);
			}

			$ret['num_rows'] = $this->db->count_all_results('waybill w');
			return $ret;
		}

		public function create($data){
			if(!isset($data['waybill_number'])){ // WAYBILL# DOESN'T EXIST, WE ARE CREATING A NEW WAYBILL
				$this->db->trans_start();
					// Insert Waybill Details
					$this->db->insert($this->table, $data['waybill_data']);
					$waybill_number = $this->db->insert_id();
					// Insert Waybill Items
					for($j=0; $j<count($data['waybill_items']['quantity']); $j++){
					if($data['waybill_items']['cost_id'][$j] == NULL ){

						$cost_id = NULL;
					}else{

						$cost_id = $data['waybill_items']['cost_id'][$j];
					}
					$insert_data[] = array(
							'waybill_number' 	=> 	$waybill_number,
							'quantity' 			=> 	$data['waybill_items']['quantity'][$j],
							'unit'				=>	$data['waybill_items']['unit'][$j],
							'cost'				=>	$data['waybill_items']['unit_price'][$j],
							'cost_id'			=>	$cost_id,
							'item_description'	=>	$data['waybill_items']['item_description'][$j],
							'sub_total'			=>  $data['waybill_items']['sub_total'][$j]
						);
					}
					$this->db->insert_batch('waybill_items', $insert_data);
					// Add Payment
					if($data['waybill_data']['payment_terms'] == 'prepaid'){
						$data	= array(
								'waybill_number'	=> $waybill_number,
								'payment_terms'		=> $data['waybill_data']['payment_terms'],
								'amount'			=> $this->input->post('amount'),
								'date'				=> date('Y-m-d H:i:s')
							);
						
						$this->addPayment($data);
					}
				$this->db->trans_complete();

				if(!$this->db->trans_status() == FALSE){
					return TRUE;
				}else{
					return FALSE;
				}

			}else{ // WAYBILL # EXISTS, WE ARE UPDATING EXISTING WAYBILL
				$waybill_number = $data['waybill_number'];
				$this->db->trans_start();

				$delete = "DELETE FROM waybill_items WHERE waybill_number = ?";

				$this->db->update($this->table, $data['waybill_data'], array('waybill_number'=>$waybill_number));
				$this->db->query($delete, array($waybill_number));
				
				for($j=0; $j<count($data['waybill_items']['quantity']); $j++) {

					if($data['waybill_items']['cost_id'][$j] == NULL ){
						$cost_id = NULL;
					}else{
						$cost_id = $data['waybill_items']['cost_id'][$j];
					}

					$waybill_items[] = array(	
						'waybill_number' 	=> 	$waybill_number,
						'quantity' 			=> 	$data['waybill_items']['quantity'][$j],
						'unit'				=>	$data['waybill_items']['unit'][$j],
						'cost'				=>	$data['waybill_items']['unit_price'][$j],
						'cost_id'			=>	$cost_id,
						'item_description'	=>	$data['waybill_items']['item_description'][$j],
						'sub_total'			=>  $data['waybill_items']['sub_total'][$j]
					);
				}

				// Re-insert waybill items
				$this->db->insert_batch('waybill_items', $waybill_items);
				
				$this->db->trans_complete();

				if($this->db->trans_status()){
					return TRUE;
				}else{
					log_message('error',"DB Error Occured");
					return FALSE;
				}
			}	
		}

		public function addPayment($data){
			$this->load->model('payment_model');
			if($this->payment_model->create($data)){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function delete($data){
			for($i=0; $i<sizeof($data); $i++){
				$this->db->where('waybill_number',$data[$i]);
				$this->db->delete($this->table);
			}
			return TRUE;
		}

		public function getDetails($waybill_number, $is_by_batch = TRUE, $return_object = FALSE){
			$this->db->select("waybill.waybill_number,c1.customer_id as consignee_id,c1.name as consignee, waybill.status, t.plate_number as truck, waybill.dr_number, waybill.is_backload, waybill.notes,
								c1.complete_address as address1,c2.customer_id as consignor_id,c2.name as consignor,c2.complete_address as address2, payment_terms, mw.delivery_status,
			 					transaction_date, total, CONCAT(ua.first_name,' ', ua.last_name) as processed_by ", FALSE);
			$this->db->join('customer c1','waybill.consignee = c1.customer_id');
			$this->db->join('customer c2','waybill.consignor = c2.customer_id');
			$this->db->join('manifest_waybill mw', 'mw.waybill_number = waybill.waybill_number', 'LEFT');
			$this->db->join('manifest m', 'm.manifest_number = mw.manifest_number', 'LEFT');
			$this->db->join('truck t', 't.truck_id = m.truck_id', 'LEFT');
			$this->db->join('user_account ua', 'ua.user_id = waybill.processed_by');
			
			if(!$is_by_batch){
				$this->db->where('waybill.waybill_number', $waybill_number);
			}else{
				$this->db->where_in('waybill.waybill_number', $waybill_number);
				$this->db->order_by('waybill.waybill_number DESC');
			}

			$query = $this->db->get($this->table);

			if($query){
				if(!$return_object){

					return $query->result_array();
				}else{

					return $query->row_array();
				}
			}else{

				return FALSE;
			}
		}

		public function getWaybill(){
			$sql = "SELECT waybill_number, c1.name as consignee, c2.name as consignor, payment_terms, total, transaction_date
					FROM waybill
					JOIN customer c1 ON waybill.consignee = c1.customer_id
					JOIN customer c2 ON waybill.consignor = c2.customer_id
					ORDER BY waybill_number DESC";

			$query = $this->db->query($sql);

			return $query->result();
		}

		public function getItems($waybill_number, $is_by_batch){
			$this->db->SELECT(" wi.waybill_number,(CASE WHEN u.unit_code IS NULL THEN unit ELSE u.unit_code END) as unit_code, uc.description, wi.cost_id, (CASE WHEN c.cost_id IS NOT NULL AND wi.cost IS NOT NULL THEN wi.cost WHEN c.cost_id IS NULL AND wi.cost IS NOT NULL THEN wi.cost ELSE c.cost END) as unit_cost, 
								quantity, item_description, wi.sub_total, w.transaction_date", FALSE);
			$this->db->JOIN('waybill w','w.waybill_number = wi.waybill_number');
			$this->db->JOIN('costing c','c.cost_id = wi.cost_id', "LEFT");
			$this->db->JOIN('unit_category uc','uc.unit_category_id = c.unit_category_id', "LEFT");
			$this->db->JOIN('unit u','u.unit_id = uc.unit_id', "LEFT");
			$this->db->ORDER_BY('wi.waybill_number DESC');

			if(!$is_by_batch){
				$this->db->where('w.waybill_number', $waybill_number);
			}else{
				$this->db->where_in('w.waybill_number', $waybill_number);
			}

			$query = $this->db->get('waybill_items wi');
		
			return ($query) ?  $query->result() : FALSE;
		}

		public function getReceived(){
			$sql = "SELECT waybill_number,c1.name as consignee,c2.name as consignor, transaction_date
					FROM waybill 
					JOIN customer c1 ON waybill.consignee = c1.customer_id
					JOIN customer c2 ON waybill.consignor = c2.customer_id
					WHERE status = 'Received'
					ORDER BY waybill_number DESC
					LIMIT 6";
			
			$query = $this->db->query($sql);
		
			return $query->result();
		}
		
		public function getUnloaded($limit, $start){

			$sql = "SELECT waybill_number,c1.name as consignee,c2.name as consignor, transaction_date
					FROM waybill 
					JOIN customer c1 ON waybill.consignee = c1.customer_id
					JOIN customer c2 ON waybill.consignor = c2.customer_id
					WHERE status = 'Received'
					ORDER BY waybill_number DESC
					LIMIT ?,?";
			
			$query = $this->db->query($sql, array(intval($limit), intval($start)));
			$ret['rows']	 = $query->result();
			
			$this->db->where('status', 'Received');
			$ret['num_rows'] = $this->db->count_all_results($this->table);

			return $ret;
		}

		public function getUnloadedTypeAhead(){
			$this->db->select('waybill_number');
			$this->db->where('status', 'Received');
			$this->db->order_by('waybill_number DESC');
			
			$query = $this->db->get($this->table);

			return $query->result();
		}

		public function getUncollected($limit, $start){
			$sql = "SELECT * FROM
					(
					    SELECT w.waybill_number, c1.name as consignee, c2.name as consignor, w.status, w.transaction_date, w.total, w.total - COALESCE(SUM(p.amount),0) as balance
						FROM waybill w
						JOIN manifest_waybill mw ON mw.waybill_number = w.waybill_number
						JOIN customer c1 ON c1.customer_id = w.consignee
						JOIN customer c2 ON c2.customer_id = w.consignor
						LEFT JOIN payment p ON p.waybill_number = w.waybill_number
						GROUP BY mw.waybill_number
					) AS w
					WHERE balance > 0 OR balance IS NULL
					LIMIT ?, ?";

			$query = $this->db->query($sql, array(intval($start), intval($limit)));

			if($query){
				return $query->result();
			}else{
				return FALSE;
			}
		}

		public function getPrepaid($limit, $start){
			$sql = "SELECT w.waybill_number, c1.name as consignee, c2.name as consignor, w.status, w.payment_terms, w.transaction_date, w.total
					FROM waybill w
					JOIN manifest_waybill mw ON mw.waybill_number = w.waybill_number
					JOIN customer c1 ON c1.customer_id = w.consignee
					JOIN customer c2 ON c2.customer_id = w.consignor
					WHERE w.payment_terms = 'prepaid'
					LIMIT ?, ?";

			$query = $this->db->query($sql, array(intval($start), intval($limit)));

			if($query){
				return $query->result();
			}else{
				return FALSE;
			}
		}

		public function getBackload($limit, $start){
			$sql = "SELECT w.waybill_number, c1.name as consignee, c2.name as consignor, w.status, w.transaction_date, w.total
					FROM waybill w
					JOIN customer c1 ON c1.customer_id = w.consignee
					JOIN customer c2 ON c2.customer_id = w.consignor
					WHERE is_backload = true
					LIMIT ?, ?";

			$query = $this->db->query($sql, array(intval($start), intval($limit)));

			if($query){
				return $query->result();
			}else{
				return FALSE;
			}
		}

		public function countUncollected(){
			$sql = "SELECT COUNT(*) as total FROM
					(
					    SELECT w.waybill_number, c1.name as consignee, c2.name as consignor, w.status, w.transaction_date, w.total, w.total - COALESCE(SUM(p.amount),0) as balance
						FROM waybill w
						JOIN manifest_waybill mw ON mw.waybill_number = w.waybill_number
						JOIN customer c1 		 ON c1.customer_id = w.consignee
						JOIN customer c2 		 ON c2.customer_id = w.consignor
						LEFT JOIN payment p 	 ON p.waybill_number = w.waybill_number
						GROUP BY mw.waybill_number
					) AS w
					WHERE balance > 0 OR balance IS NULL";

			$query = $this->db->query($sql);

			if($query){
				return $query->row()->total;
			}else{
				return FALSE;
			}
		}

		public function countPrepaid(){
			$sql = "SELECT COUNT(*) as total FROM waybill WHERE payment_terms = 'prepaid' ";

			$query = $this->db->query($sql);

			return $query->row_array();
		}

		public function countReceived(){
			$this->db->where('status', 'Received');

			return $this->db->count_all_results($this->table);
		}

		public function countBackload(){
			$sql = "SELECT COUNT(*) as total FROM waybill WHERE is_backload = true ";

			$query = $this->db->query($sql);

			return $query->row()->total;
		}

		public function computePrepaid($data = '') {
			
			$this->db->select('(SELECT SUM(p.amount)) as total_prepaid', FALSE);
			$this->db->join('payment p', 'p.waybill_number = w.waybill_number');
			$this->db->where('w.payment_terms', 'prepaid');

			if($data)
			{
				$this->db->where('date >=', $data['start_date']);
				$this->db->where('date <=', $data['end_date']);
			}
			
			$query = $this->db->get('waybill w');
			
			if($query) {
				return $query->row()->total_prepaid;

			} else {
				return FALSE;
			}	
		}

		public function computeReceived($data = '') {
			$this->db->select('(SELECT SUM(total)) as total_received', FALSE);
			$this->db->where('status', 'received');

			if($data)
			{
				$this->db->where('transaction_date >=', $data['start_date']);
				$this->db->where('transaction_date <=', $data['end_date']);
			}
			
			$query = $this->db->get('waybill w');
			
			if($query) {
				return $query->row()->total_received;

			} else {
				return FALSE;
			}	
		}

		public function computeBackload($data = '') {
			$this->db->select('(SELECT SUM(total)) as total_backload', FALSE);
			$this->db->where('is_backload', 1);

			if($data)
			{
				$this->db->where('transaction_date >=', $data['start_date']);
				$this->db->where('transaction_date <=', $data['end_date']);
			}
			
			$query = $this->db->get('waybill w');
			
			if($query) {
				return $query->row()->total_backload;

			} else {
				return FALSE;
			}	
		}

		public function getAmountPaid($waybill_number){
			$sql = "SELECT SUM(amount) as amount FROM payment WHERE waybill_number = ?";

			$query = $this->db->query($sql, array($waybill_number));

			if($query){
				return $query->row();
			}else{
				return FALSE;
			}
		}




		/*******************
				POPULATE DB WITH SAMPLE DATA
				********************/
		public function insertSampleData(){
			$i = 1;
			while($i <= 300){
				$data = array(
					'consignee' => $this->randomConsignee(),
					'consignor' => $this->randomConsignor(),
					'status'	=> $this->randomStatus(),
					'payment_terms' => $this->randomTerms(),
					'transaction_date' => date('Y-m-d'),
					'total'	=> rand(0, 1000),
					'processed_by' => 1
				);
				$this->db->insert($this->table, $data);

				$i++;
			}
			
			return TRUE;
		}

		public function randomTerms(){
			$terms = array('prepaid'=>'prepaid','collect'=>'collect');
			return array_rand($terms, 1);
		}

		public function randomConsignee(){
			$rand = rand(9, 13);
			return $rand;
		}

		public function randomConsignor(){
			$rand = rand(14, 16);

			return $rand;
		}
		
		public function randomStatus(){
			$status = array('Received'=>'Received');

			return array_rand($status, 1);
		}

		public function updateDeliveryStatus($data){
			$sql = "UPDATE manifest_waybill SET delivery_status = ? WHERE waybill_number = ? AND manifest_number = ?";
			$query = $this->db->query($sql, array($data['delivery_status'], $data['waybill_number'], $data['manifest_number']));

			if($query){
				return TRUE;
			} else {
				return FALSE;
			}
		}

		public function getManifestNumber($waybill_number){
			$sql = "SELECT manifest_number FROM manifest_waybill WHERE waybill_number = ?";
			$query = $this->db->query($sql, array($waybill_number));

			if($query->num_rows > 0) {
				return $query->row()->manifest_number;
			} else {
				return FALSE;
			}
		}

	}
?>