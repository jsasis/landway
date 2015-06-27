<?php include "/../admin_lte_header.php"; ?>
	
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class='pull-left'> Prepaid Waybills</h1>
			<h1 class='pull-right'><a href='<?php echo base_url();?>waybill/add'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
			<div class='clearfix'></div>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="box box-primary">
				<div class="box-body">
					<div class='row'>
						<div class='col-md-12'>
							<table class='table table-striped table-condensed table-hover' id='myTable'>
								<thead>
									<tr>
										<th> Waybill #</th>
										<th> Consignee</th>
										<th class='text-right'> Consignor</th>
										<th class='text-center'>Terms</th>
										<th class='text-center'>Date</th>
										<th class='text-center'>Total</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($result)):?>
									<?php foreach($result as $row):?>
									<tr>
										<td>
											<a href='<?php echo base_url();?>waybill/getDetails/<?php echo $row->waybill_number;?>'>
												<?php echo $row->waybill_number;?></a>
										</td>
										<td>
											<?php echo $row->consignee;?></td>
										<td class='text-right'>
											<?php echo $row->consignor;?></td>
										<td class='text-center'>
											<?php echo $row->payment_terms;?></td>
										<td class='text-center'>
											<?php echo date( 'M d, Y', strtotime($row->transaction_date));?></td>
										<td class='text-center'>
											<?php echo $row->total;?></td>
										<td class='text-right'>
											<div class="btn-group">
												<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
													<strong> Action </strong><span class="caret"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li><a href="<?php echo base_url();?>waybill/getDetails/<?php echo $row->waybill_number;?>">View</a>
													</li>
													<li><a href="<?php echo base_url();?>waybill/update/<?php echo $row->waybill_number;?>">Edit</a>
													</li>
												</ul>
											</div>
										</td>
									</tr>
									<?php endforeach?>
									<?php else:?>
									<tr>
										<td colspan='9'>
											<?php echo 'No record/s found.';?>
										</td>
									</tr>
									<?php endif ?>
								</tbody>
							</table>	
						</div><!-- 
						<div class="col-md-4">
							<div class="panel panel-primary">
								<div class='panel-heading'>
									<p class="pull-left"> Prepaid Collection</p>
									<p class="pull-right"><?php echo date('F d, Y'); ?></p>
									<div class="clearfix"></div> </div>
								<div class="panel-body">
									<legend>
										<div class="well">
											<h3 class="pull-left"><strong> TOTAL </strong>
										</h3>
										<h3 class='pull-right'><strong> &#8369; <span id="total"> 1,500.00 </span> </strong></h3>
										<div class="clearfix"></div>
								</div>
								</legend>
								<form id="searchForm">
									<div class="form-group" id="start_date">
										<label>From</label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control datepicker" id="start_date">
										</div>
									</div>
									<div class="form-group" id="end_date">
										<label>To</label>
										<div class="input-group">
											<div class="input-group-addon"><i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control datepicker" id="end_date">
										</div>
									</div>
									<button type="submit" class="btn btn-success">Submit</button>
								</form>
							</div>
						</div> -->
					</div>
				</div>
			</div>
			<?php echo $links;?>
			<div class='pull-right'>
				<label class='control-label'><strong>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $total;?> results<strong></label>
			</div>
		</section><!-- /.content -->
	</div><!-- /.content-wrapper -->
	
<?php include "/../admin_lte_footer.php";?>
<script>
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(3)').addClass('active');

		$('.datepicker').datepicker({
			autoclose: true,
			format: 'yyyy-mm-dd'
		});
	});

	$('#searchForm').submit(function (e) {
			e.preventDefault();
			var data = {
				start_date: $('input#start_date').val(),
				end_date: $('input#end_date').val()
			};
			$('div#start_date, div#end_date').removeClass('has-error');
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>waybill/computePrepaid',
				data: data,
				dataType: 'json',
				success: function (response) {
					if (response.success) {
						$('span#total').text(response.result.total_prepaid);
					} else {
						if (response.error.db_error) {
							alert(response.error.db_error);
						}
						if (response.error.start_date) {
							$('div#start_date').addClass('has-error');
						}
						if (response.error.end_date) {
							$('div#end_date').addClass('has-error');
						}
					}
				}
			});
		});
</script>

</body>
</html>
		