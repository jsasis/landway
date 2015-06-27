<html lang='en'>
<?php include_once('/../header.php');?>
<body>
	<div class="wrapper">
		<div class="row">
			<div class="col-md-12">
				<table class='table table-hover table-condensed table-striped' id='myTable'>
					<thead>
						<tr>
							<th>                    Waybill #</th>
							<th>                    Consignee</th>
							<th class='text-right'>Consignor</th>
							<th class='text-center'>Transaction Date</th>
							<th><h4><label class='label label-warning'>Balance Due</label></h4></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($result)):?>
							<?php foreach($result as $row):?>
							<tr>
								<td><?php echo $row->waybill_number;?></td>
								<td><?php echo $row->consignee;?></td>
								<td class='text-right'><?php echo $row->consignor;?></td>
								<td><?php echo ($row->is_backload) ? 'Yes' : 'No';?></td>
								<td class='text-center'><?php echo date('M d, y', strtotime($row->transaction_date));?></td>
								<td class='text-left'>&#8369; <?php $balance = $row->total - $row->payment; echo number_format($balance, 2, '.', ',');?></td>
							</tr>
							<?php endforeach?>
						<?php else:?>
							<tr>
								<td colspan='9'><?php echo 'No record/s found.';?></td>
							</tr>
						<?php endif ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
<script type='text/javascript'>
$(document).ready(function(e){
	window.print();
});
</script>