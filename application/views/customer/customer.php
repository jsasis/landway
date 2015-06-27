<?php include "/../admin_lte_header.php"; ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1 class="pull-left">Manage Customers</h1>
					<h1 class="pull-right"><a href='<?php echo base_url();?>customer/add'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
					<div class="clearfix"></div>
				</section>

				<!-- Main content -->
				<section class="content">
					<div class='row'>
						<div class='col-md-12'>
							<div class="box box-primary">
								<div class="box-header with-border">	
									<div class="pull-left">
										<button id='delete' class="btn btn-sm btn-danger"><i class='fa fa-minus-circle'></i> Delete</button>
									</div>
									<form action='<?php echo base_url();?>customer/searchCustomer' method='POST' class="navbar-form pull-right" role="search">
									   <div class="input-group">
									   	<?php echo form_input(array('id'=>'name','name'=>'name','class'=>'form-control','placeholder'=>'Search Customer ...'));?>
									   	<span class="input-group-btn"><button type='submit' class='btn btn-default' id='search'>Go</button></span>
									   </div>
									</form>
								</div>
								<div class="box-body">
									<?php if($this->session->flashdata('notification')):?>
										<div class='alert alert-success'><?php echo $this->session->flashdata('notification');?></div>
									<?php endif?>
									<?php echo form_open('',array('id'=>'myForm'));?>
									<table class='table table-hover' id='myTable'>
										<thead>
											<tr>
												<th><input type='checkbox' id='checkAll'></input></th>
												<th>Name</th>
												<th>Address</th>
												<th>Contact Number</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php if(!empty($result)):?>
												<?php foreach($result as $row):?>
												<?php $name 			= $row->name;?>
												<?php $complete_address = $row->complete_address;?>
												<?php $contact_number 	= $row->contact_number;?>
												<tr>
													<td width='30px'><input class='row' type='checkbox' name='checkbox[]' id='checkbox[]' value='<?php echo $row->customer_id;?>'></input></td>
													<td><?php echo $row->name;?></td>
													<td><?php echo $row->complete_address;?></td>
													<td><?php if($row->contact_number == null){ echo "N/A";}echo $contact_number;?></td>
													<td><a href='<?php echo $base_url;?>customer/update/<?php echo $row->customer_id;?>'><i class='fa fa-edit'></i> Edit</a></td>
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
									
									<!-- Modal -->
									<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									  <div class="modal-dialog">
									    <div class="modal-content">
									      <div class="modal-header">
									        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
									        <h4 class="modal-title" id="myModalLabel">Confirm</h4>
									      </div>
									      <div class="modal-body">
									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
									        <button id='yes' type="button" class="btn btn-primary">Yes</button>
									      </div>
									    </div>
									  </div>
									</div>
								</div>
							</div>
							<div class='pull-left'><?php echo $links;?></div>
							<div class='pull-right'>
								<label class='control-label'>Showing <?php if($start == null){ echo '1';}else{echo $start;}?> to <?php echo $end;?> of <?php echo $total;?> results</label>
							</div>
						</div>
					</div>
				</section><!-- /.content -->
			</div><!-- /.content-wrapper -->

<?php include "/../admin_lte_footer.php"; ?>
		
<script type='text/javascript'>
$(document).ready(function(){
	$('.sidebar-menu > li').removeClass('active');
	$('.sidebar-menu > li:nth-child(5)').addClass('active');

	$('#myTable').tablesorter({
		headers : { 0 : { sorter: false }, 4 : { sorter: false } },
	});

	$('#checkAll').click(function(){
		if(this.checked){
			$('.row').each(function(){
				this.checked = true;
			});
		}else{
			$('.row').each(function(){
				this.checked = false;
			})
		}
	});

	$('#delete').click(function(e){
		e.preventDefault();
		var data = $('#myForm').serialize();
		if(data == ""){
			$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h4>');
			$('.modal-body').html('<p>Please select records to be deleted.</p>');
			$('.modal-footer').hide();
		}else{
			$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Confirm</h4>');
			$('.modal-body').html('<p>Are you sure you want to delete?</p>');
			$('.modal-footer').show();
		}
		$('#myModal').modal();

		$('#yes').click(function(){
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>customer/delete',
				data: data,
				success: function(result){
					window.location  = '<?php echo base_url();?>customer';
				}
			});
		});
	});
});
</script>

</body>
</html>
