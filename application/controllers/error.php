<?php
	if(!defined('BASEPATH')) exit('No direct script access allowed');

	class Error extends CI_Controller{

		public function __construct(){

			parent::__construct();
		}

		public function index(){
			$this->error_403();
		}

		public function error_403(){
			return $this->load->view('error/error_403');
		}

		public function db_error(){
			return $this->load->view('error/db_error');
		}
	}
?>