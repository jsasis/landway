<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('pagination');
        $this->noCache();
    }

    public function paginate($instance, $baseUrl, $limit, $query_id = 0)
    {
    	$this->input->load_query($query_id);
    	
    	$query_array = array(
    		'search_key' => $this->input->get('search_key')
    	);

    	$config = array();
    	$config['base_url'] = $baseUrl;
    	$config['per_page'] = $limit;
    	$config['uri_segment'] = 4;
    	$page 	= ($this->uri->segment(4)) ? $this->uri->segment(4) : 0 ;

    	$result = $instance->fetch($query_array, $config['per_page'], $page);

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

    	return $data;
    }

    public function noCache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }
}
