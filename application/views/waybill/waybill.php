<!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<h3><i class="fa fa-angle-right"></i> Manage Waybills</h3>
		<div class="row mt">
			<div class="col-lg-12">
				<div class="content-panel">
					<!-- Flash messages -->
					<?php if($this->session->flashdata('notification')): ?>
						<div class='alert alert-success'><?php echo $this->session->flashdata('notification');?></div>
					<?php elseif($this->session->flashdata('warning')): ?>
						<div class='alert alert-danger'><?php echo $this->session->flashdata('warning');?></div>
					<?php endif?>

					<!-- Action Buttons -->
					<h4 class="pull-left">
						<button data-toggle="tooltip" title="Delete" data-placement="bottom" id='delete' class='btn btn-danger btn-sm'><i class='fa fa-minus-circle'></i> Delete</button>
						<a data-toggle="tooltip" title="Print" data-placement="bottom" class='btn btn-default btn-sm' onclick='printByBatch()'><span class='glyphicon glyphicon-print no-print'></span> Print</a>
					</h4>

					<!-- Search box -->
					<form  id='search_form' action='<?php echo base_url();?>waybill/search' method='POST' class="navbar-form pull-right">
						<div class="input-group">
							<?php echo form_input(array('id'=>'search_key','name'=>'search_key','class'=>'form-control','placeholder'=>'Search ...','autocomplete'=>'off'));?>
							<span class="input-group-btn">
								<button type="submit" class="btn btn-default">Search</button>
								<button type="button" id="datepicker" class="btn btn-info btn-flat datepicker" style="height: 34px" onclick="showDatepicker()"><i class='fa fa-calendar'></i></button>
							</span>
						</div>
					</form>
					
					<!-- Data table -->
					<section id="unseen">
						<?php echo form_open('',array('id'=>'myForm')); ?>

						<table id="myTable" class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th><input type='checkbox' id='checkAll'></th>
									<th>                    Waybill #</th>
									<th>                    Consignee</th>
									<th class='text-right'> Consignor</th>
									<th class='text-left'>Loading Status</th>
									<th class="text-left">Delivery Status</th>
									<th class='text-center'>Date</th>
									<th>Total Due</th>
									<!-- <th>Balance</th> -->
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($result)):?>
									<?php foreach($result as $row): $delivery_status = $row->delivery_status; ?>
										<tr>
											<td><input class='row' type='checkbox' name='checkbox[]' id='checkbox' value="<?php echo $row->waybill_number;?>"></td>
											<td><a href='<?php echo base_url();?>waybill/details/<?php echo $row->waybill_number;?>'><?php echo $row->waybill_number;?></a></td>
											<td><?php echo $row->consignee;?></td>
											<td class='text-right'><?php echo $row->consignor;?></td>
											<td style='vertical-align:middle' class='text-center'>
												<?php if($row->plate_number):?>
													<a href='<?php echo base_url();?>manifest/details/<?php echo $row->manifest_number;?>'>
														<?php echo '<label class="label label-success">'.$row->plate_number.'</label>';?>
													</a>
												<?php elseif ($row->status == 'Loaded') : ?>
													<label class="label label-warning"><?php echo $row->status;?></label>
												<?php else:?>
													<?php echo '<label class="label label-danger">'. $row->status .'</label>';?>
												<?php endif;?></td>
												<?php if($delivery_status == 'Undelivered') :?>
													<td class="text-center"><a href="javascript:void(0);"><i class="fa fa-minus-circle text-danger" data-toggle="tooltip" title="Not yet Delivered" data-placement="bottom"></i></a></td>
												<?php elseif($delivery_status == 'Delivered'): ?>
													<td class="text-center"><a href="javascript:void(0);"><i class="fa fa-check-circle text-success" data-toggle="tooltip" title="Delivered" data-placement="bottom"></i></a></td>
												<?php else: ?>
													<td></td>
												<?php endif; ?>
												<td class='text-center'><?php echo date('F d, Y', strtotime($row->transaction_date));?></td>
												<td> <?php echo number_format($row->total, 2, '.', ',');?></td>
												<!-- <td><strong><?php //$balance = $row->total - $row->payment; echo number_format($balance, 2, '.', ',');?></strong></td> -->
												<td class='text-center'>
													<div class="btn-group">
														<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
															<strong> Action </strong><span class="caret"></span>
														</button>
														<ul class="dropdown-menu" role="menu">
															<li><a href="<?php echo base_url();?>waybill/details/<?php echo $row->waybill_number;?>">View</a></li>
															<li><a href="<?php echo base_url();?>waybill/update/<?php echo $row->waybill_number;?>">Edit</a></li>
															<?php if($row->status != "Received"): ?>
																<li class="divider"></li>
																<li><a href="#" data-toggle='modal' data-target='#updateDs' data-waybill-number="<?php echo $row->waybill_number;?>">Update Delivery Status</a></li>
															<?php endif; ?>
														</ul>
													</div>
												</td>
											</tr>
										<?php endforeach?>
									<?php else:?>
										<tr>
											<td colspan='8'><?php echo 'No record/s found.';?></td>
										</tr>
									<?php endif ?>
								</tbody>
							</table>
						</table>

						<?php echo form_close(); ?>
					</section>
				</div><!-- /content-panel -->
			</div><!-- /col-lg-4 -->			
		</div><!-- /row -->

	</section><! --/wrapper -->
</section><!-- /MAIN CONTENT -->

</section><!-- /CONTAINER -->

