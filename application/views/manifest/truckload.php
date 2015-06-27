<?php /*$session = $this->session->userdata('truckLoad');
	if($session !== FALSE){
		foreach($session as $row){
			echo $row->waybill_number;
		}
	}else{
		echo "<p>Session EMPTY</p>";
		
	}*/
?>


<?php if($manifest_waybills):?>
<?php foreach($manifest_waybills as $row):?>
	<tr>
		<td><a onclick='unload("<?php echo $row->waybill_number;?>")'><span class='glyphicon glyphicon-remove text-danger'></span></a></td>
		<td><?php echo $row->waybill_number;?></td>
		<td><?php echo $row->consignee;?></td>
		<td><?php echo $row->consignor;?></td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td colspan='4' class='text-center'><h2>Truck is empty. Load waybills below.</h2></td>
	</tr>
<?php endif;?>

<!-- <div>
<?php $session = $this->session->userdata('truckLoad');?>
<?php if($session != FALSE):?>
<?php foreach($session as $row):?>
	<tr>
		<td><a onclick='unload("<?php echo $row->waybill_number;?>")'><span class='glyphicon glyphicon-remove text-danger'></span></a></td>
		<td><?php echo $row->waybill_number;?></td>
		<td><?php echo $row->consignee;?></td>
		<td><?php echo $row->consignor;?></td>
	</tr>
<?php endforeach;?>
<?php else:?>
	<tr>
		<td><h4>TRUCK IS EMPTY</h4></td>
	</tr>
<?php endif;?>
</div> -->