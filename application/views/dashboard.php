<?php include_once('/header.php');?>
<?php include_once('/nav.php');?>
	<div id='page-wrapper'>
		<div class="row">
			<div class="col-md-12 main">
				<legend><h1 class='text-info manage'><i class='fa fa-dashboard'></i> Dashboard</h1></legend>
				<div class='clearfix'></div>
				<div class="wrapper">
					<div class="row">
					    <div class="col-lg-3 col-md-6">
					        <div class="panel panel-primary">
					            <div class="panel-heading">
					                <div class="row">
					                    <div class="col-xs-3">
					                        <i class="fa fa-comments fa-5x"></i>
					                    </div>
					                    <div class="col-xs-9 text-right">
					                        <div class="huge">26</div>
					                        <div>New Comments!</div>
					                    </div>
					                </div>
					            </div>
					            <a href="#">
					                <div class="panel-footer">
					                    <span class="pull-left">View Details</span>
					                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					                    <div class="clearfix"></div>
					                </div>
					            </a>
					        </div>
					    </div>
					    <div class="col-lg-3 col-md-6">
					        <div class="panel panel-green">
					            <div class="panel-heading">
					                <div class="row">
					                    <div class="col-xs-3">
					                        <i class="fa fa-tasks fa-5x"></i>
					                    </div>
					                    <div class="col-xs-9 text-right">
					                        <div class="huge"><?php echo $total_prepaid['total_prepaid']; ?></div>
					                        <div>Prepaid Collection</div>
					                    </div>
					                </div>
					            </div>
					            <a href="#">
					                <div class="panel-footer">
					                    <span class="pull-left">View Details</span>
					                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					                    <div class="clearfix"></div>
					                </div>
					            </a>
					        </div>
					    </div>
					    <div class="col-lg-3 col-md-6">
					        <div class="panel panel-yellow">
					            <div class="panel-heading">
					                <div class="row">
					                    <div class="col-xs-3">
					                        <i class="fa fa-shopping-cart fa-5x"></i>
					                    </div>
					                    <div class="col-xs-9 text-right">
					                        <div class="huge"><?php echo $count_received;?></div>
					                        <div>Received</div>
					                    </div>
					                </div>
					            </div>
					            <a href="#">
					                <div class="panel-footer">
					                    <span class="pull-left">View Details</span>
					                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					                    <div class="clearfix"></div>
					                </div>
					            </a>
					        </div>
					    </div>
					    <div class="col-lg-3 col-md-6">
					        <div class="panel panel-red">
					            <div class="panel-heading">
					                <div class="row">
					                    <div class="col-xs-3">
					                        <i class="fa fa-support fa-5x"></i>
					                    </div>
					                    <div class="col-xs-9 text-right">
					                        <div class="huge"><?php echo $count_uncollected['total_rows'];?></div>
					                        <div>Uncollected</div>
					                    </div>
					                </div>
					            </div>
					            <a href="<?php echo base_url();?>waybill/getUncollected">
					                <div class="panel-footer">
					                    <span class="pull-left">View Details</span>
					                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					                    <div class="clearfix"></div>
					                </div>
					            </a>
					        </div>
					    </div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="panel panel-primary">
								<div class='panel-heading'>
									Received Waybills
								</div>
								<table class='table table-bordered'>
									<thead>
										<tr>
											<th>Waybill #</th>
											<th>Consignee</th>
											<th>Consignor</th>
											<th>Date</th>
										</tr>
									</thead>
									<tbody>
									<?php if($received_waybills):?>
										<?php foreach($received_waybills as $waybill):?>
										<tr>
											<td><?php echo $waybill->waybill_number;?></td>
											<td><?php echo $waybill->consignee;?></td>
											<td><?php echo $waybill->consignor;?></td>
											<td><?php echo date('m-d-Y', strtotime($waybill->transaction_date));?></td>
										</tr>
										<?php endforeach;?>
									<?php else:?>
										<td colspan='4' class='text-center'>No records found.</td>
									<?php endif;?>
									</tbody>
								</table>
								<div class='panel-footer text-right'>
									<a href='<?php echo base_url();?>waybill'><i class='fa fa-arrow-right'></i> View Waybills </a>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel panel-primary">
								<div class='panel-heading'>Current Rates</div>
								<table class='table table-bordered table-condensed'>
									<thead>
										<tr>
											<th>Item</th>
											<th>Cost</th>
										</tr>
									</thead>
									<tbody>
									<?php if($current_rates):?>
										<?php foreach($current_rates as $rate):?>
										<tr>
											<td><?php echo $rate->description;?></td>
											<td><?php echo $rate->unit_cost;?></td>
										</tr>
										<?php endforeach;?>
									<?php else:?>
										<td colspan='2' class='text-center'>No records found.</td>
									<?php endif;?>
									</tbody>
								</table>
								<div class="panel-footer text-right">
									<a href='<?php echo base_url();?>unit_category'><i class='fa fa-arrow-right'></i> View All Item Rates </a>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-primary">
								<div class="panel-heading">Uncollected Waybills</div>
								<table class='table table-striped table-condensed table-hover' id='myTable'>
									<thead>
										<tr>
											<th>					Waybill #</th>
											<th>					Consignee</th>
											<th class='text-right'>	Consignor</th>
											<th class='text-center'>Transaction Date</th>
											<th>Balance Due</th>
										</tr>
									</thead>
									<tbody>
										<?php if(!empty($uncollected_waybills)):?>
											<?php foreach($uncollected_waybills as $row):?>
											<tr>
												<td><a href='<?php echo base_url();?>waybill/getDetails/<?php echo $row->waybill_number;?>'><?php echo $row->waybill_number;?></a></td>
												<td>					<?php echo $row->consignee;?></td>
												<td class='text-right'>	<?php echo $row->consignor;?></td>
												<td class='text-center'><?php echo date('M d, Y', strtotime($row->transaction_date));?></td>
												<td>&#8369; <?php echo $row->balance;?></td>
											</tr>
											<?php endforeach?>
										<?php else:?>
											<tr>
												<td colspan='9'><?php echo 'No record/s found.';?></td>
											</tr>
										<?php endif ?>
									</tbody>
								</table>
								<div class="panel-footer text-right">
									<a href='<?php echo base_url();?>waybill/getUncollected'><i class='fa fa-arrow-right'></i> View All Uncollected Waybills </a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
 </div>
</body>
</html>