<html lang='en'>
<?php include_once('/../header.php');?>
<body>
<div class='row'>
	<div class='col-xs-10 col-xs-offset-1'>
		<legend>
			<h2 class="text-center"><strong>Landway Cargo Services</strong></h2>
		</legend>
		<h4 class="text-center"><i class="fa fa-file-text-o"></i> Total Received Report</h4>
		<table class='table table-bordered'>
			<thead style="background: rgb(60, 141, 188); color:#fff">
				<tr>
					<th>Period</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
			<?php if($result) : ?>
				<tr>
					<td><?php echo date('F d, Y', strtotime($start));?></em> - <em><?php echo date('F d, Y', strtotime($end));?></td>
					<td><?php echo $result; ?></td>
				</tr>
				
			<?php else : ?>
				<tr>
					<td colspan="2"><?php echo "No records to show."; ?></td>
				</tr>
			<?php endif;?>
			</tbody>
		</table>
		<button class="btn btn-default btn-sm hidden-print" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
	</div>
</div>
</body>
</html>
					