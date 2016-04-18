<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Customer extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            return redirect('/');
        }

        $this->load->model('customer_model');
        $this->load->library('pagination');
    }

    public function index()
    {

        $this->show();
    }

    public function show($query_id = 0)
    {
    	$data = $this->paginate($this->customer_model, base_url("customer/show/$query_id"), $this->config->item('per_page_limit'), $query_id);

        return $this->load->view('customer/customer', $data);
    }

    public function searchCustomer()
    {
        $query_array = array(
            'search_key' => $this->input->post('search_key'),
        );

        $query_id = $this->input->save_query($query_array);

        return redirect("customer/show/$query_id");
    }

    public function add()
    {
        return $this->load->view('customer/customer_new');
    }

    public function update()
    {
        $customerID = $this->uri->segment(3);

        if ($result['result'] = $this->customer_model->read($customerID)) {
            return $this->load->view('customer/update_customer', $result);
        }
    }

    public function delete()
    {
        $data = $this->input->post('checkbox');

        if ($this->customer_model->delete($data)) {
            return true;
        }
    }

    public function save()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('complete_address', 'Complete', 'required|trim|xss_clean');
        $this->form_validation->set_rules('contact_number', 'Contact No', 'trim|xss_clean|min_length[7]|max_length[11]');

        $data = array(
            'name'             => ucwords($this->input->post('customer_name')),
            'complete_address' => ucwords($this->input->post('complete_address')),
            'contact_number'   => $this->input->post('contact_number'),
        );

        if ($this->form_validation->run()) {
            if (!empty($this->input->post('customer_id'))) {
                $data['customer_id'] = $this->input->post('customer_id');
            }
            if ($this->customer_model->create($data)) {
                $result['success'] = true;
            }
        } else {
            $errors['customer_name']    = form_error('customer_name');
            $errors['complete_address'] = form_error('complete_address');
            $errors['contact_number']   = form_error('contact_number');

            if (!empty($errors)) {
                $result['success'] = false;
                $result['errors']  = $errors;
            }
        }
        echo json_encode($result);
    }

    public function typeAhead()
    {
        $customer_type = $this->uri->segment(3);

        if ($this->customer_model->getCustomers($customer_type)) {
            $result['success'] = true;
            $result['result']  = $this->customer_model->getCustomers($customer_type);
        }
        $return = array();
        foreach ($result['result'] as $row) {
            $return[] = $row->name;
        }
        $json = json_encode($return);
        print_r($json);
    }

    public function search()
    {
        $data = $this->input->post('customer');

        $result['success'] = true;
        $result['result']  = $this->customer_model->searchCustomer($data);

        echo json_encode($result);
    }

}
