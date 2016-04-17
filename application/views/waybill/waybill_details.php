<?php include "/../admin_lte_header.php";
	$waybill_number = $row['waybill_number'];
?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class='col-md-12'>
					<div class="box box-primary">
						<div class="box-body">
							<div class='row'>
								<div class='col-md-4 details'>
									<h2>Waybill # <strong><?php echo $row['waybill_number'];?></strong></h2>
									<div class="meta">
										<p><i><?php echo date('l, F d, Y H:i A',strtotime($row['transaction_date']));?></i></p>
										<?php if($row['status'] == "Loaded") :?>
										<p><?php echo $row['status'];?> to <?php echo $row['truck']; ?></p>
										<?php endif ;?>
										<p class="backload"><?php if($row['is_backload']) { echo "<label class='label label-info'>Backload</label>"; }?></p>
									</div>
									<?php if($row['status'] != "Received"): ?>
									<?php $delivery_status = $row['delivery_status'];?>
									<?php if($delivery_status == "Undelivered") :?>
									<h4><i class="fa fa-exclamation-triangle text-danger"></i> Undelivered</>
									<?php else: ?>
									<h4><i class="fa fa-check-circle text-success"></i> Delivered</h4>
									<?php endif; ?>
									<?php endif; ?>
									<?php $balance = intval($row['total']) - intval($amountPaid->amount);?>
									<h3>Balance <strong><?php echo number_format($balance, 2, '.', ',');?></strong></h3>
								</div>
								<div class='col-md-4 details'>
									<div class='text-left'>
										<h3 class="heading-customer"><small class="small-customer">CONSIGNEE</small></h3>
										<h3 style='margin-bottom:-5px'><strong><?php echo $row['consignee'];?></strong></h3>
										<h4><?php echo $row['address1'];?></h4>
									</div>
								</div>
								<div class='col-md-4 details'>
									<div class='text-left'>
										<h3 class="heading-customer"><small class="small-customer">CONSIGNOR</small></h3>
										<h3 style='margin-bottom:-5px'><strong><?php echo $row['consignor'];?></strong></h3>
										<h4><?php echo $row['address2'];?></h4>
										<?php if($row["notes"]): ?>
										<div class="callout callout-info">
											<h4><i>Note :</i></h4>
											<p><?php echo $row["notes"];?></p>
										</div>
										<?php endif;?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
						  <li class="active"><a href="#tab_1" data-toggle="tab">Items</a></li>
						  <li><a href="#tab_2" data-toggle="tab">Payment</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_1">
								<?php $waybill_number = $row['waybill_number'];?>
								<?php if(empty($resultItems)):?>
								<?php echo "<p><h4 class='text-danger  well text-center'><span class='glyphicon glyphicon-info-sign'></span> No items found.<p></h4>";?>
								<?php else:?>           
								<table class='table table-hover'>
									<thead>
										<tr>
											<th>Qty</th>
											<th>Unit</th>
											<th>Cost</th>
											<th class='text-center'>Description</th>
											<th class='text-center'>Total</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<td colspan='4' class='text-center'>* * * Nothing Follows * * *</td>
											<td id='total' class='text-center'><strong><?php echo number_format($row['total'],'2','.',',');?></strong></td>
										</tr>
									</tfoot>
									<tbody>
										<?php foreach($resultItems as $row):?>
										<tr id="1">
											<input type='hidden' id='hidden' name='id[]'></input>
											<td style='width:60px' class='quantity'>
												<?php echo $row->quantity;?>
											</td>
											<td style='width:70px' class='unit'>
												<?php echo $row->unit_code;?>
											</td>
											<td style='width:100px' class='unit_price'>
												<?php echo $row->unit_cost;?>
											</td>
											<td class='item description'>
												<?php echo $row->item_description;?>
											</td>
											<td class='price text-center text-justified' id='price' style='vertical-align:middle'><strong> <?php echo number_format(($row->quantity * $row->unit_cost),'2','.',',');?></strong></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
								<?php endif;?>
							</div><!-- /.tab-pane -->
						 	<div class="tab-pane" id="tab_2">
								<!-- Payments -->
								<div class="box-header">
									<button id='delete' class='btn btn-sm btn-danger'><i class="fa fa-trash"></i> Delete</button>
									<?php if($balance > 0): ?>
									<div class='pull-right'>
										<button class='btn btn-success' data-toggle='modal' data-target='#myModal' data-waybill-number='<?php echo $waybill_number;?>'>
											<span class='glyphicon glyphicon-plus'></span> Add Payment
										</button>
									</div>
									<?php endif;?>
								</div>
								<table class='table'>
									<form id='deleteForm'>
									<thead>
										<tr>
											<th><input type='checkbox' id='checkAll'></th>
											<th>Payment #</th>
											<th class='text-right' >Amount</th>
											<th class='text-center'>Date</th>
										</tr>
									</thead>
									<tbody>
										<?php if(!empty($payments)):?>
											<?php foreach($payments as $row):?>
											<tr>
												<td><input class='row' type='checkbox' name='checkbox[]' id='checkbox[]' value="<?php echo $row->payment_id;?>"></input></td>
												<td><?php echo $row->payment_id;?></td>
												<td class='text-right'> <?php echo $row->amount;?></strong></td>
												<td class='text-center'><?php echo date('l, M d, Y g:i A',strtotime($row->date));?></td>
											</tr>
											<?php endforeach?>
										<?php else:?>
											<tr>
												<td colspan='9'><?php echo 'No payments yet.';?></td>
											</tr>
										<?php endif ?>
									</tbody>
									</form>
								</table>
						  	</div><!-- /.tab-pane -->
						</div><!-- /.tab-content -->
					</div>
					<div class="box-footer">
						<a href="<?php echo base_url();?>waybill/update/<?php echo $waybill_number;?>" class='btn btn-primary btn-sm no-print'><i class='fa fa-edit'></i> Edit</a>
						<a class='btn btn-default btn-sm' id='print'><i class='fa fa-print no-print'></i> Print</a>
						<a href="<?php echo $base_url;?>waybill" class="btn btn-default btn-sm pull-right">Back to List</a>
					</div>
					
				</div>
			</div>	
		</section><!-- /.content -->
		<!-- IFrame Print View -->
		<iframe id='iframe' name='print' width='100%' height='100%' style='display:none'></iframe>
		<!-- Modal -->
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
							<button type='submit' class='btn btn-primary'>Save</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal Delete-->
		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="myModalLabel">Confirm</h4>
					</div>
					<div class="modal-body">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button id='yes' type="button" class="btn btn-primary">Yes</button>
					</div>
				</div>
			</div>
		</div>
	</div><!-- /.content-wrapper -->
	
