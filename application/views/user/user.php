<?php echo form_open('',array('id'=>'myForm'));?>
<table class='table table-hover' id='myTable'>
	<thead>
		<tr>
			<th><input type='checkbox' id='checkAll'></input></th>
			<th>First Name</th>
			<th class='text-right'>Last Name</th>
			<th class='text-center'>Username</th>
			<th class='text-center'>Password</th>
			<th class='text-right'>Permission</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($result)):?>
			<?php foreach($result as $row):?>
				<tr>
					<td width='30px'><input class='row' type='checkbox' name='checkbox[]' id='checkbox[]' value='<?php echo $row->user_id;?>'></input></td>
					<td><?php echo $row->first_name;?></td>
					<td class='text-right'><?php echo $row->last_name;?></td>
					<td class='text-center'><?php echo $row->username;?></td>
					<td class='text-center'><?php echo $row->password;?></td>
					<td class='text-right'><?php echo $row->user_type;?></td>
					<td class='text-right'>
						<div class="btn-group">
							<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<strong> Action </strong><span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo base_url();?>user/edit/<?php echo $row->user_id;?>">Edit</a></li>
								<li class="divider"></li>
								<li><a href="<?php echo base_url();?>user/change/password/<?php echo $row->user_id;?>">Change Password</a></li>
							</ul>
						</div>
					</td>
				</tr>
			<?php endforeach?>
		<?php else:?>
			<tr>
				<td colspan='9'><?php echo 'No record/s found.';?></td>
			</tr>
		<?php endif ?>
	</tbody>
</table>
<?php echo form_close();?>
