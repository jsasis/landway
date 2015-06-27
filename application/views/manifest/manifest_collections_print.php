<?php include "/../admin_lte_header.php";?>
<body>
	<h1><strong>LANDWAY CARGO SERVICES</strong></h1>
	<p>MAIN BRANCH</p>
	<p>UNIT 6 1613 A. RIVERA ST., TONDO, MANILA</p>
	<p>TEL # 254-03-08</p>
	<p>TIN # 163-342-098</p>
	
	<div class='wrapper'>
		<div class='row '>
			<div class='col-xs-12'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='pull-left print-label-push'> OPERATOR NAME </div>
						<div class='pull-right'> MANIFEST NUMBER <?php echo $manifest_details['alpha'];?> </div>
					</div>
					
				</div>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='pull-left print-label-push'><label> ANTONIO VILLAMOR </label></div>
						<div class='pull-right'><label> # OF WAYBILLS <?php $load = sizeof($manifest_waybills); echo ($load > 0) ? $load - 1 : $load?> </label></div>
					</div>
				</div>
			</div>
		</div>
		<h1 class='dm'><b>DRIVER'S MANIFEST</b></h1>
		<div clas='row'>
			<div class='col-xs-12'>
				<div class='row'>
					<div class='col-xs-4'>
						<div class='row'>
							<div class='pull-left'><p>DRIVER</p></div>
							<div class='pull-right'><p><?php echo $manifest_details['driver'];?></p></div>
						</div>
						<div class='row'>
							<div class='pull-left'><p>TRIP TO</p></div>
							<div class='pull-right'><p><?php echo $manifest_details['trip_to'];?></p></div>
						</div>
					</div>
					<div class='col-xs-4 col-xs-offset-4'>
						<div class='row'>
							<div class='pull-left'><p>DATE</p></div>
							<div class='pull-right'><p><?php echo $manifest_details['date'];?></p></div>
						</div>
						<div class='row'>
							<div class='pull-left'><p>PLATE #</p></div>
							<div class='pull-right'><p><?php echo $manifest_details['plate_number'];?></p></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='col-xs-12'>
				<table class='table table-condensed table-bordered'>
					<thead class='custom'>
						<tr>
							<td>Waybill #</td>
							<td>Consignee</td>
							<td>Consignor</td>
							<td>Prepaid</td>
							<td>Collect</td>
							<td>Total Amount</td>
							<td>Total Payments</td>
							<td>Balance</td>
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
								<td><?php echo $row->waybill_number;?>-<?php echo str_pad($i, 3, '0', STR_PAD_LEFT);?></td>
								<?php endif;?>
								<td><?php echo substr($row->consignee,0, 20);?></td>
								<td><?php echo substr($row->consignor,0, 20);?></td>
								<td><?php echo $row->prepaid;?></td>
								<td><?php echo $row->collect;?></td>
								
								<?php if($row->waybill_number == 'TOTAL'):?>
								<td><?php echo number_format($row->total_due, 2, '.', ',');?></td>
								<?php else:?>
								<td><?php echo number_format($row->total_payment, 2, '.', ',');?></td>
								<?php endif;?>
								<td><?php echo number_format($row->total_payment, 2, '.', ',') ;?></td>
								<td><strong><?php echo number_format($row->balance, 2, '.', ',');?></strong></td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
							<tr>
								<td colspan='7' class='text-center'><h3>No waybills yet</h3></td>
							</tr>
						<?php endif;?>
					</tbody>
				</table>
				<p><b>GRAND TOTAL &#8369;<?php echo number_format($grand_total['grand_total'], 2, '.', ',');?></b></p>
				<p><b>TOTAL PAYMENTS &#8369;<?php echo number_format($total_payments['payments'], 2, '.', ',');?></b></p>
				<p><b>UNCOLLECTED &#8369; <?php echo number_format($grand_total['grand_total'] - $total_payments['payments'], 2, '.', ',');?></b></p>
				<br>
				<br>
				<br>
				<div class='col-xs-12'>
					<div class='row'>
						<div class='pull-left print-label-push'><b><?php echo $manifest_details['processed_by'];?></b></div>
						<div class='pull-right'><b><?php echo $manifest_details['driver'];?></b></div>
					</div>
					<div class='row'>
						<div class='pull-left print-label-push'><p>Proccesed By</p></div>
						<div class='pull-right'><p>Driver's Signature</p></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>