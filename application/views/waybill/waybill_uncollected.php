<?php include "/../admin_lte_header.php"; ?>
	
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class='pull-left'> Uncollected Waybills</h1>
			<h1 class='pull-right'><a href='<?php echo base_url();?>waybill/add'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
			<div class='clearfix'></div>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class='row'>
				<div class='col-md-12'>
					<?php if($this->session->flashdata('notification')):?>
						<div class='alert alert-success'><?php echo $this->session->flashdata('notification');?></div>
					<?php endif?>
					<div class="box box-primary">
						<div class="box-body">
							<?php echo form_open('',array('id'=>'myForm'));?>
							<table class='table table-striped table-condensed table-hover' id='myTable'>
								<thead>
									<tr>
										<th>					Waybill #</th>
										<th>					Consignee</th>
										<th class='text-right'>	Consignor</th>
										<th class='text-center'>Date</th>
										<th>Balance</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($result)):?>
										<?php foreach($result as $row):?>
										<tr>
											<td><a href='<?php echo base_url();?>waybill/getDetails/<?php echo $row->waybill_number;?>'><?php echo $row->waybill_number;?></a></td>
											<td>					<?php echo $row->consignee;?></td>
											<td class='text-right'>	<?php echo $row->consignor;?></td>
											<td class='text-center'><?php echo date('M d, Y', strtotime($row->transaction_date));?></td>
											<td> <?php echo $row->balance;?></td>
											<td class='text-right'>
												<div class="btn-group">
													<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														<strong> Action </strong><span class="caret"></span>
													</button>
													<ul class="dropdown-menu" role="menu">
														<li><a href="<?php echo base_url();?>waybill/getDetails/<?php echo $row->waybill_number;?>">View</a></li>
														<li><a href="<?php echo base_url();?>waybill/update/<?php echo $row->waybill_number;?>">Edit</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<?php endforeach?>
									<?php else:?>
										<tr>
											<td colspan='9'><?php echo 'No record/s found.';?></td>
										</tr>
									<?php endif ?>
								</tbody>
							</table>
							<?php echo form_close();?>
						</div>
					</div>
					<?php echo $links;?>
					<div class='pull-right'>
						<label class='control-label'><strong>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $total;?> results</strong></label>
					</div>
				</div>
			</div>
		</section><!-- /.content -->
	</div><!-- /.content-wrapper -->
	
<?php include "/../admin_lte_footer.php";?>
<script>
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(3)').addClass('active');
	});
</script>
</body>
</html>