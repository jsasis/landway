<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    public function index()
    {
        return $this->load->view('login');
    }

    public function show($query_id = 0)
    {
        $data = $this->paginate($this->user_model, base_url("user/show/$query_id"), $this->config->item('per_page_limit'), $query_id);

        return $this->load->view('user/user', $data);
    }

    public function search()
    {
        $query_array = array(
            'search_key' => $this->input->post('search_key'),
        );

        $query_id = $this->input->save_query($query_array);

        return redirect("user/show/$query_id");
    }

    public function add()
    {
        return $this->load->view('user/user_new');
    }

    public function delete()
    {
        $data = $this->input->post('checkbox');

        if ($this->user_model->delete($data)) {
            $this->session->set_flashdata('notification', 'Record has been deleted.');
            return true;
        }
    }

    public function save()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|alpha');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|alpha');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[user_account.username]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]md5');
        $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');

        if ($this->form_validation->run()) {
            $data = array(
                'first_name' => ucwords($this->input->post('first_name')),
                'last_name'  => ucwords($this->input->post('last_name')),
                'username'   => strtolower($this->input->post('username')),
                'password'   => $this->input->post('password'),
                'user_type'  => $this->input->post('user_type'),
            );

            if ($this->user_model->create($data)) {
                $result['success'] = true;
            } else {
                echo "DB ERROR";
            }
        } else {

            $errors['first_name']       = form_error('first_name');
            $errors['last_name']        = form_error('last_name');
            $errors['username']         = form_error('username');
            $errors['password']         = form_error('password');
            $errors['confirm_password'] = form_error('confirm_password');
            $errors['user_type']        = form_error('user_type');
        }

        if (!empty($errors)) {
            $result['success'] = false;
            $result['error']   = $errors;
        }

        echo json_encode($result);
    }

    public function saveUpdate()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|alpha');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|alpha');
        $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');

        if ($this->form_validation->run()) {
            $data['user_id']   = $this->input->post('user_id');
            $data['user_data'] = array(
                'first_name' => ucwords($this->input->post('first_name')),
                'last_name'  => ucwords($this->input->post('last_name')),
                'user_type'  => $this->input->post('user_type'),
            );

            if ($this->user_model->saveUpdate($data)) {
                $result['success'] = true;
            } else {
                return redirect('error/db_error');
            }
        } else {

            $errors['first_name'] = form_error('first_name');
            $errors['last_name']  = form_error('last_name');
            $errors['user_type']  = form_error('user_type');
        }

        if (!empty($errors)) {
            $result['success'] = false;
            $result['error']   = $errors;
        }

        echo json_encode($result);
    }

    public function changePassword()
    {

        return $this->load->view('user/change_password');
    }

    public function update()
    {
        $username = $this->session->userdata('logged_in')['username'];

        $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|md5|callback_check_password');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|md5');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]md5');

        if ($this->form_validation->run()) {
            $data['username']     = $username;
            $data['old_password'] = $this->input->post('old_password');
            $data['new_password'] = $this->input->post('new_password');

            if ($this->user_model->update($data)) {
                $result['success'] = true;
            }
        } else {
            $errors['old_password']     = form_error('old_password');
            $errors['new_password']     = form_error('new_password');
            $errors['confirm_password'] = form_error('confirm_password');
        }

        if (!empty($errors)) {
            $result['success'] = false;
            $result['error']   = $errors;
        }

        echo json_encode($result);
    }

    public function updateUser()
    {
        $user_id = $this->uri->segment(3);

        $data['result'] = $this->user_model->getDetails($user_id);

        return $this->load->view('user/user_update', $data);
    }

    public function getDetails($update)
    {
        $user_id = $this->uri->segment(3);

        $data['row']         = $this->waybill_model->getDetails($user_id);
        $data['resultItems'] = $this->waybill_model->getItems($user_id);

        if ($update === true) {
            $this->load->model('truck_model');
            $data['trucks'] = $this->truck_model->getTrucks();

            return $this->load->view('waybill/update_waybill', $data);
        } else {
            $this->load->model('payment_model');
            $data['payments']   = $this->payment_model->getPayment($user_id);
            $data['amountPaid'] = $this->waybill_model->getAmountPaid($user_id);

            return $this->load->view('waybill/waybill_details', $data);
        }
    }

}
