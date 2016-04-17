<?php
	class Post_model extends CI_Model {
		
		private $table = 'posts';

		public function __construct() {
			parent::__construct();
		}

		public function create($data){
			if(!isset($data['id']))
			{
				$query = $this->db->insert($this->table, $data);
			} else {
				$this->db->where('id', $data['id']);
				$query = $this->db->update($this->table, $data);
			}

			return ($query) ? TRUE : FALSE;
		}

		public function findAll() {
			$this->db->select('u.user_id, u.first_name, u.last_name, p.id, p.post_title, p.post_body, p.post_date');
			$this->db->join('user_account u', 'u.user_id = p.posted_by');
			$this->db->order_by('p.id', 'desc');
			$this->db->limit(5);
			
			$query = $this->db->get('posts p');

			return $query->result();
		}

		public function findById($id) {
			$this->db->select('u.first_name, u.last_name, p.id, p.post_title, p.post_body, p.post_date');
			$this->db->join('user_account u', 'u.user_id = p.posted_by');
			$this->db->where('id', $id);

			$query = $this->db->get('posts p');

			return $query->row();
		}

		public function delete($post_id) {
			$this->db->where('id', $post_id);

			if($this->db->delete($this->table)) {
				return TRUE;
			} else {
				return FALSE;
			}
		}

		public function recordCount() {
			
			return $this->db->count_all($this->table);	
		}

		public function fetch($limit, $start) {
			$this->db->order_by('id');

			$query = $this->db->get($this->table, $limit, $start);

			if($query->num_rows() > 0)
			{
				return $query->result();
			}
			else
			{
				return FALSE;
			}
		}
	}
?>