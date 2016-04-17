<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

session_start();

class Auth extends CI_Controller
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

    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

        if ($this->form_validation->run() == false) {

            return $this->load->view('login');
        } else {
            return redirect('dashboard');
        }
    }

    public function logout()
    {
        $this->load->model('query_model');

        if ($this->query_model->deleteAll()) {
            // delete search keys from db
            $this->session->unset_userdata('logged_in');
            session_destroy();
        } else {
            echo "ERROR DELETING CI_QUERY";
            exit();
        }

        return redirect('/');
    }

    public function check_database($password)
    {
        $username = $this->input->post('username');
        $result   = $this->user_model->login($username, $password);

        if ($result) {
            $sess_array = array();
            foreach ($result as $row) {
                $sess_array = array(
                    'user_id'    => $row->user_id,
                    'first_name' => $row->first_name,
                    'last_name'  => $row->last_name,
                    'username'   => $row->username,
                    'role'       => $row->user_type,
                );
                $this->session->set_userdata('logged_in', $sess_array);
            }
            return true;
        } else {
            $this->form_validation->set_message('check_database', 'Invalid username or password');
            return false;
        }
    }

    public function check_password($password)
    {
        $username = $this->session->userdata('logged_in')['username'];
        $result   = $this->user_model->check_password($username, $password);

        if ($result) {
            return true;
        } else {
            $this->form_validation->set_message('check_password', 'Wrong Password');
            return false;
        }
    }
}
