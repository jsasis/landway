<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User_Model extends CI_Model
{

    private $table = 'user_account';

    public function __construct()
    {
        parent::__construct();
    }

    public function login($username, $password)
    {
        $this->db->select('user_id, first_name, last_name, username, password, user_type');
        $this->db->from('user_account');
        $this->db->where('username', $username);
        $this->db->where('password', MD5($password));
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function create($data)
    {
        if ($this->db->insert($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function saveUpdate($data)
    {
        $this->db->where('user_id', $data['user_id']);

        $query = $this->db->update($this->table, $data['user_data']);

        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function check_password($username, $password)
    {
        $sql   = "SELECT * FROM user_account WHERE username = ? AND password  = ? LIMIT 1";
        $query = $this->db->query($sql, array($username, $password));

        if ($query->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
        $this->db->where('username', $data['username']);
        $this->db->where('password', $data['old_password']);

        $query = $this->db->update($this->table, array('password' => $data['new_password']));

        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($data)
    {
        for ($i = 0; $i < sizeof($data); $i++) {
            $this->db->where('user_id', $data[$i]);
            $this->db->delete($this->table);
        }
        return true;
    }

    public function getDetails($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->table);
        if ($query) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function fetch($query_array, $limit, $start)
    {

        if (strlen($query_array['search_key'])) {
            $this->db->like('first_name', $query_array['search_key']);
            $this->db->or_like('last_name', $query_array['search_key']);
            $this->db->or_like('username', $query_array['search_key']);
        }

        $query = $this->db->get($this->table, $limit, $start);

        $ret['rows'] = $query->result();

        if (strlen($query_array['search_key'])) {
            $this->db->like('first_name', $query_array['search_key']);
            $this->db->or_like('last_name', $query_array['search_key']);
            $this->db->or_like('username', $query_array['search_key']);
        }

        $ret['num_rows'] = $this->db->count_all_results($this->table);

        return $ret;
    }
}
