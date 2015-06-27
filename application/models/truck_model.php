<?php
	
class Truck_Model extends CI_Model{

	private $table = 'truck';

	function __construct(){
		parent::__construct();
	}

	function create($data = null){
		if(isset($data['truck_id'])){
			$this->db->where('truck_id',$data['truck_id']);
			if($this->db->update($this->table, $data)){
				return true;
			}else{
				return false;
			}
		}else{
			if($this->db->insert($this->table, $data)){
				return true;
			}else{
				return false;
			}
		}
	}

	function read($truckID = null){
		if($truckID == null){
			$this->db->order_by('truck_id','desc');
			$query = $this->db->get($this->table);

			return $query->result();
		}else{
			$this->db->where('truck_id', $truckID);
			$query = $this->db->get($this->table);

			return $query->row_array();
		}
	}

	function delete($data){
		for($i=0; $i<sizeof($data); $i++){
			$this->db->where('truck_id',$data[$i]);
			$this->db->delete($this->table);
		}

		return true;
	}

	function recordCount(){

		return $this->db->count_all($this->table);
	}

	function fetch($limit, $start) {
	  $sql = "SELECT * FROM truck ORDER BY truck_id DESC  LIMIT ?, ?";
	  $query = $this->db->query($sql, array(intval($limit), intval($start)));

	  if ($query->num_rows > 0) {
	    return $query->result();
	  }
	  return false;
	}

	function getTrucks(){
		$this->db->select('truck_id, plate_number');
		$query = $this->db->get($this->table);

		if($query){
			return $query->result()	;

		}else{
			return FALSE;
		}
	}

	function generate_report($data){
		if($data['truck_id'] == 'all'){
			$sql = "SELECT IFNULL(i.plate_number, 'TOTAL') as truck, i.amount as total
					FROM ( 
						SELECT t.plate_number, SUM(p.amount) as amount
					    FROM truck t
						LEFT JOIN manifest m ON m.truck_id = t.truck_id
						LEFT JOIN manifest_waybill mw ON mw.manifest_number = m.manifest_number
						LEFT JOIN waybill w ON w.waybill_number = mw.waybill_number
						LEFT JOIN payment p ON p.waybill_number = w.waybill_number
						WHERE p.date >= ? AND p.date <= ?
						GROUP BY t.plate_number WITH ROLLUP
					    ) as i";
		
			$query = $this->db->query($sql, array($data['start_date'], $data['end_date']));

		}else{
			$sql = "SELECT IFNULL(i.plate_number, 'TOTAL') as truck, i.amount as total
					FROM ( 
						SELECT t.plate_number, SUM(p.amount) as amount
					    FROM truck t
						LEFT JOIN manifest m ON m.truck_id = t.truck_id
						LEFT JOIN manifest_waybill mw ON mw.manifest_number = m.manifest_number
						LEFT JOIN waybill w ON w.waybill_number = mw.waybill_number
						LEFT JOIN payment p ON p.waybill_number = w.waybill_number
						WHERE p.date >= ? AND p.date <= ? AND t.truck_id = ?
						GROUP BY t.plate_number WITH ROLLUP
					    ) as i";

			$query = $this->db->query($sql, array($data['start_date'], $data['end_date'], $data['truck_id']));
		}

		return($query->num_rows > 0) ? $query->result() : FALSE;
	}

	function generate_report_annual($data) {
		$sql = "SELECT IFNULL(totals.plate_number, 'Total') as truck, totals.January, totals.February, totals.March, totals.April, totals.May, 
						totals.June, totals.July, totals.August, totals.September, totals.October, totals.November, totals.December,
						(totals.January + totals.February + totals.March + totals.April + totals.May + totals.June + totals.July + totals.August + totals.September + totals.October + 
						totals.November + totals.December) as total
				FROM (
				    	SELECT t.plate_number,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','01-01 00:00:00') AND CONCAT({$data},'-','01-31 23:59:59'), p.amount, 0)) as January,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','02-01 00:00:00') AND CONCAT({$data},'-','02-31 23:59:59'), p.amount, 0)) as February,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','03-01 00:00:00') AND CONCAT({$data},'-','03-31 23:59:59'), p.amount, 0)) as March,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','04-01 00:00:00') AND CONCAT({$data},'-','04-31 23:59:59'), p.amount, 0)) as April,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','05-01 00:00:00') AND CONCAT({$data},'-','05-31 23:59:59'), p.amount, 0)) as May,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','06-01 00:00:00') AND CONCAT({$data},'-','06-31 23:59:59'), p.amount, 0)) as June,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','07-01 00:00:00') AND CONCAT({$data},'-','07-31 23:59:59'), p.amount, 0)) as July,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','08-01 00:00:00') AND CONCAT({$data},'-','08-31 23:59:59'), p.amount, 0)) as August,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','09-01 00:00:00') AND CONCAT({$data},'-','09-31 23:59:59'), p.amount, 0)) as September,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','10-01 00:00:00') AND CONCAT({$data},'-','10-31 23:59:59'), p.amount, 0)) as October,
				        SUM(IF(p.date BETWEEN CONCAT({$data},'-','11-01 00:00:00') AND CONCAT({$data},'-','11-31 23:59:59'), p.amount, 0)) as November,
				    	SUM(IF(p.date BETWEEN CONCAT({$data},'-','12-01 00:00:00') AND CONCAT({$data},'-','12-31 23:59:59'), p.amount, 0)) as December
				    	FROM payment p 
				    	JOIN waybill w ON w.waybill_number = p.waybill_number 
				    	JOIN manifest_waybill mw ON mw.waybill_number = w.waybill_number 
				    	JOIN manifest m ON m.manifest_number = mw.manifest_number 
				    	RIGHT JOIN truck t ON t.truck_id = m.truck_id 
				    	GROUP BY t.plate_number WITH ROLLUP
				    ) as totals";

		$query = $this->db->query($sql);

		return($query->num_rows > 0 ) ? $query->result() : FALSE;
	}
}
?>