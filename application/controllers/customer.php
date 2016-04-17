<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

	public function __construct(){
		parent::__construct();

		if(!$this->session->userdata('logged_in')){
			redirect('user');
		}
		
		$this->load->model('customer_model');
		$this->load->library('pagination');
	}
	
	public function index() {
		
		$this->show();
	}

	public function show($query_id = 0){
		$this->input->load_query($query_id);

		$query_array = array(
			'name' => $this->input->get('name')
		);

		$config = array();
		$config['base_url'] = base_url()."customer/show/$query_id";
		$config['per_page'] = 5;
		$config['uri_segment'] = 4;
		$page 	= ($this->uri->segment(4)) ? $this->uri->segment(4) : 0 ;

		$result = $this->customer_model->fetch($query_array, $config['per_page'], $page);
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

		$this->load->view('customer/customer', $data);
	}

	public function searchCustomer(){
		$query_array = array(
			'name' => $this->input->post('name')
		);
		
		$query_id = $this->input->save_query($query_array);

		redirect("customer/show/$query_id");
	}

	public function add(){
		$this->load->view('customer/customer_new');
	}

	public function update(){
		$customerID = $this->uri->segment(3);

		if($result['result'] = $this->customer_model->read($customerID)){
			$this->load->view('customer/update_customer',$result);
		}
	}

	public function delete(){
		$data = $this->input->post('checkbox');

		if($this->customer_model->delete($data)){
			return true;
		}
	}

	public function save(){
		$this->load->library('form_validation');

		$this->form_validation->set_rules('customer_name','Customer Name','required|trim|xss_clean');
		$this->form_validation->set_rules('complete_address','Complete','required|trim|xss_clean');
		$this->form_validation->set_rules('contact_number','Contact No','trim|xss_clean|min_length[7]|max_length[11]');

		$data = array(
			'name'				=>	ucwords($this->input->post('customer_name')),
			'complete_address'	=> 	ucwords($this->input->post('complete_address')),
			'contact_number' 	=> 	$this->input->post('contact_number')
		);
		
		if($this->form_validation->run()){
			if(!empty($this->input->post('customer_id'))){
				$data['customer_id'] = $this->input->post('customer_id');
			}
			if($this->customer_model->create($data)){
				$result['success'] = true;
			}
		}else{
			$errors['customer_name'] = form_error('customer_name');
			$errors['complete_address'] = form_error('complete_address');
			$errors['contact_number'] = form_error('contact_number');

			if(!empty($errors)) {
				$result['success'] = false;
				$result['errors'] = $errors;
			}
		}
		echo json_encode($result);
	}

	public function typeAhead(){
		$customer_type = $this->uri->segment(3);

		if($this->customer_model->getCustomers($customer_type)){
			$result['success'] = true;
			$result['result'] = $this->customer_model->getCustomers($customer_type);
		}
		$return = array();
		foreach($result['result'] as $row){
			$return[] = $row->name;
		}
		$json = json_encode($return);
		print_r($json);
	}

	public function search(){
		$data = $this->input->post('customer');

		$result['success'] = true;
		$result['result'] = $this->customer_model->searchCustomer($data);
		
		echo json_encode($result);
	}

}
?>