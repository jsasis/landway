<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Manifest extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            return redirect('user');
        }

        $libraries = array(
            'pagination',
            'session',
            'form_validation',
        );
        $models = array(
            'manifest_model',
            'waybill_model',
        );

        $this->load->library($libraries);
        $this->load->model($models);
    }

    public function index()
    {

        $this->listManifest();
    }

    public function show()
    {
        $this->load->model('truck_model');
        $data['trucks'] = $this->truck_model->getTrucks();

        return $this->load->view('manifest/manifest', $data);
    }

    public function listManifest($query_id = 0)
    {
    	$this->input->load_query($query_id);

        $data = $this->paginate($this->manifest_model, base_url("manifest/listManifest/$query_id"), $this->config->item('per_page_limit'), $query_id);

        return $this->load->view('manifest/list', $data);
    }

    public function search()
    {
        $query_array = array(
            'search_key' => $this->input->post('search_key'),
        );
        $query_id = $this->input->save_query($query_array);

        return redirect("manifest/listManifest/$query_id");
    }

    public function getDetails()
    {
        $manifest_number = $this->uri->segment(3);
        if (!$manifest_number) {
            return redirect("error/error_404");
        }

        $data['manifest_details'] = $this->manifest_model->getManifest($manifest_number);

        if (!empty($this->uri->segment(4))) {
            // if we're viewing collections
            if ($this->input->post('waybill_number')) {
                // SEARCH COLLECTIONS
                $data['manifest_waybills'] = $this->manifest_model->getManifestWaybills($manifest_number, true, $this->input->post('waybill_number'));
            } else {
                // COLLECTIONS DEFAULT
                $data['manifest_waybills'] = $this->manifest_model->getManifestWaybills($manifest_number, true);
            }
        } else {
            // COLLECTIONS STANDARD VIEW
            $data['manifest_waybills'] = $this->manifest_model->getManifestWaybills($manifest_number, false, null, false);
        }
        $data['grand_total']    = $this->manifest_model->getGrandTotal($manifest_number);
        $data['total_payments'] = $this->manifest_model->getTotalPayments($manifest_number);

        if (!empty($this->uri->segment(4))) {
            if ($this->input->is_ajax_request()) {
                echo $this->load->view('manifest/manifest_collections_ajax', $data, null, true);
            } else {
                return $this->load->view('manifest/manifest_collections', $data);
            }
        } else {
            return $this->load->view('manifest/manifest_details', $data);
        }
    }

    public function getWaybillCollection()
    {
        $waybill_number = $this->input->post('waybill_number');

        $data['manifest_details']  = $this->manifest_model->getManifest($manifest_number);
        $data['manifest_waybills'] = $this->manifest_model->getManifestWaybills($manifest_number, false, $waybill_number);
        $data['grand_total']       = $this->manifest_model->getGrandTotal($manifest_number);
        $data['total_payments']    = $this->manifest_model->getTotalPayments($manifest_number);

        return $this->load->view('manifest/manifest_collections', $data);
    }

    public function typeAhead()
    {
        $manifest_number = $this->uri->segment(3);
        if ($this->manifest_model->getWaybillCollection($manifest_number)) {
            $result['success'] = true;
            $result['result']  = $this->manifest_model->getWaybillCollection($manifest_number);
        }
        $return = array();
        foreach ($result['result'] as $row) {
            $return[] = $row->waybill_number;
        }
        $json = json_encode($return);

        print_r($json);
    }

    public function load()
    {
        $manifest_number = $this->uri->segment(3);
        $post_data       = $this->input->post('checkbox');
        $waybills        = $this->manifest_model->getManifestWaybills($manifest_number);

        $params          = array();
        $loaded_waybills = array();

        if ($waybills != null) {
            foreach ($waybills as $row) {
                $loaded_waybills[] = $row->waybill_number;
            }
            foreach ($post_data as $posted_data) {
                if (!in_array($posted_data, $loaded_waybills)) {
                    $params[] = $posted_data;
                }
            }
        } else {
            $params = $post_data;
        }

        $data['manifest_number'] = $manifest_number;
        $data['waybills']        = $params;

        if ($this->manifest_model->update($data)) {
            $result['success'] = true;
        }

        echo json_encode($result);
    }

    public function unload()
    {
        $waybill_number  = $this->input->post('waybill_number');
        $manifest_number = $this->input->post('manifest_number');

        if ($this->manifest_model->unload($waybill_number)) {
            $data['manifest_waybills'] = $this->manifest_model->getManifestWaybills($manifest_number);
            echo $this->load->view('manifest/truckload', $data, null, true);
        }
    }

    public function save()
    {
        $this->load->model('manifest_model');

        $this->form_validation->set_rules('truck', 'Truck', 'required');
        $this->form_validation->set_rules('driver', 'Driver', 'required');
        $this->form_validation->set_rules('trip_to', 'Trip To', 'required');

        $params['manifest_data'] = array(
            'truck_id'     => $this->input->post('truck'),
            'driver'       => ucwords($this->input->post('driver')),
            'trip_to'      => ucwords($this->input->post('trip_to')),
            'date'         => date('y-m-d H:i:s'),
            'processed_by' => $this->session->userdata('logged_in')['user_id'],
        );

        if ($this->form_validation->run()) {
            if (!empty($this->input->post('manifest_number'))) {
                $params['manifest_number'] = $this->input->post('manifest_number');
            }
            $output = $this->manifest_model->create($params);
            if ($output) {
                if (empty($this->input->post('manifest_number'))) {
                    $result['manifest_number'] = $output;
                }
                $result['success'] = true;
            }
        } else {
            $errors['truck']   = form_error('truck');
            $errors['driver']  = form_error('driver');
            $errors['trip_to'] = form_error('trip_to');

            if (!empty($errors)) {
                $result['success'] = false;
                $result['error']   = $errors;
            }
        }
        echo json_encode($result);
    }

    public function update()
    {
        $manifest_number = $this->uri->segment(3);
        if (!$manifest_number) {
            return redirect("error/error_404");
        }

        $data['manifest_details'] = $this->manifest_model->getManifest($manifest_number);

        $config             = array();
        $config['base_url'] = base_url() . 'manifest/update/' . $manifest_number;

        $config['per_page']    = 100;
        $config['uri_segment'] = 4;
        $page                  = $this->uri->segment(4);

        $result = $this->waybill_model->getUnloaded($page, $config['per_page']);

        $config['total_rows'] = $result['num_rows'];

        $this->pagination->initialize($config);

        $data['result'] = $result['rows'];
        $data['links']  = $this->pagination->create_links();

        $total_rows                = $this->pagination->total_rows;
        ($total_rows < 1) ? $start = 0 : $start = $page + 1;
        $end                       = $page + $this->pagination->per_page;

        if ($end > $total_rows) {
            $end = $total_rows;
        }

        $data['start'] = $start;
        $data['end']   = $end;
        $data['total'] = $total_rows;

        $this->load->model('truck_model');

        $data['trucks']            = $this->truck_model->getTrucks();
        $data['manifest_waybills'] = $this->manifest_model->getManifestWaybills($manifest_number, null, null, true);

        return $this->load->view('manifest/update_manifest', $data);
    }

    public function clear()
    {
        $params = $this->uri->segment(3);
        if ($this->manifest_model->clear($params)) {
            return redirect('manifest/update/' . $params);
        }
    }

    public function delete()
    {
        $session_data = $this->session->userdata('logged_in');
        if ($session_data['role'] != 'admin') {
            $this->session->set_flashdata('warning', 'You are not allowed to delete.');
            $result['success'] = false;
            $result['msg']     = "You are not allowed to delete.";
        } else {
            $data = $this->input->post('checkbox');
            for ($i = 0; $i < sizeof($data); $i++) {
                $delete = $this->manifest_model->delete($data[$i]);
                if ($delete) {
                    $result['success'] = true;
                } else {
                    $result['success'] = false;
                    $result['msg']     = "Oops! You cannot delete this manifest until all of its waybills are delivered.";
                }
            }
        }
        echo json_encode($result);
    }

    // public function delete(){
    //     $data     = $this->input->post('checkbox');
    //     $delete = $this->manifest_model->delete($data);

    //     if($delete){
    //         $result['success'] = TRUE;
    //     } else {
    //         $result['success'] = FALSE;
    //         $result['msg']       = "Oops! You cannot delete this manifest until all of its waybills are delivered.";
    //     }

    //     echo json_encode($result);
    // }

    public function printManifest()
    {
        $manifest_number           = $this->uri->segment(3);
        $data['manifest_details']  = $this->manifest_model->getManifest($manifest_number);
        $data['manifest_waybills'] = $this->manifest_model->getManifestWaybills($manifest_number, false);
        $data['grand_total']       = $this->manifest_model->getGrandTotal($manifest_number);

        return $this->load->view('manifest/manifest_print', $data);
    }

    public function printManifestCollections()
    {
        $manifest_number = $this->uri->segment(3);

        $data['manifest_details']  = $this->manifest_model->getManifest($manifest_number);
        $data['manifest_waybills'] = $this->manifest_model->getManifestWaybills($manifest_number, true);
        $data['grand_total']       = $this->manifest_model->getGrandTotal($manifest_number);
        $data['total_payments']    = $this->manifest_model->getTotalPayments($manifest_number);

        return $this->load->view('manifest/manifest_collections_print', $data);
    }

    public function export()
    {
        $manifest_number = $this->uri->segment(3);
        $query           = $this->manifest_model->getManifestWaybills($manifest_number, false);

        //load our new PHPExcel library
        $this->load->library('excel');
        $headings = array('Waybill', 'Consignee', 'Consignor', 'Prepaid', 'Collect', 'Remarks');
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('manifest');

        $rowNumber = 1;
        $col       = 'A';

        foreach ($headings as $heading) {
            $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $heading);
            $col++;
        }

        // Loop through the result set
        $rowNumber = 2;
        foreach ($query as $row) {
            $col = 'A';
            foreach ($row as $cell) {
                $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                $col++;
            }
            $rowNumber++;
        }

        // Freeze pane so that the heading line won't scroll
        $this->excel->getActiveSheet()->freezePane('A2');

        $filename = 'manifest.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        ob_end_clean();

        $objWriter->save('php://output');
    }

    public function exportCollections()
    {
        $manifest_number = $this->uri->segment(3);
        $query           = $this->manifest_model->getManifestWaybills($manifest_number, true);

        //load our new PHPExcel library
        $this->load->library('excel');
        $headings = array('Waybill', 'Consignee', 'Consignor', 'Prepaid', 'Collect', 'Balance Due');
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('manifest');

        $rowNumber = 1;
        $col       = 'A';

        foreach ($headings as $heading) {
            $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $heading);
            $col++;
        }

        // Loop through the result set
        $rowNumber = 2;
        foreach ($query as $row) {
            $col = 'A';
            foreach ($row as $cell) {
                $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, (empty($cell)) ? 0 : $cell);
                $col++;
            }
            $rowNumber++;
        }

        // Freeze pane so that the heading line won't scroll
        $this->excel->getActiveSheet()->freezePane('A2');

        $filename = 'manifest.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

        ob_end_clean();

        $objWriter->save('php://output');
    }

}
