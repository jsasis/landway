<?php if(!empty($result)):?>
	<?php foreach($result as $row):?>
		<tr>
			<td width="30px"><input class='checkbox1' type='checkbox' name='checkbox[]' id='checkbox[]' value='<?php echo $row->unit_id;?>'></input></td>
			<td><a>View</a> | <a href='<?php echo base_url();?>unit/delete/<?php echo $row->unit_id;?>'>Delete</a></td>
			<td><?php echo $row->unit_code;?></td>
			<td><?php echo $row->description;?></td>
		</tr>
	<?php endforeach;?>
<?php else:?>
	<tr><td colspan='5'><?php echo "<strong class='text-info'>No Record/s Found</strong>";?></td></tr>
<?php endif;?>