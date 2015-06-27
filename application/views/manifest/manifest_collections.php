<?php include "/../admin_lte_header.php"; ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Main content -->
		<section class="content">

			<div class='row'>
				<div class='col-md-12'>
					<!-- DETAILS -->
					<div class='row'>
						<div class='col-md-6'>
							<h2>Manifest #	<?php echo $manifest_details['alpha'];?></h2>
							<h4><a href='<?php echo base_url();?>manifest/update/<?php echo $manifest_details['manifest_number'];?>'><i class='fa fa-edit'></i> Manage</a></small></h4>
							<h3><small>Loaded Waybills:</small> <label class='label label-warning'><?php $load = sizeof($manifest_waybills); echo ($load > 0) ? $load - 1 : $load ?></label></h3>
							<h6><i>processed by <?php echo $manifest_details['processed_by'];?></i></h6>
						</div>
						<div class='col-md-6 text-right'>
							<h2><?php echo $manifest_details['plate_number'];?> </h2>
							<h3><?php echo $manifest_details['driver'];?>		</h3>
							<h4><?php echo $manifest_details['trip_to'];?>		</h4>
							<h5><?php echo date('F d, Y', strtotime($manifest_details['date']));?></h5>
						</div>
					</div>
					<div class="box box-primary">
						<div class="box-body">		
							<!-- ITEMS -->
							<div class='row'>
								<div class='col-md-12'>
									<div class='pull-left search-box' >
										<?php echo form_input(array('id'=>'search','size'=>'40px','name'=>'waybill_number','class'=>'form-control search','placeholder'=>'Search Waybill ...','autocomplete'=>'off',
										'onchange'=>"search()"));?>
									</div>
									<div class='pull-right' style='margin-left: 5px'>
										<a href='javascript:void(0)' class="btn btn-default btn-sm" onclick="window.frames['print'].focus();window.frames['printCollections'].print();"><span class='glyphicon glyphicon-print'></span> Print </a>
									</div>
									<div class='pull-right'>
										<a href='<?php echo base_url();?>manifest/exportCollections/<?php echo $manifest_details['manifest_number'];?>' class="btn btn-success btn-sm"><span class='glyphicon glyphicon-export'></span>  Export</a>
									</div>
									<div class='pull-right' style='margin-right: 30px'><a href='<?php echo base_url();?>manifest/details/<?php echo $manifest_details['manifest_number'];?>' class='btn btn-default btn-sm'><i class='fa fa-exchange'></i> Switch to items view</a></div>
									<div class='clearfix'></div>
									<hr>
									<!-- TABLE LISTINGS -->
									<h4 class='text-center label-danger'>COLLECTIONS VIEW</h4>
									<table class='table table-striped table-condensed table-hover' id='myTable'>
										<thead class='custom'>
											<tr>
												<td></td>
												<td>Waybill #</td>
												<td>Consignee</td>
												<td>Consignor</td>
												<td>Prepaid</td>
												<td>Collect</td>
												<td><i class='fa fa-book'></i> Total Amount</td>
												<td><i class='fa fa-calculator'></i> Total Payments</td>
												<td><i class='fa fa-circle-o-notch'></i> Balance </td>
											</tr>
										</thead>
										<tbody>
											<?php if($manifest_waybills):?>
											<?php foreach($manifest_waybills as $row):?>
												<tr>
													<?php if($row->waybill_number == 'TOTAL'):?>
													<td></td>
													<?php else:?>
														<?php if($row->balance > 0):?>
															<td><button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#myModal' data-waybill-number='<?php echo $row->waybill_number;?>' data-balance='<?php echo $balance;?>'><strong><i class='fa fa-book'></i></strong> Payment</button></td>
														<?php else:?>
															<td></td>
														<?php endif;?>
													<?php endif;?>
													<?php if($row->waybill_number == 'TOTAL'):?>
													<td><strong>TOTAL</strong></td>
													<?php else:?>
													<td><a href='<?php echo base_url();?>waybill/getDetails/<?php echo $row->waybill_number;?>'><?php echo $row->waybill_number;?></a></td>
													<?php endif;?>
													<td><?php echo substr($row->consignee,0, 20);?></td>
													<td><?php echo substr($row->consignor,0, 20);?></td>
													<td><?php echo number_format($row->prepaid, 2, '.', ',') ;?></td>
													<td><?php echo number_format($row->collect, 2, '.', ',') ;?></td>
													<?php if($row->waybill_number == 'TOTAL'):?>
													<td><?php echo number_format($row->total_due, 2, '.', ',');?></td>
													<?php else:?>
													<td><?php echo number_format($row->total_payment, 2, '.', ',');?></td>
													<?php endif;?>
													<td style='background: #ECECEC'><?php echo number_format($row->total_payment, 2, '.', ',') ;?></td>
													<td style='background: #E4E4E4; font-size: 15px;'><strong><?php echo number_format($row->balance, 2, '.', ',');?></strong></td>
												</tr>
											<?php endforeach;?>
											<?php else:?>
												<tr>
													<td colspan='7' class='text-center'><h3>No records found.</h3></td>
												</tr>
											<?php endif;?>
										</tbody>
									</table>
									<div class='row'>
										<div class='col-md-4'>
											<div class="panel panel-default">
												<div class="panel-heading">
													<i class='fa fa-calculator fa-5x in-image text-info'></i>
													<div class="in-bold"> <?php echo number_format($total_payments['payments'], 2, '.', ',');?><br></div>
													<div class="in-thin"> total collections  </div>
												</div>
											</div>
										</div>
										<div class='col-md-4'>
											<div class="panel panel-info">
												<div class="panel-heading">
													<i class='fa fa-credit-card fa-5x in-image text-default'></i>
													<div class="in-bold"> <?php echo number_format($grand_total['grand_total'] - $total_payments['payments'], 2, '.', ',');?><br></div>
													<div class="in-thin"> to be collected  </div>
												</div>
											</div>
										</div>
										<div class='col-md-4'>
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
			<!-- Iframe -->
			<iframe src='<?php echo base_url();?>manifest/printManifest/<?php echo $manifest_details['manifest_number'];?>' name='print' style='display:none'></iframe>
			<iframe src='<?php echo base_url();?>manifest/printManifestCollections/<?php echo $manifest_details['manifest_number'];?>' name='printCollections' style='display:none'></iframe>
			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			        <h4 class="modal-title" id="myModalLabel">Add Payment</h4>
			      </div>
			      <div class="modal-body">
			      	<?php echo form_open('',array('id'=>'paymentForm'));?>
				      		<input type='hidden' id='waybill_number' name='waybill_number'></input>
				      		<input type='hidden' name='payment_terms' value='collect'></input>
				      	<div class='form-group' id='amount'>
				      		<?php echo form_label('Enter Amount','', array('class'=>'control-label'));?>
					      	<?php echo form_input(array('id'=>'amount','name'=>'amount','class'=>'form-control','placeholder'=>'Enter Amount'));?>
				      	</div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			        <button type='submit' class='btn btn-primary'>Save</button>
			        <?php echo form_close();?>
			      </div>
			    </div>
			  </div>
			</div>
		</section><!-- /.content -->

	</div><!-- /.content-wrapper -->

