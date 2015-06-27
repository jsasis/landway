<div class='container'>
			<h2 class='manage text-info'><span class='glyphicon glyphicon-th-list'></span> Manage <small>Units</small></h2>
			<?php echo form_open('',array('id'=>'myForm'));?>

			<div class='row'>
				<div class='col-md-9'>
					<button type='submit' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-remove'></span> Delete</button>
					<a class='btn btn-primary btn-xs' href='<?php echo base_url();?>unit/addUnit'><span class='glyphicon glyphicon-plus'></span> New Unit</a>
				</div>
				<div class='col-md-3'>
					<div class='form-group'>
						<div class='input-group input-group'>
						  <input type='search' id='search' name='search' class='form-control' placeholder='Search by Description'>
						  <span class='input-group-addon'><span class='glyphicon glyphicon-search'></span></span>
						</div>
					</div>
				</div>
			</div>

			<div class='row'>
				<div class='col-md-12'>
					<table id='myTable' class='table table-bordered table-striped table-hover'>
						<thead>
							<tr class='text-default'>
								<th><input type='checkbox' id='checkAll'></input></th>
								<th>Action</td>
								<th>Unit Code</th>
								<th>Description</th>
								<th>Unit Cost</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($res)):?>
								<?php foreach($res as $row):?>
									<tr>
										<td width='30px'><input class='checkbox1' type='checkbox' name='checkbox[]' id='checkbox[]' value='<?php echo $row->unit_id;?>'></input></td>
										<td width='60px'><a>View</a> | <a href='<?php echo base_url();?>unit/delete/<?php echo $row->unit_id;?>'>Delete</a></td>
										<td><?php echo $row->unit_code;?></td>
										<td><?php echo $row->description;?></td>
										<td><?php echo $row->unit_cost;?></td>
									</tr>
								<?php endforeach;?>
								<?php echo form_close();?>
							<?php else:?>
								<tr><td colspan='5'><?php echo 'No Records Found.';?></td></tr>
							<?php endif;?>
						</tbody>
					</table>
				</div>
			</div>

			<div class='row'>
				<div class='col-md-12 text-right'>
					<?php echo $links;?>
				</div>
			</div>
			</form>
		</div>