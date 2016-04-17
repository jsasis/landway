<?php include "/../admin_lte_header.php"; ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1 class="pull-left">Manage Users</h1>
					<h1 class="pull-right"><a href='<?php echo base_url();?>user/add'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
					<div class="clearfix"></div>
				</section>

				<!-- Main content -->
				<section class="content">
					<div class='row'>
						<div class='col-md-12'>
							<div class="box box-primary">
								<div class="box-body">
									<?php if($this->session->flashdata('notification')):?>
										<div class='alert alert-success'><?php echo $this->session->flashdata('notification');?></div>
									<?php endif?>
									<nav class="navbar navbar-default control-table" role="navigation">
									   <div class="container-fluid">
										  <ul class='nav navbar-nav navbar-left'>
											 <form class="navbar-form navbar-left" role="search">
												<button id='delete' class="btn btn-sm btn-danger"><i class='fa fa-minus-circle'></i> Delete</button>
											 </form>
										  </ul>
										  <ul class='nav navbar-nav navbar-right'>
										     <form action='<?php echo base_url();?>user/search' method='POST' class="navbar-form navbar-left" role="search">
										        <div class="input-group">
										        	<?php echo form_input(array('name'=>'search_key','class'=>'form-control','placeholder'=>'Search User ...'));?>
										        	<span class="input-group-btn"><button type='submit' class='btn btn-default' id='search'>Go</button></span>
										        </div>
										     </form>
										  </ul>
									   </div>
									</nav>
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
									
								</div>
							</div>
							<div class='pull-left'>
								<?php echo $links;?>
							</div>
							<div class='pull-right'>
								<label class='control-label'>Showing <?php if($start == null){ echo '1';}else{echo $start;}?> to <?php echo $end;?> of <?php echo $total;?> results</label>
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
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(8)').addClass('active');

		$('#myTable').tablesorter({
			headers : { 0 : { sorter: false }, 5 : { sorter: false }},
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
					url: '<?php echo base_url();?>user/delete',
					data: data,
					success: function(result){
						window.location  = '<?php echo base_url();?>user/show';
					}
				});
			});
		});

	});
</script>

</body>
</html>