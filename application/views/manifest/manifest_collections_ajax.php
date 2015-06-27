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
		<?php $i = 0;?>
		<?php foreach($manifest_waybills as $row):?>
			<tr>
				<?php if($row->waybill_number == 'TOTAL'):?>
				<td></td>
				<?php else:?>
				<td><button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#myModal' data-waybill-number='<?php echo $row->waybill_number;?>'><strong><i class='fa fa-book'></i></strong> Payment</button></td>
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
				<td colspan='9' class='text-center'><p>No records found.</p></td>
			</tr>
		<?php endif;?>
	</tbody>
</table>