<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
	class Waybill extends CI_Controller {

		public function __construct() {
			parent::__construct();

			if(!$this->session->userdata('logged_in')){
				return redirect('user');
			}

			$libraries = array(
				'pagination',
				'session'
			);
			$models = array(
				'waybill_model',
				'unit_category_model'
			);

			$this->load->library($libraries);
			$this->load->model($models);	
		}

		public function index(){
			$this->show();
		}

		public function insertSampleData(){

			$this->waybill_model->insertSampleData();
		}

		public function show($query_id = 0){
			$this->noCache();
			$this->input->load_query($query_id);
			$query_array = array(
				'search_key' => $this->input->get('search_key')
			);

			$config = array();
			$config['base_url'] = base_url()."waybill/show/$query_id";
			$config['per_page'] = 50;
			$config['uri_segment'] = 4;
			$page 	= ($this->uri->segment(4)) ? $this->uri->segment(4) : 0 ;

			$result = $this->waybill_model->fetch($query_array, $config['per_page'], $page);
			$config['total_rows'] = $result['num_rows'];

			$this->pagination->initialize($config);

			$data['links'] 	= $this->pagination->create_links();
			$data['result']	= $result['rows'];
			
			$total_rows = $this->pagination->total_rows;

			if($total_rows < 1){
				$start = 0;
			}else{
				$start 	= $page + 1;
			}

			$end 	= $page + $this->pagination->per_page;

			if($end > $total_rows){
			   	$end = $total_rows;
			}

			$data['start'] 	= $start;
			$data['end']	= $end;
			$data['total'] 	= $total_rows;
			
			return $this->load->view('waybill/waybill', $data);
		}

		public function search(){
			$query_array = array(
				'search_key' => $this->input->post('search_key')
			);
			$query_id = $this->input->save_query($query_array);

			return redirect("waybill/show/$query_id");
		}

		public function save(){
			$truck_id = $this->input->post('truck');
			$shipment_date = $this->input->post('shipment_date');

			if($truck_id == '') {$truck_id = NULL;}
			if($shipment_date == '') {$shipment_date = NULL;}
			
			$data['waybill_data'] = array(
				'consignee' 	 => $this->input->post('ce_id'),
				'consignor' 	 => $this->input->post('cr_id'),
				'payment_terms'	 => $this->input->post('payment_terms'),
				'notes'			 => $this->input->post('notes'),
				'status'		 => $this->input->post('status'),
				'dr_number'		 => $this->input->post('dr_number'),
				'is_backload'	 => $this->input->post('is_backload'),
				'total'			 => $this->input->post('total'),
				'processed_by'   => $this->session->userdata('logged_in')['user_id']
			);

			$data['waybill_items'] = array(
				'quantity'		   => $this->input->post('quantity'),
				'unit' 			   => $this->input->post('unit'),
				'unit_price' 	   => $this->input->post('unit_price'),
				'cost_id'	   	   => $this->input->post('id'),
				'item_description' => $this->input->post('item_description'),
				'sub_total'		   => $this->input->post('sub_total')
			);

			if($this->validate()){
				if(!empty($this->input->post('waybill_number'))){ 	//update waybill
					$data['waybill_number'] = $this->input->post('waybill_number');
				}
				if($this->waybill_model->create($data)) {
					$result['success'] = TRUE;
				}
			}else{
				$error['ce_id'] 		= form_error('ce_id');
				$error['cr_id']			= form_error('cr_id');
				$error['consignee'] 	= form_error('consignee');
				$error['consignor']		= form_error('consignor');
				$error['address_1'] 	= form_error('address_1');
				$error['address_2'] 	= form_error('address_2');
				$error['dr_number'] 	= form_error('dr_number');
				$error['payment_terms'] = form_error('payment_terms');
				$error['amount']		= form_error('amount');
			
				$error['quantity'] 			= form_error('quantity[]');
				$error['unit'] 				= form_error('unit[]');
				$error['description'] 		= form_error('description[]');
				$error['unit_price'] 		= form_error('unit_price[]');

			}
			if(!empty($error)){
				$result['success'] = FALSE;
				$result['error'] = $error;
			}
			echo json_encode($result);
		}

		public function validate(){
			$this->load->library('form_validation');
			//customer info
			$this->form_validation->set_rules('ce_id','ce_id','required');
			$this->form_validation->set_rules('cr_id','cr_id','required');
			$this->form_validation->set_rules('consignee','Consignee','required');
			$this->form_validation->set_rules('address_1','Consignee Address','required');
			$this->form_validation->set_rules('consignor','Consignor','required');
			$this->form_validation->set_rules('address_2','Consignor Address','required');
			$this->form_validation->set_rules('dr_number','DR #','alpha_numeric');
			if(empty($this->input->post('waybill_number'))) {
				$this->form_validation->set_rules('payment_terms','Payment Terms','required');
				if($this->input->post('payment_terms') === 'prepaid'){
					$this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
				}
			}
			//item details
			$this->form_validation->set_rules('quantity[]','Quantity','required|numeric');
			$this->form_validation->set_rules('unit[]','Unit','required');
			$this->form_validation->set_rules('item_description[]','Item Description','required');
			$this->form_validation->set_rules('unit_price[]','Unit Price','required|numeric');

			if($this->form_validation->run()){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		public function add(){
			$this->load->model('truck_model');
			//$data['result'] = $this->unit_category_model->read();
			$data['trucks'] = $this->truck_model->getTrucks();
			return $this->load->view('waybill/waybill_new', $data);
		}

		public function update(){
			$update = TRUE;
			$this->getDetails($update);
		}

		public function delete(){
			$session_data = $this->session->userdata('logged_in');
			if($session_data['role'] != 'admin') {
				$this->session->set_flashdata('warning', 'You are not allowed to delete.');
				return FALSE;
			}

			$data = $this->input->post('checkbox');
			if($this->waybill_model->delete($data)){
				$this->session->set_flashdata('notification','Record has been deleted.');
				return TRUE;
			}
		}

		public function getUncollected(){
			$this->noCache();

			$config = array();
			$config['base_url'] = base_url()."waybill/getUncollected/";
			$config['per_page'] = 30;
			$config['uri_segment'] = 3;
			$page 	= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0 ;

			$config['total_rows'] = $this->waybill_model->countUncollected();

			$this->pagination->initialize($config);

			$data['links'] 	= $this->pagination->create_links();
			$data['result']	= $this->waybill_model->getUncollected($config['per_page'], $page);

			$total_rows = $this->waybill_model->countUncollected();

			if($total_rows < 1){
				$start = 0;
			}else{
				$start 	= $page + 1;
			}

			$end 	= $page + $this->pagination->per_page;

			if($end > $total_rows){
			   	$end = $total_rows;
			}

			$data['start'] 	= $start;
			$data['end']	= $end;
			$data['total'] 	= $total_rows;

			return ($data['result'] >= 0) ? $this->load->view('waybill/waybill_uncollected', $data) : redirect('error/db_error');
		}

		public function getPrepaid(){
			$this->noCache();

			$config = array();
			$config['base_url'] = base_url()."waybill/getPrepaid/";
			$config['per_page'] = 50;
			$config['uri_segment'] = 3;
			$page 	= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0 ;

			$config['total_rows'] = $this->waybill_model->countPrepaid()['total'];

			$this->pagination->initialize($config);

			$data['links'] 	= $this->pagination->create_links();
			$data['result']	= $this->waybill_model->getPrepaid($config['per_page'], $page);

			$total_rows = $this->waybill_model->countPrepaid()['total'];

			if($total_rows < 1){
				$start = 0;
			}else{
				$start 	= $page + 1;
			}

			$end 	= $page + $this->pagination->per_page;

			if($end > $total_rows){
			   	$end = $total_rows;
			}

			$data['start'] 	= $start;
			$data['end']	= $end;
			$data['total'] 	= $total_rows;

			return ($data['result'] >= 0) ? $this->load->view('waybill/waybill_prepaid', $data) : redirect('error/db_error');
		}

		public function getBackload(){
			$this->noCache();

			$config = array();
			$config['base_url'] = base_url()."waybill/getBackload/";
			$config['per_page'] = 50;
			$config['uri_segment'] = 3;
			$page 	= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0 ;

			$config['total_rows'] = $this->waybill_model->countBackload();

			$this->pagination->initialize($config);

			$data['links'] 	= $this->pagination->create_links();
			$data['result']	= $this->waybill_model->getBackload($config['per_page'], $page);

			$total_rows = $this->waybill_model->countBackload();

			if($total_rows < 1){
				$start = 0;
			}else{
				$start 	= $page + 1;
			}

			$end 	= $page + $this->pagination->per_page;

			if($end > $total_rows){
			   	$end = $total_rows;
			}

			$data['start'] 	= $start;
			$data['end']	= $end;
			$data['total'] 	= $total_rows;

			return ($data['result'] >= 0) ? $this->load->view('waybill/waybill_backload', $data) : redirect('error/db_error');
		}

		public function computePrepaid() {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('start_date', 'Start Date', 'required');
			$this->form_validation->set_rules('end_date', 'End Date', 'required');

			if($this->form_validation->run()) {
				$data = array(
					'start_date' => date('Y-m-d 00:00:00', strtotime($this->input->post('start_date'))),
					'end_date'	 => date('Y-m-d 23:59:59', strtotime($this->input->post('end_date')))
				);
				if($this->waybill_model->computePrepaid($data)) {
					$result['success'] = true;
					$result['result']  = $this->waybill_model->computePrepaid($data);
				} else {
					$result['success'] = false;
					$errors['db_error'] = "Database error occured.";
				}
			} else {
				$errors['start_date'] = form_error('start_date');
				$errors['end_date']	  = form_error('end_date');
			}

			if(!empty($errors)) {
				$result['success'] = false;
				$result['error']  = $errors;
			}

			echo json_encode($result);
		}	

		public function printWaybill($waybill_number){
			$data['rows'] 			= $this->waybill_model->getDetails($waybill_number, FALSE, FALSE);
			$data['resultItems'] 	= $this->waybill_model->getItems($waybill_number, FALSE);

			if(count($data['rows']) == 0){
				return redirect('error/error_403');
			}

			if(!empty($data['rows'])){
				return $this->load->view('waybill/waybill_print', $data, NULL, TRUE);
			}else{
				return redirect('error/db_error');
			}
		}

		public function printByBatch(){
			$waybill_number = $this->input->post('checkbox');

			$data['rows'] 			= $this->waybill_model->getDetails($waybill_number, TRUE, FALSE);
			$data['resultItems'] 	= $this->waybill_model->getItems($waybill_number, TRUE);

			if(!empty($data['rows'])){
				return $this->load->view('waybill/waybill_print', $data);
			}else{
				return redirect('error/db_error');
			}
		}

		public function printUncollected(){
			$data['result']	= $this->waybill_model->getUncollected(1, 0);

			if(!empty($data['result'])){
				return $this->load->view('waybill/waybill_print_uncollected', $data);
			}else{
				return redirect('error/db_error');
			}
		}

		public function getDetails($update){
			(!$this->uri->segment(3)) ? show_404() : $waybill_number = $this->uri->segment(3);

			$data['row'] 			= $this->waybill_model->getDetails($waybill_number, NULL, TRUE);
			$data['resultItems'] 	= $this->waybill_model->getItems($waybill_number, FALSE);

			if(count($data['row']) == 0) return redirect('error/error_403');
				
			if($update === TRUE){
				$this->load->model('truck_model');
				$data['trucks'] = $this->truck_model->getTrucks();

				return $this->load->view('waybill/update_waybill', $data);
			}else{
				$this->load->model('payment_model');
				$data['payments'] 	= $this->payment_model->getPayment($waybill_number);
				$data['amountPaid'] = $this->waybill_model->getAmountPaid($waybill_number);
				
				return $this->load->view('waybill/waybill_details',$data);
			}
		}

		public function typeAhead(){
			if($this->waybill_model->getUnloadedTypeAhead()){
				$result['success'] = TRUE;
				$result['result'] = $this->waybill_model->getUnloadedTypeAhead();
			}
			$return = array();
			foreach($result['result'] as $row){
				$return[] = $row->waybill_number;
			}
			$json = json_encode($return);
			
			print_r($json);
		}

		public function updateDeliveryStatus(){
			$data = array(
				'waybill_number'	=> $this->input->post('waybill_number'),
				'delivery_status' 	=> $this->input->post('delivery_status'),
				'manifest_number' 	=> $this->waybill_model->getManifestNumber($this->input->post('waybill_number'))
			);

			if($this->waybill_model->updateDeliveryStatus($data)){
				$this->session->set_flashdata('notification','Delivery status has been updated.');
				return redirect("waybill");
			} else {
				echo "error";
			}
		}

		private function noCache(){
			$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
			$this->output->set_header("Pragma: no-cache");
		}
	}
?>