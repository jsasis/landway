<html lang='en'>
<?php include_once('/../header.php');?>
<body>
<div class='row'>
	<div class='col-xs-10 col-xs-offset-1'>
		<legend><h2 class="text-center">Annual Income Report</h2></legend>
		<table class='table table-condensed table-bordered'>
			<thead style="background: rgb(60, 141, 188); color:#fff">
				<tr>
					<th>Truck</th>
					<th>Jan</th>
					<th>Feb</th>
					<th>March</th>
					<th>April</th>
					<th>May</th>
					<th>Jun</th>
					<th>Jul</th>
					<th>Aug</th>
					<th>Sep</th>
					<th>Oct</th>
					<th>Nov</th>
					<th>Dec</th>
					<th class="text-right">Total</th>
				</tr>
			</thead>
			<tbody>
			<?php if($result) : ?>
				<?php foreach($result as $row):?>
					<tr>
						<td style="width: 30px;"><?php echo $row->truck;?></td>
						<td style="width: 30px;"><?php echo $row->January;?></td>
						<td style="width: 30px;"><?php echo $row->February;?></td>
						<td style="width: 30px;"><?php echo $row->March;?></td>
						<td style="width: 60px;"><?php echo $row->April;?></td>
						<td style="width: 30px;"><?php echo $row->May;?></td>
						<td style="width: 30px;"><?php echo $row->June;?></td>
						<td style="width: 30px;"><?php echo $row->July;?></td>
						<td style="width: 30px;"><?php echo $row->August;?></td>
						<td style="width: 30px;"><?php echo $row->September;?></td>
						<td style="width: 30px;"><?php echo $row->October;?></td>
						<td style="width: 30px;"><?php echo $row->November;?></td>
						<td style="width: 30px;"><?php echo $row->December;?></td>
						<td style="width: 30px;" class="text-right"><?php echo $row->total;?></td>
					</tr>
				<?php endforeach;?>
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
					