<html lang='en'>
<?php include_once('/../header.php');?>
<body>
	<?php $pages = 0;?>
	<?php foreach($rows as $row):?>
		<h1 class='text-center'><strong>LANDWAY CARGO SERVICES</strong></h1>
		<div id="address">
			<p class='text-center'>MAIN OFFICE : UNIT 6 163 A.RIVERA ST. TONDO, MANILA TEL# (02)254-03-08</p>
			<p class='text-center'>NAGA BRANCH : BOMBO RADYO COMPOUND, DIVERSION ROAD, TABUCO, NAGA CITY TEL# (054)472-62-67</p>
		</div>
		<div class='wrapper'>
			<h2 class="text-center" style="margin-top: -15px; font-weight:bold">WAYBILL</h2>
			<!-- Customer Info -->
			<div class='row'>
				<div class='col-xs-6 details' style='padding-left:30px'>
					<div class='row'>
						<div class='pull-left'><p>DATE:</p></div>
						<div class='pull-right'><p><?php echo date('M d, Y', strtotime($row['transaction_date']));?></p></div>
					</div>
					<div class='row'>
						<div class='pull-left'><p  class='larger'>WAYBILL #:</p></div>
						<div class='pull-right'><p class='larger'><?php $waybill_number =  $row['waybill_number']; echo $waybill_number;?></p></div>
					</div>
					<div class='row'>
						<div class='pull-left'><p class="larger">Consignor:</p></div>
						<div class='text-right'><p class="larger"><?php echo $row['consignor'];?></p></div>
					</div>
					<div class='row'>
						<div class='pull-left'><p>Address:</p></div>
						<div class='pull-right'><p><?php echo $row['address2'];?></p></div>
					</div>
				</div>
				<div class='col-xs-6 details' style='padding-left:30px;padding-right:30px'>
					<div class='row'>
						<div class='pull-left'><p  class='larger'>Payment Terms:</p></div>
						<div class='pull-right'><p  class='larger'><?php echo $row['payment_terms'];?></p></div>
					</div>
					<div class='row'>
						<div class='pull-left'><p class="larger">Consignee:</p></div>
						<div class='pull-right'><p class="larger"><?php echo $row['consignee'];?></p></div>
					</div>
					<div class='row'>
						<div class='pull-left'><p>Address:</p></div>
						<div class='pull-right'><p><?php echo $row['address1'];?></p></div>
					</div>
					<?php if($row['notes']):?>
					<div class='row'>
						<div class='pull-left'><p>Note:</p></div>
						<div class='pull-right'><p><?php echo $row['notes'];?></p></div>
					</div>
					<?php endif;?>
				</div>
			</div>
			<div class="clearfix"></div>
			<!-- Item Details -->
			<div class='row'>
				<div class='col-xs-9 backload'>
					<?php if($row['is_backload']):?>
						<p class='text-center backload'>Backload</p>
					<?php endif;?>
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th class="text-center">Qty</th>
								<th class="text-center">Cost</th>
								<th class="text-center">Unit</th>
								<th class="text-center">Description</th>
								<th class='text-center'>TOTAL</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td class='text-right'><strong>TOTAL</strong></td>
								<td id='total' class='text-right'><strong><?php echo number_format($row['total'],'2','.',',');?></strong></td>
							</tr>
						</tfoot>
						<tbody>
							<?php for($i = 0; $i < count($resultItems); $i++):?>
								<?php if($resultItems[$i]->waybill_number == $row['waybill_number']):?>
									<tr id="1">
										<input type='hidden' id='hidden' name='id[]'></input>
										<td style='width:30px;' class='quantity text-center'>
											<?php echo $resultItems[$i]->quantity;?>
										</td>
										<td style='width:30px;' class='unit_price text-center'>
											<?php echo $resultItems[$i]->unit_cost;?>
										</td>
										<td style='width:30px;' class='unit text-center'>
											<?php echo $resultItems[$i]->unit_code;?>
										</td>
										<td style='width: 200px' class='item description text-center'>
											<?php echo $resultItems[$i]->item_description;?>
										</td>
										<td style='width:100px' class='price text-right' id='price'><strong><?php echo number_format($resultItems[$i]->sub_total,'2','.',',');?></strong></td>
									</tr>
								<?php endif;?>
							<?php endfor;?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Terms and Conditions -->
			<div class="row" id="terms">
				<div class="col-xs-12" >
					<p>I hereby declare that the contents of myshipment are true contents and received the above mentioned in good order</p>
					<p>and conditioned worth and its not restricted or hazardous or prohibited materials</p>
				</div>
			</div>
			<!-- Footer -->
			<div class="row" id="footer">
				<div class="col-xs-6">
					<div class="underline"></div>
					<p class="text-center">Shipper</p>
					<p class="text-center">Signature Over Printed Name</p>
				</div>
				<div class="col-xs-6">
					<div class="underline"></div>
					<p class="text-center">Consignee</p>
					<p class="text-center">Signature Over Printed Name</p>
				</div>
			</div>
		</div>
		<?php $pages++;?>
		<?php if($pages % 2 == 0):?>
			<div class='page-break' style='display: block; page-break-before: always; background:red'></div>
		<?php else:?>
			<p class="separator"></p>
		<?php endif;?>
	<?php endforeach;?>
	
</body>
</html>
<script type='text/javascript'>
$(document).ready(function(e){
	window.print();
});
</script>