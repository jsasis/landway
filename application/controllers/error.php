<?php
	if(!defined('BASEPATH')) exit('No direct script access allowed');

	class Error extends CI_Controller{

		function __construct(){

			parent::__construct();
		}

		function index(){
			$this->error_403();
		}

		function error_403(){
			$this->load->view('error/error_403');
		}

		function db_error(){
			$this->load->view('error/db_error');
		}
	}
?>