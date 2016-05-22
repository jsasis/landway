<!-- Iframe -->
			<iframe name='print' id='i_frame' width='100%' height='100%' style='display:none'></iframe>
			<!-- Modal -->
			<div class="modal fade" id="updateDs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title" id="myModalLabel">Update Delivery Status</h4>
						</div>
						<div class="modal-body">
							<form method="post" action="<?php echo base_url();?>waybill/updateDeliveryStatus">
								<input type="hidden" name="waybill_number" value="">
								<select name="delivery_status" id="" class="form-control">
									<option value="Delivered">Delivered</option>
									<option value="Undelivered">Undelivered</option>
								</select>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								<button type="submit" class="btn btn-primary">Update</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- Modal DELETE -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title" id="myModalLabel">Add Payment</h4>
						</div>
						<div class="modal-body">
							<form id='paymentForm'>
								<input type='hidden' id='waybill_number' name='waybill_number'></input>
								<?php echo form_hidden('payment_terms', 'collect');?>
								<div class='form-group' id='amount'>
									<?php echo form_label('Enter Amount','',array('class'=>'control-label'));?>
									<?php echo form_input(array('id'=>'amount','name'=>'amount','class'=>'form-control','value'=> number_format($balance, 2, ".",",")));?>
								</div>
						</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								<button id="yes" type='button' class='btn btn-primary'>Yes</button>
							</form>
						</div>
					</div>
				</div>
			</div>