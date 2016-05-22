<!-- MAIN CONTENT -->
<section id="main-content">
	<section class="wrapper">
		<h3 class="pull-left"><i class="fa fa-angle-right"></i> Create Waybill</h3>
		<div class='pull-right'><a href='<?php echo base_url();?>customer/add' class='btn btn-success btn-lg'><i class='fa fa-plus-circle'></i> Customer</a></div>
		<div class="row">
			<div class="col-lg-12">
				<div class="form-panel">
					<!-- Flash messages -->
					<?php if($this->session->flashdata('notification')): ?>
						<div class='alert alert-success'><?php echo $this->session->flashdata('notification');?></div>
					<?php elseif($this->session->flashdata('warning')): ?>
						<div class='alert alert-danger'><?php echo $this->session->flashdata('warning');?></div>
					<?php endif?>

					<?php echo form_open('',array('id'=>'myForm', 'class'=>'form-horizontal'));?>
					<input type="hidden" name="status" value="Received">	
					<!-- Customer Details -->
					<div class='row'>
						<!-- Consignee -->
						<div class='col-md-6'>
							<div class='form-group' id='consignee'>
								<?php $isConsignee = true;?>
								<input type='hidden' id='ce_id' name='ce_id'>
								<?php echo form_label('Consignee','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
								<div class='col-md-9'>
									<?php echo form_input(array('id'=>'consignee','name'=>'consignee','class'=>'form-control customer consignee','placeholder'=>'Consignee', 'autocomplete'=>'off', 'onchange'=>"setCustomerData('<?php echo $isConsignee;?>')"));?>
									<span class='error-message control-label'></span>
								</div>
							</div>
							<div class='form-group' id='address_1'>
								<?php echo form_label('Address','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
								<div class='col-md-9'>
									<?php echo form_input(array('id'=>'address_1','name'=>'address_1','class'=>'form-control','placeholder'=>'Consignee Address'));?>
									<span class='error-message control-label'></span>
								</div>
							</div>
							<div class='form-group' id='notes'>
								<?php echo form_label('Notes','',array('class'=>'control-label col-md-2'));?>
								<div class='col-md-9'>
									<textarea id='notes' name='notes' class='form-control' placeholder='(Optional)' rows='5'></textarea>
								</div>
							</div>
						</div>
						<!-- Consignor -->
						<div class='col-md-6'>
							<div class='form-group' id='consignor'>
								<input type='hidden' id='cr_id' name='cr_id'>
								<?php echo form_label('Consignor','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
								<div class='col-md-9'>
									<?php echo form_input(array('id'=>'consignor','name'=>'consignor','class'=>'form-control customer consignor','placeholder'=>'Consignor', 'autocomplete'=>'off', 'onchange'=>'setCustomerData()'));?>
									<span class='error-message control-label'></span>
								</div>
							</div>
							<div class='form-group' id='address_2'>
								<?php echo form_label('Address','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
								<div class='col-md-9'>
									<?php echo form_input(array('id'=>'address_2','name'=>'address_2','class'=>'form-control','placeholder'=>'Consignor Address'));?>
									<span class='error-message control-label'></span>
								</div>
							</div>
							<div class='form-group' id='dr_number'>
								<?php echo form_label('CI/DR #','',array('class'=>'control-label col-md-2'));?>
								<div class='col-md-5'>
									<?php echo form_input(array('id'=>'dr_number','name'=>'dr_number','class'=>'form-control','placeholder'=>'CI/DR #'));?>
									<span class='error-message control-label'></span>
								</div>
							</div>
							<div class='form-group' id='is_backload'>
								<?php echo form_label('Backload','',array('class'=>'control-label col-md-2'));?>
								<div class='col-md-4'>
									<?php $data = array(0=>'No',1=>'Yes');?>
									<?php $attrib = "class='form-control'";?>
									<?php echo form_dropdown('is_backload', $data, 0, $attrib);?>
									<span class='error-message control-label'> </span>
								</div>
							</div>
						</div>
					</div>		
					<!-- Items -->
					<div class="row">
						<div class='col-md-8'>
							<table class='table table-hover'>
								<thead>
									<tr>
										<th></th>
										<th>Qty <span class='text-danger'>*</span></th>
										<th>Unit <span class='text-danger'>*</span></th>
										<th>Cost <span class='text-danger'>*</span></th>
										<th>Item Description <span class='text-danger'>*</span></th>
										<th class='text-center'>Sub-Total </th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan='4'><button id='addMore' class='btn btn-sm btn-info'><span class='glyphicon glyphicon-plus'></span></button></td>
										<td class='text-center'><strong>GRAND TOTAL</strong></td>
										<td id='total' class='text-center'><strong> 0.00</strong></td>
									</tr>
								</tfoot>
								<tbody id='wrapper'>
									<input type='hidden' id='total' name='total'>
									<tr id="1">
										<input type='hidden' id='hidden' name='id[]'>
										<td style='width:30px'> </td>
										<td style='width:100px' class='quantity'>
											<?php echo form_input(array('id'=>'qty','name'=>'quantity[]','class'=>'form-control','placeholder'=>'Qty', 'autocomplete'=>'off', 'onkeyup'=>"setVal(null, true)",'data-toggle'=>'popover','data-placement'=>'top','data-content'=>'Please enter quantity.'));?>
										</td>
										<td style='width:200px' class='unit'>
											<?php echo form_input(array('id'=>'unit','name'=>'unit[]','class'=>'form-control typeahead','placeholder'=>'Unit','onchange'=>'getUnit(null)'));?>
										</td>
										<td style='width:100px' class='unit_price'>
											<?php echo form_input(array('id'=>'unit_price','name'=>'unit_price[]','class'=>'form-control', 'placeholder'=>'Cost', 'onkeyup'=>"setVal(null, false)"));?>
										</td>
										<td class='item_description'>
											<?php echo form_input(array('id'=>'item_description','name'=>'item_description[]','class'=>'form-control', 'placeholder'=>'Item Description'));?>
										</td>
										<td class='price text-center' id='price' style='vertical-align:middle'><strong> 0.00</strong></td>
										<input type='hidden' id='sub_total' name='sub_total[]'>
									</tr>
								</tbody>
							</table>
						</div> <!-- /.items -->
						<div class="col-md-4">
							<div class="row">
								<div class="col-md-10 col-md-offset-1">
									<div class='form-group' id='payment_terms'>
										<?php echo form_label('Terms','',array('class'=>'control-label'));?>
										<?php $data = array('prepaid'=>'Prepaid','collect'=>'Collect');?>
										<?php $attrib = "class='form-control'";?>
										<?php echo form_dropdown('payment_terms', $data, 'collect', $attrib);?>
										<span class='error-message control-label'> </span>
									</div>
									<div id='payment'>
										<div class='form-group' id='amount'>
											<?php echo form_label('Amount','' , array('class'=>'control-label'));?>
											<?php echo form_input(array('id'=>'amount','name'=>'amount','class'=>'form-control','placeholder'=>'Enter Amount'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Action Buttons -->
					<div class="row">
						<div class="col-md-12">
							<div class="box-footer text-right">
								<a class='btn btn-default' href="<?php echo base_url();?>waybill">Cancel</a>
								<button id='save' class='btn btn-success'>Save</button>
							</div>
						</div>
					</div>
					<?php echo form_close();?>
				</div><!-- /content-panel -->
			</div><!-- /col-lg-4 -->			
		</div><!-- /row -->
	</section><! --/wrapper -->
</section><!-- /MAIN CONTENT -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-info-sign"></span> Unknown Customer</h4>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> No</button>
			</div>
		</div>
	</div>
</div> <!-- /.modal -->

</section><!-- /CONTAINER 