<?php include "/../admin_lte_footer.php";?>

<script type='text/javascript'>
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(4)').addClass('active');
	});

	var showloader = false;
	$(document).ajaxStart(function(){
	    if (showloader){
	        $('body').loader('show');
	    }
	});    
	$(document).ajaxComplete(function(){
	    window.setTimeout("$('body').loader('hide')", 1000);
	    showloader = false;
	});
	$('#myModal').on('show.bs.modal',function(e){
	    var waybill_number = $(e.relatedTarget).data('waybill-number');
	    var balance 	   = $(e.relatedTarget).data('balance');

	    $(e.currentTarget).find('.modal-title').html('WAYBILL# '+ waybill_number);
	    $(e.currentTarget).find('#balance').html(numeral(balance).format('0.00'));
	    $(e.currentTarget).find('input[name="waybill_number"]').val(waybill_number);
	    $(e.currentTarget).find('input#amount').val(numeral(balance).format('0.00'));
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
					$('.notification').fadeOut(800);
					window.setTimeout(function(){
						window.location = '<?php echo base_url();?>manifest/getDetails/<?php echo $manifest_details["manifest_number"];?>/collections';
					}, 800);
				}
			}
		});
	});
	/* Typeahead for waybill search*/
	$('.search').typeahead({
		source: function (query, process) {
			$.ajax({
				type: 'post',
				url: "<?php echo base_url();?>manifest/typeAhead/<?php echo $manifest_details['manifest_number'];?>",
				dataType: 'json',
				success: function(result){
					process(result);
					console.log(result);
				},
			});
		},
		matcher: function (item) {
			if (item.toLowerCase().indexOf(this.query.trim().toLowerCase()) != -1) {
				return true;
			}
		},
		highlighter: function (item) {
			var regex = new RegExp( '(' + this.query + ')', 'gi' );
			return item.replace( regex, "<strong>$1</strong>" );
		}
	});
	/* Function search waybill */
	function search(){
		var data = $('#search').serialize();
		var manifest_number = <?php echo $manifest_details['manifest_number'];?>;
		
		showloader = true;

		$.ajax({
			type: 'post',
			url: '<?php echo base_url();?>manifest/getDetails/<?php echo $manifest_details["manifest_number"];?>/collections',
			data: data,
			success: function(response){
				if(response){
					$('#myTable').html(response);
				}
			}
		});
	}
</script>

</body>
</html>

