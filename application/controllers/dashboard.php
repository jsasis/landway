<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->load->model('waybill_model');
		$this->load->model('unit_category_model');
		$this->load->model('post_model');

		// $data['uncollected_waybills']	= $this->waybill_model->getUncollected(10, 0);
		// $data['received_waybills']		= $this->waybill_model->getReceived();
		// $data['current_rates']			= $this->unit_category_model->getCurrentRates();

		$data['total_prepaid']			= $this->waybill_model->computePrepaid();
		$data['count_uncollected']		= $this->waybill_model->countUncollected();
		$data['count_received']			= $this->waybill_model->countReceived();
		$data['backload_count']			= $this->waybill_model->countBackload();

		$data['posts']					= $this->post_model->findAll();

		// $this->load->view('dashboard', $data);

		$this->load->view('admin_lte', $data);
	}
}
?>