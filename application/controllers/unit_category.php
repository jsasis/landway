<?php 	if(!defined('BASEPATH')) exit('No direct script access allowed');

	class Unit_Category extends CI_Controller{

		public function __construct(){
			parent::__construct();
			
			if(!$this->session->userdata('logged_in')){
				return redirect('/');
			}

			$this->load->library('pagination');
			$this->load->model('unit_category_model');
		}

		public function index(){
			$this->show();
		}

		public function show(){
			$config = array();
			$config['base_url'] = base_url()."unit_category/show";
			$config['total_rows'] = $this->unit_category_model->recordCount();
			$config['per_page'] = 50;
			$config['uri_segment'] = 3;
			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
			
			$this->pagination->initialize($config);

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
		}

		public function save() {
			$this->load->library('form_validation');

			if(!empty($this->input->post('unit_category_id'))){
				$this->form_validation->set_rules('unit_category_description','Category Description','required|trim|is_unique[unit_category.description]');
			} else {
				$this->form_validation->set_rules('unit','Unit','required|trim');
				$this->form_validation->set_rules('unit_category_description','Category Description','required|trim|is_unique[unit_category.description]');
				$this->form_validation->set_rules('unit_cost','Unit Cost','required|trim');
			}
			
			if($this->form_validation->run()){
				if(!empty($this->input->post('unit_category_id'))){ //if updating a record
					$data = array(
						'unit_category_id'	=> $this->input->post('unit_category_id'),
						'description' 		=> ucwords($this->input->post('unit_category_description')),
						);
				} else { //if adding a new record
					$data['item'] = array(
						'unit_id' => $this->input->post('unit'),
						'description' => ucwords($this->input->post('unit_category_description')),
						);
					$data['item_cost'] = $this->input->post('unit_cost');
				}

				if($this->unit_category_model->create($data)){

					$result['success'] = true;
				}
			} else {
				
				$error['unit_category_description'] = form_error("unit_category_description");
				$error['unit_cost'] = form_error("unit_cost");

				if(!empty($error)){
					$result['error'] = $error;
					$result['success'] = false;
				}
			}

			echo json_encode($result);
		}

		public function add(){
			$data['unit'] = $this->getUnits();
			return $this->load->view('unit_category/add_unit_category',$data);
		}

		public function update(){
			$unitID = $this->uri->segment(3);
			$result['unit'] = $this->getUnits();

			if($result['result'] = $this->unit_category_model->read($unitID)){
				return $this->load->view('unit_category/update_unit_category',$result);
			}
		}

		public function delete(){
			$data = $this->input->post('checkbox');

			if($this->unit_category_model->deleteAll($data)){
				$result['success'] = TRUE;
			} else {
				$result['success'] = FALSE;
			}

			echo json_encode($result);
		}

		public function getUnits(){
			$this->load->model('unit_model');
		
			return $data['unit'] = $this->unit_model->read();
		}

		public function getItemPrice(){
			if($this->unit_category_model->getItemPrice()){
				$result['success'] = true;
				$result['result'] = $this->unit_category_model->getItemPrice();
			}
			echo json_encode($result);
			//print_r($result);
		}

		public function getItems(){
			if($this->unit_category_model->getItems()){
				$result = $this->unit_category_model->getItems();
			}
			$items = array();
			foreach($result as $row){
				$items[] = $row->description;
			}
			$json = json_encode($items);
			print_r($json);
		}
	}
?>