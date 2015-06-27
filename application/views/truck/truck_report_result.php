<html lang='en'>
<?php include_once('/../header.php');?>
<body>
<div class='row'>
	<div class='col-xs-10 col-xs-offset-1'>
		<legend>
			<h2 class="text-center"><strong>Landway Cargo Services</strong></h2>
		</legend>
		<h4 class="text-center pull-left"><i class="fa fa-file-text-o"></i> Gross Income Report</h4>
		<h5 class="text-center pull-right"><strong><em><?php echo date('F d, Y', strtotime($start));?></em> - <em><?php echo date('F d, Y', strtotime($end));?></em></h5>
		<div class="cleafix"></div>
		<table class='table table-bordered'>
			<thead style="background: rgb(60, 141, 188); color:#fff">
				<tr>
					<th>Truck</th>
					<th>TOTAL</th>
				</tr>
			</thead>
			<tbody>
			<?php if($result) : ?>
				<?php foreach($result as $row):?>
					<tr>
						<td><?php echo $row->truck;?></td>
						<td><?php echo $row->total;?></td>
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
					