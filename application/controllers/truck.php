<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Truck extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            return redirect('user');
        } else {
            if ($this->session->userdata('logged_in')['role'] !== 'admin') {
                return redirect('error/error_403');
            }
        }

        $this->load->library('pagination');
        $this->load->model('truck_model');
    }

    public function index()
    {
        $this->show();
    }

    public function show()
    {
        $config               = array();
        $config['base_url']   = base_url() . 'truck/show';
        $config['total_rows'] = $this->truck_model->recordCount();
        $config['per_page']   = 50;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3));

        $data['result'] = $this->truck_model->fetch($page, $config['per_page']);
        $data['links']  = $this->pagination->create_links();

        $total_rows = $this->pagination->total_rows;

        if ($total_rows < 1) {
            $start = 0;
        } else {
            $start = $page + 1;
        }
        $end = $page + $this->pagination->per_page;

        if ($end > $total_rows) {
            $end = $total_rows;
        }

        $data['start'] = $start;
        $data['end']   = $end;
        $data['total'] = $total_rows;

        return $this->load->view('truck/truck', $data);
    }

    public function add()
    {
        return $this->load->view('truck/add_truck');
    }

    public function save()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('make', 'Make', 'required');
        $this->form_validation->set_rules('type', 'Type', 'required');
        $this->form_validation->set_rules('plate_number', 'Plate Number', 'required');

        if ($this->form_validation->run()) {
            if (!empty($this->input->post('truck_id'))) {
                //TRUCK ID EXISTS! IT MEANS WE'RE DOING AN UPDATE
                $data = array(
                    'truck_id'     => $this->input->post('truck_id'),
                    'make'         => ucwords($this->input->post('make')),
                    'type'         => ucwords($this->input->post('type')),
                    'plate_number' => ucwords($this->input->post('plate_number')),
                );
            } else {
                //TRUCK ID DOES'NT EXISTS. IT MEANS WE'RE CREATING A NEW RECORD
                $data = array(
                    'make'         => ucwords($this->input->post('make')),
                    'type'         => ucwords($this->input->post('type')),
                    'plate_number' => ucwords($this->input->post('plate_number')),
                );
            }
            if ($this->truck_model->create($data)) {
                $result['success'] = true;
            }
        } else {

            $error['make']         = form_error('make');
            $error['type']         = form_error('type');
            $error['plate_number'] = form_error('plate_number');

            if (!empty($error)) {
                $result['error']   = $error;
                $result['success'] = false;
            }
        }
        echo json_encode($result);
    }

    public function delete()
    {
        $data = $this->input->post('checkbox');

        if ($this->truck_model->delete($data)) {
            return true;
        }
    }

    public function update()
    {
        $truck_id = $this->uri->segment(3);

        $this->load->model('manifest_model');

        $config                = array();
        $config['base_url']    = base_url() . "truck/update/$truck_id/";
        $config['per_page']    = 50;
        $config['uri_segment'] = 4;

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $result = $this->manifest_model->fetch(null, $config['per_page'], $page, $truck_id);

        $config['total_rows'] = $result['num_rows'];

        $this->pagination->initialize($config);

        $data['links']     = $this->pagination->create_links();
        $data['manifests'] = $result['rows'];

        $total_rows = $this->pagination->total_rows;

        if ($total_rows < 1) {
            $start = 0;
        } else {
            $start = $page + 1;
        }

        $end = $page + $this->pagination->per_page;

        if ($end > $total_rows) {
            $end = $total_rows;
        }

        $data['start'] = $start;
        $data['end']   = $end;
        $data['total'] = $total_rows;

        if ($data['result'] = $this->truck_model->read($truck_id)) {

            return $this->load->view('truck/update_truck', $data);
        } else {
            return redirect('error/db_error');
        }
    }

    public function search()
    {
        if ($this->truck_model->search()) {
            $result['result'] = $this->truck_model->search();
            return $this->load->view('truck/truck_ajax', $result);
        } else {
            $result['result'] = $this->truck_model->search();
            //$this->pagination->reset();
            return $this->load->view('truck/truck_ajax', $result);
        }
    }

    public function create_report()
    {
        $typeOfReport = $this->uri->segment(3);

        if ($typeOfReport) {
            if ($typeOfReport == "gross") {
                $data['trucks'] = $this->truck_model->getTrucks();
                return $this->load->view('truck/truck_report', $data);
            } elseif ($typeOfReport == "annual") {
                return $this->load->view('truck/truck_report_annual');
            }
        } else {
            return redirect("error/error_404");
        }
    }

    public function generate_report()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('report_type', 'Report Type', 'required');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('end_date', 'End Date', 'required');

        if ($this->form_validation->run()) {
            $this->load->model('waybill_model');
            $post_data = array(
                'start_date' => date('Y-m-d 00:00:00', strtotime($this->input->post('start_date'))),
                'end_date'   => date('Y-m-d 23:59:59', strtotime($this->input->post('end_date'))),
            );

            if ($this->input->post('report_type') == 'prepaid') {
                $report_res = $this->waybill_model->computePrepaid($post_data);
            } elseif ($this->input->post('report_type') == 'received') {
                $report_res = $this->waybill_model->computeReceived($post_data);
            } elseif ($this->input->post('report_type') == 'backload') {
                $report_res = $this->waybill_model->computeBackload($post_data);
            } else {
                $post_data = array(
                    'truck_id'   => $this->input->post('truck'),
                    'start_date' => date('Y-m-d 00:00:00', strtotime($this->input->post('start_date'))),
                    'end_date'   => date('Y-m-d 23:59:59', strtotime($this->input->post('end_date'))),
                );

                $report_res = $this->truck_model->generate_report($post_data);
            }

            if ($report_res) {
                $data['start']  = $this->input->post('start_date');
                $data['end']    = $this->input->post('end_date');
                $data['result'] = $report_res;

                $report_type = $this->input->post('report_type');
                if ($report_type == 'prepaid') {
                    $html_report = $this->load->view('report/prepaid', $data, true);
                } elseif ($report_type == 'received') {
                    $html_report = $this->load->view('report/received', $data, true);
                } elseif ($report_type == 'backload') {
                    $html_report = $this->load->view('report/backload', $data, true);
                } else {
                    $html_report = $this->load->view('truck/truck_report_result', $data, true);
                }
                $result['result']  = $html_report;
                $result['success'] = true;
            } else {
                $result['success'] = false;
                $error['empty']    = "No record/s found.";
            }

        } else {
            $error['report_type'] = form_error('report_type');
            $error['start_date']  = form_error('start_date');
            $error['end_date']    = form_error('end_date');
        }

        if (!empty($error)) {
            $result['error']   = $error;
            $result['success'] = false;
        }

        echo json_encode($result);
    }

    public function generate_report_annual()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('year', 'Truck', 'required');

        if ($this->form_validation->run()) {
            $post_data = date('Y', strtotime($this->input->post('year')));

            $report_res = $this->truck_model->generate_report_annual($post_data);

            if ($report_res) {
                $data['year']   = $this->input->post('year');
                $data['result'] = $report_res;

                $html_report = $this->load->view('truck/truck_report_result_annual', $data, true);

                $result['result']  = $html_report;
                $result['success'] = true;

            } else {
                $result['success'] = false;
                $error['empty']    = "No records found.";
            }
        } else {
            $error['year'] = form_error('year');
        }

        if (!empty($error)) {
            $result['error']   = $error;
            $result['success'] = false;
        }

        echo json_encode($result);
    }
}
