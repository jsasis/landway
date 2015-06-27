<?php include "/../admin_lte_header.php"; ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Main content -->
		<section class="content">
			<div class='row'>
				<div class='col-md-12'>
					<!-- Manifest Details -->
					<div class='row'>
						<div class='col-md-6'>
							<h2>Manifest #	<?php echo $manifest_details['alpha'];?></h2>
							<h4><a href='<?php echo base_url();?>manifest/update/<?php echo $manifest_details['manifest_number'];?>'><i class='fa fa-edit'></i> Manage</a></small></h4>
							<h3><small>Loaded Waybills:</small> <label class='label label-warning'><?php $load = sizeof($manifest_waybills); echo ($load > 0) ? $load - 1 : $load ?></label></h3>
							<i>processed by <?php echo $manifest_details['processed_by'];?></i>
						</div>
						<div class='col-md-6 text-right'>
							<h2><?php echo $manifest_details['plate_number'];?> </h2>
							<h3><?php echo $manifest_details['driver'];?>		</h3>
							<h4><?php echo $manifest_details['trip_to'];?>		</h4>
							<i><?php echo date('F d, Y', strtotime($manifest_details['date']));?></i>
						</div>
					</div>
					<hr>
					<div class="box box-primary">
						<div class="box-body">
							<!-- Manifest Items -->
							<div class='row'>
								<div class='col-md-12'>
									<!-- CONTROL BUTTONS -->
									<div class='pull-right' style='margin-left: 5px'>
										<a href='javascript:void(0)' class="btn btn-default btn-sm" onclick="window.frames['print'].focus();window.frames['print'].print();"><span class='glyphicon glyphicon-print'></span> Print </a>
									</div>
									<div class='pull-right'>
										<a href='<?php echo base_url();?>manifest/export/<?php echo $manifest_details['manifest_number'];?>' class="btn btn-success btn-sm"><span class='glyphicon glyphicon-export'></span>  Export</a>
									</div>
									<div class='pull-left'><a href='<?php echo base_url();?>manifest/getDetails/<?php echo $manifest_details['manifest_number'];?>/collections' class='btn btn-default btn-sm'><i class='fa fa-exchange'></i> Switch to collections view</a></div>
									<div class='clearfix'></div>
									<hr>
									<!-- END OF CONTROL BUTTONS -->
								
									<!-- TABLE LISTINGS -->
										<h4 class='text-center label-primary'>ITEMS VIEW</h4>
										<table class='table table-striped table-condensed table-hover'>
											<thead class='custom'>
												<tr>
													<td>Waybill #</td>
													<td>Consignee</td>
													<td>Consignor</td>
													<td>Prepaid</td>
													<td>Collect</td>
													<td class='text-center'><i class='fa fa-bars'></i> Items</td>
												</tr>
											</thead>
											<tbody>
												<?php if($manifest_waybills):?>
												<?php $i = 0;?>
												<?php foreach($manifest_waybills as $row):?>
												<?php $i++;?>
													<tr>
														<?php if($row->waybill_number == 'TOTAL'):?>
														<td><strong>TOTAL</strong></td>
														<?php else:?>
														<td><a href='<?php echo base_url();?>waybill/getDetails/<?php echo $row->waybill_number;?>'><?php echo $row->waybill_number;?></a></td>
														<?php endif;?>
														<td><?php echo $row->consignee;?></td>
														<td><?php echo $row->consignor;?></td>
														<td><?php echo ((is_null($row->prepaid))) ? " 0.00" : " ".number_format($row->prepaid, 2, '.', ',') ;?></td>
														<td><?php echo ((is_null($row->collect))) ? " 0.00" : " ".number_format($row->collect, 2, '.', ',') ;?></td>
														<td class='text-center'><?php echo ((is_null($row->remarks))) ? "" : $row->remarks ;?></td>
														
													</tr>
												<?php endforeach;?>
												<?php else:?>
													<tr>
														<td colspan='7' class='text-center'><h3>No waybills loaded.</h3></td>
													</tr>
												<?php endif;?>
											</tbody>
										</table>
										<div class='row'>
											<div class='col-md-4 col-md-offset-8'>
												<div class="panel panel-primary">
													<div class="panel-heading">
														<i class='fa fa-book fa-5x in-image text-default'></i>
														<div class="in-bold"> <?php echo number_format($grand_total['grand_total'], 2, '.', ',');?><br></div>
														<div class="in-thin"> grand total  </div>
													</div>
												</div>
											</div>
										</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section><!-- /.content -->
		<!-- Iframe -->
		<iframe src='<?php echo base_url();?>manifest/printManifest/<?php echo $manifest_details['manifest_number'];?>' name='print' style='display:none;'></iframe>
	</div><!-- /.content-wrapper -->

<?php include "/../admin_lte_footer.php"; ?>

<script type='text/javascript'>
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(4)').addClass('active');
	});
	$('#myModal').on('show.bs.modal',function(e){
	    var waybill_number = $(e.relatedTarget).data('waybill-number');
	    $(e.currentTarget).find('.modal-title').html('Payment for <b class="text-primary text-right ">WAYBILL# '+ waybill_number +'</b>');
	    $(e.currentTarget).find('input[name="waybill_number"]').val(waybill_number);
	});
	$('#paymentForm').submit(function(e){
		e.preventDefault();
		var data = $(this).serialize();
		var manifest_details = parseInt($('input#manifest_number').val());

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
					$('.notification').fadeOut(2000);
					window.setTimeout(function(){
						window.location = '<?php echo base_url();?>manifest/getDetails/'+ manifest_details;
					}, 2000);
				}
			}
		});
	});
</script>
</body>
</html>
