<?php 	if(!defined('BASEPATH')) exit('No direct script access allowed');

	class Costing extends CI_Controller{

		public function __construct(){
			parent::__construct();
			
			if(!$this->session->userdata('logged_in')){
				return redirect('/');
			}

			$this->load->library('form_validation');
			$this->load->library('pagination');
			$this->load->model('costing_model');
		}

		/*public function index(){
			$this->show();
		}*/

		/*public function show(){
			$config = array();
			$config['base_url'] = base_url()."unit_category/show";
			$config['total_rows'] = $this->unit_category_model->recordCount();
			$config['per_page'] = 5;
			$config['uri_segment'] = 3;
			
			$this->pagination->initialize($config);

			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			$data['result'] = $this->unit_category_model->fetchUnit($config['per_page'], $page);
			$data['links'] = $this->pagination->create_links();

			$total_rows = $this->pagination->total_rows;

			if($total_rows < 1){
				$start = 0;
			}

			$start 	= $page + 1;
			$end 	= $page + $this->pagination->per_page;

			if($end > $total_rows){
			   	$end = $total_rows;
			}

			$data['start'] 	= $start;
			$data['end']	= $end;
			$data['total'] 	= $total_rows;

			return $this->load->view('unit_category/unit_category', $data);
		}*/

		public function save(){
			$this->form_validation->set_rules('cost','New Price','required|trim');

			if($this->form_validation->run()){
				$data = array(
					'unit_category_id' 	=> $this->input->post('unit_category_id'),
					'cost'				=> $this->input->post('cost'),
					'effective_date'	=> date('Y-m-d H:i:s')
				);

				if($this->costing_model->create($data)){
					$result['success'] = TRUE;
				}else{
					echo "DB ERROR";
				}

			}else{
				$error['cost'] = form_error('cost');
			}
				
			if(!empty($error)){
				$result['success'] = FALSE;
				$result['error']   = $error;
			}

			echo json_encode($result);
		}
	}
?>