<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('post_model');
	}

	function index() {
		$this->show();
	}

	function show(){
		$this->load->library('pagination');
		
		$config = array();
		$config['base_url'] = base_url() . 'post/show';
		$config['total_rows'] = $this->post_model->recordCount();
		$config['per_page'] = 7;

		$this->pagination->initialize($config);

		$page = ($this->uri->segment(3));

		$data['result'] = $this->post_model->fetch($config['per_page'], $page);
		$data['links'] = $this->pagination->create_links();

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

		$this->load->view('post/post', $data);
	}

	function save() {
		$this->load->model('post_model');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('post_title', 'Title', 'required');
		$this->form_validation->set_rules('post_body', 'Body', 'required');

		if($this->form_validation->run())
		{
			if(!empty($this->input->post('id'))) {
				$data = array(
					'id'		=> $this->input->post('id'),
					'posted_by' => $this->session->userdata('logged_in')['user_id'],
					'post_title'=> $this->input->post('post_title'),
					'post_body'=> $this->input->post('post_body'),
					'post_date'=> date('Y-m-d H:i:s')
				);
			} else {
				$data = array(
					'posted_by' => $this->session->userdata('logged_in')['user_id'],
					'post_title'=> $this->input->post('post_title'),
					'post_body'=> $this->input->post('post_body'),
					'post_date'=> date('Y-m-d H:i:s')
				);
			}
			
			$save = $this->post_model->create($data);

			if($save)
			{
				$this->session->set_flashdata('notification','Post has been saved.');
				$result['success'] = TRUE;
			}
		} else {
			$errors['title'] = form_error('post_title');
			$errors['body']  = form_error('post_body');
		}

		if(!empty($errors))
		{
			$result['success'] = FALSE;
			$result['error']   = $errors;
		}

		echo json_encode($result);
	}

	function delete() {
		$post_id = $this->uri->segment(3);

		if($this->post_model->delete($post_id))
		{
			redirect('dashboard');
		}
	}

	function edit() {
		$id = $this->uri->segment(3);

		$posts = $this->post_model->findById($id);

		echo json_encode($posts);
	}
}
?>