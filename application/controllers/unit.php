<?php
	if(!defined('BASEPATH')) exit('No direct script access allowed');

	class Unit extends CI_Controller{
		public function __construct(){
			parent::__construct();

			if(!$this->session->userdata('logged_in')){
				redirect('user');
			}
			
			$this->load->library('pagination');
			$this->load->model('unit_model');
		}

		public function index(){
			$this->showUnits();
		}

		public function showUnits(){
			$config = array();
			$config['base_url'] = base_url().'unit/showUnits';
			$config['total_rows'] = $this->unit_model->recordCount();
			$config['per_page'] = 50;
			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(3));
			$data['result'] = $this->unit_model->fetchUnit($page, $config['per_page']);
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

			$this->load->view('unit/unit', $data);

			/*
			
			$data['result'] = $this->unit_model->read();

			$this->load->view('unit',$data);*/
		}

		public function addUnit(){
			
			$this->load->view('unit/add_unit');
		}

		public function getSubItems(){
			$this->load->model("unit_category_model");
			$unit_id = $this->uri->segment(3);

			if($unit_id) {
				$data["item"] = $this->unit_model->read($unit_id);
				$data["sub_items"] = $this->unit_category_model->getSubItems($unit_id);

				$this->load->view("unit/unit_items", $data);

			} else {
				redirect("error/error_404");
			}
		}

		public function save(){
			$this->load->library('form_validation');

			$this->form_validation->set_rules('unit_code','Unit Code','required|trim');
			$this->form_validation->set_rules('description','Description','required|trim');

			if($this->form_validation->run()){
				if(!empty($this->input->post('unit_id'))){ //if updating an existing record
					$data = array(
						'unit_id'	=> $this->input->post('unit_id'),
						'unit_code' => strtoupper($this->input->post('unit_code')),
						'description' => ucwords($this->input->post('description'))
						);
				}else{ //if adding a new record
					$data = array(
						'unit_code' => strtoupper($this->input->post('unit_code')),
						'description' => ucwords($this->input->post('description'))
						);
				}
				if($this->unit_model->create($data)){
					$result['success'] = true;
				}
			}else{
				
				$error['unit_code'] = form_error('unit_code');
				$error['description'] = form_error('description');
				$error['unit_cost'] = form_error('unit_cost');

				if(!empty($error)){
					$result['error'] = $error;
					$result['success'] = false;
				}
			}
			echo json_encode($result);
		}

		public function delete(){
			$data = $this->input->post('checkbox');

			if($this->unit_model->deleteAll($data)){
				return true;
			}
		}

		public function updateUnit(){
			
			$unitID = $this->uri->segment(3);

			if($result['result'] = $this->unit_model->read($unitID)){
				$this->load->view('unit/update_unit',$result);
			}else{
				echo "ERROR.";
			}
		}

		public function search(){
			if($this->unit_model->search()){
				$result['result'] = $this->unit_model->search();
				$this->load->view('unit/unit_ajax', $result);
			}else{
				$result['result'] = $this->unit_model->search();
				//$this->pagination->reset();
				$this->load->view('unit/unit_ajax', $result);
			}
		}

		public function showUnits1(){
			/*
			$config = array();
			$config['base_url'] = base_url().'unit/showUnits';
			$config['total_rows'] = $this->unit_model->recordCount();
			$config['per_page'] = 5;

			$this->pagination->initialize($config);

			$page = ($this->uri->segment(3));
			$data['result'] = $this->unit_model->fetchUnit($page, $config['per_page']);
			$data['links'] = $this->pagination->reset();

			$this->load->view('unit', $data);*/

			/*
			
			$data['result'] = $this->unit_model->read();

			$this->load->view('unit',$data);*/
		}

		public function isLoggedIn(){
			if($this->session->userdata('logged_in')){
				return true;
			}else{
				return false;
			}
		}
	}
?>