<?php include "/../admin_lte_footer.php";?>

<script type='text/javascript'>
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(3)').addClass('active');
	});

	var showloader = false;
	$(document).ajaxStart(function(){
		if (showloader){
			$('body').loader('show');
		}
	});    
	$(document).ajaxComplete(function(){
		setTimeout("$('body').loader('hide')", 800);
		showloader = false;
	});
	$('#checkAll').on('click',function(){
		if(this.checked){
			$('.row').each(function(){
				this.checked = true;
			});
		}else{
			$('.row').each(function(){
				this.checked = false;
			});
		}
	});
	$('#delete').on('click',function(e){
		e.preventDefault();
		var data = $('#deleteForm').serialize();
		if(data == ""){
			$('#deleteModal .modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h4>');
			$('#deleteModal .modal-body').html('<p>Please select record/s to be deleted.</p>');
			$('#deleteModal .modal-footer').hide();
		}else{
			$('#deleteModal .modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Confirm Delete</h4>');
			$('#deleteModal .modal-body').html('<p>Are you sure you want to delete?</p>');
			$('#deleteModal .modal-footer').show();
		}
		
		$('#deleteModal').modal();

		$('#yes').click(function(){
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>payment/delete',
				data: data,
				success: function(result){
					window.location = "<?php echo base_url();?>waybill/getDetails/<?php echo $waybill_number;?>";
				}
			});
		});
	});
	$('#myModal').on('show.bs.modal',function(e){
		 var waybill_number = $(e.relatedTarget).data('waybill-number');
		 $('#amount').focus();
		 $(e.currentTarget).find('.modal-title').html('WAYBILL # '+ waybill_number);
		 $(e.currentTarget).find('input[name="waybill_number"]').val(waybill_number);
	});
	$('#paymentForm').submit(function(e){
			e.preventDefault();
			var data = $(this).serialize();

			$('#payment_terms, #amount').removeClass('has-error');
			$('#payment_terms .error-message, #amount .error-message').empty();

			$.ajax({
				type: 'post',
				data: data,
				url:  '<?php echo base_url();?>payment/save',
				dataType: 'json',
				success: function(response){
					if(!response.success){
						if(response.error.payment_terms){
							$('#payment_terms').addClass('has-error');
							$('#payment_terms .error-message').html(response.error.payment_terms);
						}
						if(response.error.amount){
							$('#amount').addClass('has-error');
							$('#amount .error-message').html(response.error.amount);
						}
					}else{
						$('#paymentForm')[0].reset();
						$('#myModal').modal('hide');
						$('.notification').html("<strong><h4><span class='glyphicon glyphicon-ok'></span><span id='message'> Payment has been saved!</span></h4></strong>");
						$('.notification').slideDown('slow');
						$('.notification').fadeOut(800);
						window.setTimeout(function(){
							window.location = "<?php echo base_url();?>waybill/getDetails/<?php echo $waybill_number;?>";
						}, 800);
					}
				}
			});
	});
	$('#print').click(function(){
		$.ajax({
			type: 'POST',
			url: "<?php echo base_url();?>waybill/printWaybill/<?php echo $waybill_number;?>",
			success: function(result){
				$('#iframe').attr('srcdoc', result);
			}
		});	
	});
</script>

</body>
</html>