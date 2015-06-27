<?php
	class Manifest_model extends CI_Model {
		private $table = 'manifest';

		function __construct() {
			parent::__construct();
			//date_default_timezone_set('Asia/Hong_Kong');
		}

		function create($data){
			if(!isset($data['manifest_number'])){
				$insert = $this->db->insert($this->table, $data['manifest_data']);	// add new manifest
				$insert_id = $this->db->insert_id();
				if($insert){
					return $insert_id;
				}else{
					return FALSE;
				}
			}else{
				$this->db->where('manifest_number', $data['manifest_number']);
				$update = $this->db->update($this->table, $data['manifest_data']);

				if($update){
					return TRUE;
				}else{
					return FALSE;
				}
			}
		}

		function update($data){
			foreach($data['waybills'] as $row){
				$insert_waybills[] 			= array(
					'manifest_number'	=> $data['manifest_number'],
					'waybill_number' 	=> $row
				);

				$update_waybills[] 		= array(
					'status' 			=> 'Loaded',
					'waybill_number'	=> $row		
				);
			}

			$this->db->trans_start();

				$this->db->insert_batch('manifest_waybill', $insert_waybills); // load waybills to manifest
				$this->db->update_batch('waybill', $update_waybills, 'waybill_number'); //update waybill status

			$this->db->trans_complete();

			if($this->db->trans_status()){
				
				return TRUE;

			}else{

				return FALSE;
			}
		}

		function unload($data){
			$this->db->trans_start();

			$this->db->where('waybill_number', $data);
			$this->db->delete('manifest_waybill');

			$this->db->where('waybill_number', $data);
			$this->db->update('waybill', array('status'=>'Received'));

			$this->db->trans_complete();

			if($this->db->trans_status()){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		function delete($data){
			for($i=0; $i<sizeof($data); $i++){
				$this->db->where('manifest_number', $data[$i]);
				$this->db->delete($this->table);
			}
		}
		
		function fetch($query_array, $limit, $start, $truck_id = NULL) {
			$this->db->select("CONCAT(day(date),month(date),'-', SUBSTRING_INDEX(t.plate_number, ' ', 1), manifest_number) AS alpha, manifest_number, t.plate_number, driver, trip_to, date", FALSE);
			$this->db->join('truck t', 't.truck_id = manifest.truck_id');
			$this->db->order_by('manifest_number DESC');
			if($truck_id) $this->db->where('t.truck_id', $truck_id);

			if(strlen($query_array['search_key'])){
				$this->db->like('manifest_number', $query_array['search_key']);
				$this->db->or_like('t.plate_number', $query_array['search_key']);
				$this->db->or_like('trip_to', $query_array['search_key']);
				$this->db->or_like('date', $query_array['search_key']);
			}
			
			$query = $this->db->get('manifest', $limit, $start);
			$ret['rows']	= $query->result();
			
			if(strlen($query_array['search_key'])){
				$this->db->join('truck t', 't.truck_id = manifest.truck_id');
				$this->db->like('manifest_number', $query_array['search_key']);
				$this->db->or_like('t.plate_number', $query_array['search_key']);
				$this->db->or_like('trip_to', $query_array['search_key']);
				$this->db->or_like('date', $query_array['search_key']);
			}

			$this->db->select("CONCAT(day(date),month(date),'-', SUBSTRING_INDEX(t.plate_number, ' ', 1), manifest_number) AS alpha, manifest_number, t.plate_number, driver, trip_to, date", FALSE);
			$this->db->join('truck tr', 'tr.truck_id = manifest.truck_id');
			$this->db->order_by('manifest_number DESC');
			if($truck_id) $this->db->where('tr.truck_id', $truck_id);

			$ret['num_rows'] = $this->db->count_all_results($this->table);
			
			return $ret;
		}

		function getManifest($manifest_number){
			$sql = "SELECT CONCAT(day(date), month(date),'-',SUBSTRING_INDEX(t.plate_number, ' ', 1),manifest_number) AS alpha, manifest_number, t.truck_id as truck_id, t.plate_number, driver, trip_to, date
					, CONCAT(ua.first_name,' ', ua.last_name) as processed_by
					FROM manifest 
					JOIN truck t ON t.truck_id = manifest.truck_id 
					JOIN user_account ua ON ua.user_id = manifest.processed_by
					WHERE manifest_number = ? ";
					
			$query = $this->db->query($sql, array($manifest_number));

			if ($query->num_rows > 0) {
				return $query->row_array();
			}else{
				return FALSE;
			}
		}

		function getManifestWaybills($manifest_number, $collections = NULL, $waybill_number = NULL, $loaded_view = NULL){
			// STANDARD VIEW
			if(!$collections && !$loaded_view) {
				$sql = "SELECT IFNULL(w.waybill_number, 'TOTAL') AS waybill_number,c1.name as consignee,c2.name as consignor, w.prepaid, w.collect,
				(
					CASE WHEN c.cost_id IS NULL THEN GROUP_CONCAT(wi.quantity,wi.unit, ' ', wi.item_description) ELSE
					 GROUP_CONCAT(wi.quantity,u.unit_code,' ', wi.item_description) END
				 ) as remarks
						FROM
							( 
						        SELECT w.waybill_number,
						      	SUM(IF(w.payment_terms = 'prepaid', w.total, NULL)) as prepaid,
						      	SUM(IF(w.payment_terms = 'collect', w.total, NULL)) as collect
						      	FROM waybill w
						      	JOIN manifest_waybill mw ON mw.waybill_number = w.waybill_number
						      	JOIN manifest m ON m.manifest_number = mw.manifest_number
						        WHERE m.manifest_number = ?
						     	GROUP BY w.waybill_number WITH ROLLUP
						    ) AS w
						LEFT JOIN waybill wb ON wb.waybill_number = w.waybill_number
						LEFT JOIN customer c1 ON c1.customer_id = wb.consignee
						LEFT JOIN customer c2 ON c2.customer_id = wb.consignor
						LEFT JOIN waybill_items wi ON wi.waybill_number = wb.waybill_number
						LEFT JOIN costing c ON c.cost_id = wi.cost_id
						LEFT JOIN unit_category uc ON uc.unit_category_id = c.unit_category_id
						LEFT JOIN unit u ON u.unit_id = uc.unit_id
						GROUP BY waybill_number ";

			// COLLECTIONS VIEW WITHOUT SEARCH
			} elseif($collections && !$waybill_number && !$loaded_view) {
				$sql = "SELECT IFNULL(mw.waybill_number, 'TOTAL') AS waybill_number, c1.name as consignee, c2.name as consignor,
									  mw.prepaid, mw.collect, mw.total_payment as total_payment,
									  mw.total_due as total_due,(mw.total_due - mw.total_payment) as balance
						FROM
							( 
						        SELECT mw.waybill_number, COALESCE(SUM(p.amount), 0) as total_payment, SUM(w.total) as total_due,
						      	/*SUM(IF(p.payment_terms = 'prepaid', p.amount, 0)) as prepaid,
						      	SUM(IF(p.payment_terms = 'collect', p.amount, 0)) as collect*/
						      	SUM(IF(w.payment_terms = 'prepaid', w.total, 0)) as prepaid,
						      	SUM(IF(w.payment_terms = 'collect', w.total, 0)) as collect
						      	FROM manifest_waybill mw
						      	JOIN waybill w ON w.waybill_number = mw.waybill_number
						      	LEFT JOIN payment p ON p.waybill_number = w.waybill_number
						        WHERE mw.manifest_number = ?
						     	GROUP BY mw.waybill_number WITH ROLLUP
						    ) AS mw
						LEFT JOIN waybill wb ON wb.waybill_number = mw.waybill_number
						LEFT JOIN customer c1 ON c1.customer_id = wb.consignee
						LEFT JOIN customer c2 ON c2.customer_id = wb.consignor
                        ORDER BY mw.waybill_number DESC";

            // COLLECTIONS VIEW WITH SEARCH
			} elseif($collections && $waybill_number  && !$loaded_view) { 
				$sql = "SELECT IFNULL(mw.waybill_number, 'TOTAL') AS waybill_number, c1.name as consignee, c2.name as consignor,
									  mw.prepaid, mw.collect, mw.total_payment as total_payment,
									  mw.total_due as total_due,(mw.total_due - mw.total_payment) as balance
						FROM
							( 
						        SELECT mw.waybill_number, COALESCE(SUM(p.amount), 0) as total_payment, SUM(w.total) as total_due,
						      	SUM(IF(p.payment_terms = 'prepaid', p.amount, 0)) as prepaid,
						      	SUM(IF(p.payment_terms = 'collect', p.amount, 0)) as collect
						      	FROM manifest_waybill mw
						      	JOIN waybill w ON w.waybill_number = mw.waybill_number
						      	LEFT JOIN payment p ON p.waybill_number = w.waybill_number
						        WHERE mw.manifest_number = ? AND w.waybill_number = ?
						     	GROUP BY mw.waybill_number WITH ROLLUP
						    ) AS mw
						LEFT JOIN waybill wb ON wb.waybill_number = mw.waybill_number
						LEFT JOIN customer c1 ON c1.customer_id = wb.consignee
						LEFT JOIN customer c2 ON c2.customer_id = wb.consignor
                        ORDER BY mw.waybill_number DESC";

			} else {
				$sql = "SELECT w.waybill_number, c1.name as consignee,c2.name as consignor
						FROM waybill w
						JOIN customer c1 ON c1.customer_id = w.consignee
						JOIN customer c2 ON c2.customer_id = w.consignor
						JOIN manifest_waybill mw ON mw.waybill_number = w.waybill_number
						WHERE manifest_number = ?";
			}

			($waybill_number) ? $query = $this->db->query($sql, array($manifest_number, $waybill_number)) : $query = $this->db->query($sql, array($manifest_number));

			return ($query) ?  $query->result() : FALSE;
		}

		function getWaybillCollection($manifest_number = NULL){
			$sql = "SELECT DISTINCT mw.waybill_number
			      	FROM manifest_waybill mw
			      	JOIN waybill w  ON w.waybill_number = mw.waybill_number
			      	LEFT JOIN payment p ON p.waybill_number = w.waybill_number
			        WHERE mw.manifest_number = ?";

			$query = $this->db->query($sql, array($manifest_number));

			return ($query) ?  $query->result() : FALSE;
		}

		function getTotal($manifest_number){
			$sql = "SELECT IFNULL(w.waybill_number, 'TOTAL') AS waybill_number,c1.name as consignee,c2.name as consignor, w.prepaid, w.collect, GROUP_CONCAT(wi.quantity,' ', wi.item_description) as remarks
					FROM
						( 
					        SELECT w.waybill_number,
					      	SUM(IF(w.payment_terms = 'prepaid', w.total, NULL)) as prepaid,
					      	SUM(IF(w.payment_terms = 'collect', w.total, NULL)) as collect
					      	FROM waybill w
					        JOIN manifest_waybill mw ON mw.waybill_number = w.waybill_number
					        WHERE mw.manifest_number = ?
					     	GROUP BY w.waybill_number WITH ROLLUP
					    ) AS w
					LEFT JOIN waybill wb 		ON wb.waybill_number = w.waybill_number
					LEFT JOIN customer c1 		ON c1.customer_id = wb.consignee
					LEFT JOIN customer c2 		ON c2.customer_id = wb.consignor
					LEFT JOIN waybill_items wi 	ON wi.waybill_number = wb.waybill_number
					GROUP BY waybill_number";

			$query = $this->db->query($sql, array($manifest_number));

			if($query->num_rows() > 1){
				return $query->result();
			}
		}

		function getGrandTotal($manifest_number){
			$sql = "SELECT SUM(w.total) as grand_total FROM manifest_waybill mw 
					JOIN waybill w 
					ON w.waybill_number = mw.waybill_number
					WHERE manifest_number = ?";
			$query = $this->db->query($sql, array($manifest_number));
			return $query->row_array();
		}

		function getTotalPayments($manifest_number){
			$sql = "SELECT SUM( p.amount ) as payments
					FROM payment p
					JOIN waybill w ON w.waybill_number = p.waybill_number
					JOIN manifest_waybill mw ON mw.waybill_number = w.waybill_number
					WHERE mw.manifest_number = ?";
			$query = $this->db->query($sql, array($manifest_number));
			return $query->row_array();
		}

	}
?> 