<?php
	if(!defined('BASEPATH')) exit('No direct script access allowed');
	class Manifest extends CI_Controller {

		function __construct() {
			parent::__construct();

			if(!$this->session->userdata('logged_in')){
				redirect('user');
			}

			$libraries 	= array(
					'pagination',
					'session',
					'form_validation'
				);
			$models		= array(
				'manifest_model',
				'waybill_model'
				);

			$this->load->library($libraries);
			$this->load->model($models);
		}

		function index(){
	
			$this->listManifest();
		}
		
		function show(){
			$this->load->model('truck_model');
			$data['trucks'] = $this->truck_model->getTrucks();

			$this->load->view('manifest/manifest', $data);
		}

		function listManifest($query_id = 0){
			$this->noCache();
			$this->input->load_query($query_id);
			$query_array = array(
				'search_key' => $this->input->get('search_key')
			);

			$config = array();
			$config['base_url'] = base_url()."manifest/listManifest/$query_id";
			$config['per_page'] = 100;
			$config['uri_segment'] = 4;

			$page 	= ($this->uri->segment(4)) ? $this->uri->segment(4) : 0 ;

			$result = $this->manifest_model->fetch($query_array, $config['per_page'], $page);
			
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
			

			$this->load->view('manifest/list', $data);
		}

		function search(){
			$query_array = array(
				'search_key' => $this->input->post('search_key')
			);
			$query_id = $this->input->save_query($query_array);

			redirect("manifest/listManifest/$query_id");
		}

		function getDetails(){
			$this->noCache();
			$manifest_number = $this->uri->segment(3);
			if(!$manifest_number) redirect("error/error_404");
			
			$data['manifest_details']	= $this->manifest_model->getManifest($manifest_number);			
		
			if(!empty($this->uri->segment(4))){ // if we're viewing collections
				if($this->input->post('waybill_number')){
					// SEARCH COLLECTIONS
					$data['manifest_waybills']	= $this->manifest_model->getManifestWaybills($manifest_number, TRUE, $this->input->post('waybill_number'));
				}else{
					// COLLECTIONS DEFAULT
					$data['manifest_waybills']	= $this->manifest_model->getManifestWaybills($manifest_number, TRUE);
				}
			}else{
				// COLLECTIONS STANDARD VIEW
				$data['manifest_waybills']	= $this->manifest_model->getManifestWaybills($manifest_number, FALSE, NULL, FALSE);
			}
			$data['grand_total']		= $this->manifest_model->getGrandTotal($manifest_number);
			$data['total_payments']		= $this->manifest_model->getTotalPayments($manifest_number);

			if(!empty($this->uri->segment(4))){
				if($this->input->is_ajax_request()){
					echo $this->load->view('manifest/manifest_collections_ajax', $data, NULL, TRUE);
				}else{
					$this->load->view('manifest/manifest_collections', $data);
				}
			}else{
				$this->load->view('manifest/manifest_details', $data);
			}
		}

		function getWaybillCollection(){
			$waybill_number = $this->input->post('waybill_number');

			$data['manifest_details']	= $this->manifest_model->getManifest($manifest_number);	
			$data['manifest_waybills']	= $this->manifest_model->getManifestWaybills($manifest_number, FALSE, $waybill_number);
			$data['grand_total']		= $this->manifest_model->getGrandTotal($manifest_number);
			$data['total_payments']		= $this->manifest_model->getTotalPayments($manifest_number);

			$this->load->view('manifest/manifest_collections', $data);
		}

		function typeAhead(){
			$manifest_number = $this->uri->segment(3);
			if($this->manifest_model->getWaybillCollection($manifest_number)){
				$result['success'] = true;
				$result['result'] = $this->manifest_model->getWaybillCollection($manifest_number);
			}
			$return = array();
			foreach($result['result'] as $row){
				$return[] = $row->waybill_number;
			}
			$json = json_encode($return);
			
			print_r($json);
		}

		function load(){
			$manifest_number= $this->uri->segment(3);
			$post_data		= $this->input->post('checkbox');
			$waybills		= $this->manifest_model->getManifestWaybills($manifest_number);
			
			$params			= array();
			$loaded_waybills= array();	

			if($waybills != NULL){
				foreach($waybills as $row){
					$loaded_waybills[] = $row->waybill_number;
				}
				foreach ($post_data as $posted_data){
					if(!in_array($posted_data, $loaded_waybills)){
						$params[] = $posted_data;
					}
				}
			}else{
				$params = $post_data;
			}
			
			$data['manifest_number']= $manifest_number;
			$data['waybills']		= $params;

			if($this->manifest_model->update($data)){
				$result['success'] = TRUE;
			}

			echo json_encode($result);
		}

		function unload(){
			$waybill_number  = $this->input->post('waybill_number');
			$manifest_number = $this->input->post('manifest_number');

			if($this->manifest_model->unload($waybill_number)){
				$data['manifest_waybills']	= $this->manifest_model->getManifestWaybills($manifest_number);
				echo $this->load->view('manifest/truckload', $data, NULL, TRUE);
			}
		}

		function save(){
			$this->load->model('manifest_model');

			$this->form_validation->set_rules('truck', 'Truck', 'required');
			$this->form_validation->set_rules('driver', 'Driver', 'required');
			$this->form_validation->set_rules('trip_to', 'Trip To', 'required');

			$params['manifest_data'] = array(
				'truck_id' 	=> $this->input->post('truck'),
				'driver'	=> ucwords($this->input->post('driver')),
				'trip_to'	=> ucwords($this->input->post('trip_to')),
				'date'		=> date('y-m-d H:i:s'),
				'processed_by'   => $this->session->userdata('logged_in')['user_id']
			);

			if($this->form_validation->run()){
				if(!empty($this->input->post('manifest_number'))){
					$params['manifest_number'] = $this->input->post('manifest_number');
				}
				$output = $this->manifest_model->create($params);
				if($output){
					if(empty($this->input->post('manifest_number'))){
						$result['manifest_number'] = $output;
					}
					$result['success'] = TRUE;
				}
			}else{
				$errors['truck']	= form_error('truck');
				$errors['driver'] 	= form_error('driver');
				$errors['trip_to']	= form_error('trip_to');

				if(!empty($errors)){
					$result['success']	= FALSE;
					$result['error']	= $errors;	
				}
			}
			echo json_encode($result);
		}

		function update(){
			$manifest_number = $this->uri->segment(3);
			if(!$manifest_number) redirect("error/error_404");

			$data['manifest_details']	= $this->manifest_model->getManifest($manifest_number);			
			
			$config = array();
			$config['base_url'] 	= base_url().'manifest/update/'.$manifest_number;
			
			$config['per_page'] 	= 100;
			$config['uri_segment']	= 4;
			$page = $this->uri->segment(4);

			$result = $this->waybill_model->getUnloaded($page, $config['per_page']);

			$config['total_rows'] 	= $result['num_rows'];

			$this->pagination->initialize($config);

			$data['result'] = $result['rows'];
			$data['links'] 	= $this->pagination->create_links();

			$total_rows = $this->pagination->total_rows;
			($total_rows < 1) ? $start = 0 : $start = $page + 1;
			$end 	= $page + $this->pagination->per_page;

			if($end > $total_rows){
			   	$end = $total_rows;
			}

			$data['start'] 	= $start;
			$data['end']	= $end;
			$data['total'] 	= $total_rows;

			$this->load->model('truck_model');

			$data['trucks'] = $this->truck_model->getTrucks();
			$data['manifest_waybills']	= $this->manifest_model->getManifestWaybills($manifest_number, NULL, NULL, TRUE);

			$this->load->view('manifest/update_manifest', $data);
		}

		function clear(){
			$params = $this->uri->segment(3);
			if($this->manifest_model->clear($params)){
				redirect('manifest/update/'.$params);
			}
		}
		
		function delete(){
			$data 	= $this->input->post('checkbox');
			$delete = $this->manifest_model->delete($data);

			if($delete){
				$result['success'] = true;
			}
			echo json_encode($result);
		}

		function printManifest(){
			$manifest_number 			= $this->uri->segment(3);
			$data['manifest_details']	= $this->manifest_model->getManifest($manifest_number);
			$data['manifest_waybills']	= $this->manifest_model->getManifestWaybills($manifest_number, FALSE);
			$data['grand_total']		= $this->manifest_model->getGrandTotal($manifest_number);

			$this->load->view('manifest/manifest_print', $data);
		}

		function printManifestCollections(){
			$manifest_number 			= $this->uri->segment(3);
			
			$data['manifest_details']	= $this->manifest_model->getManifest($manifest_number);
			$data['manifest_waybills']	= $this->manifest_model->getManifestWaybills($manifest_number, TRUE);
			$data['grand_total']		= $this->manifest_model->getGrandTotal($manifest_number);
			$data['total_payments']		= $this->manifest_model->getTotalPayments($manifest_number);

			$this->load->view('manifest/manifest_collections_print', $data);
		}

		function export(){
			$manifest_number = $this->uri->segment(3);
			$query = $this->manifest_model->getManifestWaybills($manifest_number, FALSE);

			//load our new PHPExcel library
			$this->load->library('excel');
			$headings = array('Waybill', 'Consignee', 'Consignor', 'Prepaid', 'Collect', 'Remarks');
			//name the worksheet
			$this->excel->getActiveSheet()->setTitle('manifest');
			
			$rowNumber = 1; 
			$col = 'A'; 

		   foreach($headings as $heading) { 
		      $this->excel->getActiveSheet()->setCellValue($col.$rowNumber, $heading); 
		      $col++; 
		   } 

			// Loop through the result set 
		    $rowNumber = 2; 
		    foreach ($query as $row) { 
		       $col = 'A'; 
		       foreach($row as $cell) { 
		          $this->excel->getActiveSheet()->setCellValue($col.$rowNumber, $cell); 
		          $col++; 
		       } 
		       $rowNumber++; 
		    } 

			// Freeze pane so that the heading line won't scroll 
		    $this->excel->getActiveSheet()->freezePane('A2'); 
			 
			$filename='manifest.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			             
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  

			ob_end_clean(); 

			$objWriter->save('php://output');
		}

		function exportCollections(){
			$manifest_number = $this->uri->segment(3);
			$query = $this->manifest_model->getManifestWaybills($manifest_number, TRUE);

			//load our new PHPExcel library
			$this->load->library('excel');
			$headings = array('Waybill', 'Consignee', 'Consignor', 'Prepaid', 'Collect', 'Balance Due');
			//name the worksheet
			$this->excel->getActiveSheet()->setTitle('manifest');
			
			$rowNumber = 1; 
			$col = 'A'; 

		   foreach($headings as $heading) { 
		      $this->excel->getActiveSheet()->setCellValue($col.$rowNumber,$heading); 
		      $col++; 
		   } 

			// Loop through the result set 
		    $rowNumber = 2; 
		    foreach ($query as $row) { 
		       $col = 'A'; 
		       foreach($row as $cell) { 
		          $this->excel->getActiveSheet()->setCellValue($col.$rowNumber, (empty($cell)) ? 0 : $cell); 
		          $col++; 
		       } 
		       $rowNumber++; 
		    } 

			// Freeze pane so that the heading line won't scroll 
		    $this->excel->getActiveSheet()->freezePane('A2'); 
			 
			$filename='manifest.xls'; //save our workbook as this file name
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
			header('Cache-Control: max-age=0'); //no cache
			             
			//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
			//if you want to save it as .XLSX Excel 2007 format
			$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  

			ob_end_clean(); 

			$objWriter->save('php://output');
		}
		
		private function noCache(){
			$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
			$this->output->set_header("Cache-Control: post-check=0, pre-check=0");
			$this->output->set_header("Pragma: no-cache");
		}

	}
	
?>