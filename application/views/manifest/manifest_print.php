<?php include "/../admin_lte_header.php";?>
<body>
	<h1><strong>LANDWAY CARGO SERVICES</strong></h1>
	<p>MAIN BRANCH</p>
	<p>UNIT 6 1613 A. RIVERA ST., TONDO, MANILA</p>
	<p>TEL # 254-03-08</p>
	<p>TIN # 163-342-098</p>
	<div class='wrapper'>
		<div class='row '>
			<div class='col-md-12'>
				<div class='row'>
					<div class='col-md-12'>
						<div class='pull-left print-label-push'> OPERATOR NAME </div>
						<div class='pull-right'> MANIFEST NUMBER <?php echo $manifest_details['alpha'];?> </div>
					</div>
				</div>
				<div class='row'>
					<div class='col-md-12'>
						<div class='pull-left print-label-push'><label> ANTONIO VILLAMOR </label></div>
						<div class='pull-right'><label> # OF WAYBILLS <?php $load = sizeof($manifest_waybills); echo ($load > 0) ? $load - 1 : $load?> </label></div>
					</div>
				</div>
			</div>
		</div>
		<h1 class='dm'><b>DRIVER'S MANIFEST</b></h1>
		<div clas='row'>
			<div class='col-md-12'>
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
							<div class='pull-right'><p><?php echo date('M d, Y', strtotime($manifest_details['date']));?></p></div>
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
			<div class='col-md-12'>
				<table  class='table table-condensed table-justified table-bordered'>
					<thead class='custom'>
						<tr>
							<td>Waybill #</td>
							<td>Consignee</td>
							<td>Consignor</td>
							<td>Prepaid</td>
							<td>Collect</td>
							<td>Remarks</td>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td>GRAND TOTAL</td>
							<td></td>
							<td></td>
							<td></td>
							<td class="text-center"><?php echo number_format($grand_total['grand_total'], 2, '.', ',');?></td>
							<td></td>
						</tr>
					</tfoot>
					<tbody>
						<?php if($manifest_waybills): ?>
						<?php foreach($manifest_waybills as $row):?>
							<tr>
								<?php if($row->waybill_number == 'TOTAL'):?>
								<td>TOTAL</td>
								<?php else:?>
								<td><?php echo $row->waybill_number;?></td>
								<?php endif;?>
								<td><?php echo substr($row->consignee,0, 20);?></td>
								<td><?php echo substr($row->consignor,0, 20);?></td>
								<td class="text-center"><?php echo $row->prepaid;?></td>
								<td class="text-center"><?php echo $row->collect;?></td>
								<td><?php echo $row->remarks; ?></td>
								<!-- <td><?php //echo substr($row->remarks, 0, 45);?></td> -->
							</tr>
						<?php endforeach;?>
						<?php else:?>
							<tr>
								<td colspan='7' class='text-center'><h3>No waybills loaded.</h3></td>
							</tr>
						<?php endif;?>
					</tbody>
				</table>
				<div id="terms">
					<p style="line-height: 1em;">I hereby certify that the above recieved goods covered by the forgoing waybills or delivery
						to the consignees accoding to the particulars set forth herein and that I am reponsible for all
						the said goods until I have made complete delivery of the same collected the freight changes plus value
						of the goods or C.O.D shipments, if any turned over proceed thereof to LANDWAY CARGO SERVICES. |
						In case of losses, damages or accident or any of the above mentioned items, the trucking will replace
						or pay the corresponding amount of the said items within 15 days after notification. An additional sum of 25%
						of the amount TRANSACTION.|</p>
				</div>
				<div class='col-xs-12'>
					<div class='row'>
						<div class='pull-left print-label-push'><strong><?php echo $manifest_details['processed_by'];?></strong></div>
						<div class='pull-right'><strong><?php echo $manifest_details['driver'];?></strong></div>
					</div>
					<div class='row'>
						<div class='pull-left print-label-push'><p>Signature Over Printed Name</p></div>
						<div class='pull-right'><p>Driver's Signature</p></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>