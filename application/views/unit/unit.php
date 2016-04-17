<?php include "/../admin_lte_header.php"; ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1 class="pull-left">Manage Units</h1>
					<h1 class='pull-right'><a href='<?php echo base_url();?>unit/add'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
					<div class="clearfix"></div>
				</section>

				<!-- Main content -->
				<section class="content">
					<div class='row'>
						<div class='col-md-12'>
							<div class="box box-primary">
								<div class="box-header with-border">
									<div class="pull-left">
										<button id='delete' class="btn btn-danger btn-sm"><i class='fa fa-minus-circle'></i> Delete</button>
									</div>
									<div class="pull-right">
										<form class="navbar-form navbar-left" role="search">
										   <div class="form-group">
										      <input type="text" class="form-control" placeholder="Search">
										      <button type="submit" class="btn btn-default btn-flat">Go</button>

										   </div>
										</form>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="box-body">
									<?php echo form_open('',array('id'=>'myForm'));?>
									<table id='myTable' class='table table-striped table-hover'>
										<thead>
											<tr>
												<th><input type='checkbox' id='checkAll'></input></th>
												<th>Unit Code</th>
												<th>Description</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php if(!empty($result)):?>
												<?php foreach($result as $row):?>
													<tr>
														<td width='30px'><input class='checkbox1' type='checkbox' name='checkbox[]' id='checkbox[]' value='<?php echo $row->unit_id;?>'></input></td>
														<td><?php echo $row->unit_code;?></td>
														<td><?php echo $row->description;?></td>
														<td class='text-center'>
															<a href='<?php echo base_url();?>unit/update/<?php echo $row->unit_id;?>' class="btn btn-sm btn-primary">Edit</a>
															<a href='<?php echo base_url();?>unit/getSubItems/<?php echo $row->unit_id;?>' class="btn btn-sm btn-default"><i class="fa fa-th-list"></i></a>
														</td>
													</tr>
												<?php endforeach;?>
												<?php echo form_close();?>
											<?php else:?>
												<tr><td colspan='5'><?php echo 'No Records Found.';?></td></tr>
											<?php endif;?>
										</tbody>
									</table>
									<?php echo form_close();?>
								</div>
							</div>
							<div class='pull-left'>
								<?php echo $links;?>
							</div>
							<div class='pull-right'>
								<label class='control-label'><strong>Showing <?php if($start == null){ echo '1';}else{echo $start;}?> to <?php echo $end;?> of <?php echo $total;?> results<strong></label>
							</div>
						</div>
					</div>
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
				</section><!-- /.content -->
			</div><!-- /.content-wrapper -->

<?php include "/../admin_lte_footer.php"; ?>
			

<script type='text/javascript'>
	$(document).ready(function(e){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(7)').addClass('active');
		
		$('#myTable').tablesorter({
			headers : { 0 : { sorter: false }, 3: { sorter: false} },
		});

		$('#myForm').submit(function(e){
			e.preventDefault();
			var data = $('#myForm').serialize();
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>unit/deleteAll',
				data: data,
				success: function(result){
					//$('#myTable tbody').html(result);
					window.location.href = '<?php echo base_url();?>unit';
				}
			});
		});
	});
</script>
<script>
	$(document).ready(function(){
		$('.navbar ul li').removeClass('active');
		$('.navbar ul li:nth-child(3)').addClass('active');

		$('#checkAll').click(function(event) {  //on click 
		    if(this.checked) { // check select status
		        $('.checkbox1').each(function() { //loop through each checkbox
		            this.checked = true;  //select all checkboxes with class 'checkbox1'               
		        });
		    }else{
		        $('.checkbox1').each(function() { //loop through each checkbox
		            this.checked = false; //deselect all checkboxes with class 'checkbox1'                       
		        });         
		    }
		});

		$('#delete').click(function(e){
			e.preventDefault();
			var data = $('#myForm').serialize();
			if(data == ""){
				$('.modal-header').html('<h3 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h3>');
				$('.modal-body').html('<h4>Please select record/s to be deleted.</h4>');
				$('.modal-footer').hide();
			}else{
				$('.modal-header').html('<h3 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Confirm</h3>');
				$('.modal-body').html('<h4>Are you sure you want to delete selected record/s ?</h4>');
				$('.modal-footer').show();
			}
			$('#myModal').modal();

			$('#yes').click(function(){
				$.ajax({
					type: 'post',
					url: '<?php echo base_url();?>unit/delete',
					data: data,
					success: function(result){
						window.location  = '<?php echo base_url();?>unit';
					}
				});
			});
		});
	});
</script>

</body>
</html>