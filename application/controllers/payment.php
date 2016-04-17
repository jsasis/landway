<?php 	if(!defined('BASEPATH')) exit('No direct script access allowed');

	class Payment extends CI_Controller {

		public function __construct() {
			parent::__construct();

			if(!$this->session->userdata('logged_in')){
				return redirect('user');
			}

			$libraries 	= array(
				'pagination',
				'form_validation'
			);
			$models		= array(
				'payment_model',
			);

			$this->load->library($libraries);
			$this->load->model($models);
		}	

		public function index(){
			$this->show();
		}

		public function show(){
			$this->noCache();

			$config = array();
			$config['base_url'] 	= base_url().'payment/show/';
			$config['total_rows'] 	= $this->payment_model->recordCount();
			$config['per_page'] 	= 10;
			$config['uri_segment']  = 3;

			$this->pagination->initialize($config);

			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			
			$data['result'] = $this->payment_model->fetch($config['per_page'], $page);
			$data['links']	= $this->pagination->create_links();

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

			return $this->load->view('payment/payment', $data);
		}

		public function save(){
			$this->load->model('payment_model');
			$this->load->library('form_validation');

			$this->form_validation->set_rules('waybill_number','Waybill No.', 'required');
			$this->form_validation->set_rules('payment_terms','Payment Terms', 'required');
			$this->form_validation->set_rules('amount', 'Amount', 'required|numeric');

			if($this->form_validation->run()){
				$data	= array(
					'waybill_number'	=> $this->input->post('waybill_number'),
					'payment_terms'		=> $this->input->post('payment_terms'),
					'amount'			=> $this->input->post('amount'),
					'date'				=> date('Y-m-d H:i:s')
				);
				if($this->payment_model->create($data)){
					$result['success']	= TRUE;
				}
			}else{
				$errors['waybill_number']	= form_error('waybill_number');
				$errors['payment_terms']	= form_error('payment_terms');
				$errors['amount'] 			= form_error('amount');
			}

			if(!empty($errors)){
				$result['success']	= FALSE;
				$result['error']	= $errors;
			}
			echo json_encode($result);
		}

		public function add(){
			$this->load->model('waybill_model');
			$data['waybill_numbers'] = $this->waybill_model->getWaybill();
			return $this->load->view('payment/payment_new', $data);
		}

		public function delete(){
			$data = $this->input->post('checkbox');
			if($this->payment_model->delete($data)){
				$this->session->set_flashdata('notification','Record has been deleted.');
				return TRUE;
			}else{
				echo "DB ERROR";
			}
		}

		private function noCache(){
			$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
			$this->output->set_header("Pragma: no-cache");
		}
	}
?>