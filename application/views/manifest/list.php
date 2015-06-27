<?php include "/../admin_lte_header.php"; ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class='pull-left'>Manage Manifests</h1>
			<h1 class='pull-right'><a href='<?php echo base_url();?>manifest/show'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
			<div class='clearfix'></div>
		</section>
		<!-- Main content -->
		<section class="content">
			<div class='row'>
				<div class='col-md-12'>
					<div class="box box-default">
						<div class="box-header with-border">
							<div class="pull-left">
								<button id='delete' class='btn btn-sm btn-danger'><i class='fa fa-minus-circle'></i> Delete</button>
							</div>
							 <form  id='search_form' action='<?php echo base_url();?>manifest/search' method='POST' class="navbar-form pull-right">
								<div class="input-group">
									<?php echo form_input(array('id'=>'search_key','name'=>'search_key','class'=>'form-control','placeholder'=>'Search ...','autocomplete'=>'off'));?>
									<div class="input-group-btn">
										<button type="submit" class="btn btn-default">Go</button>
										<button type="button" id="datepicker" class="btn btn-info btn-flat datepicker" style="height: 34px" onclick="showDatepicker()"><i class='fa fa-calendar'></i></button>
									</div>
								</div>
							 </form>
						</div>
						<div class="box-body">
							<table class='table table-hover table-striped table-condensed'  id='myTable'>
								<thead>
									<tr>
										<th><input type='checkbox' id='checkAll'></input></th>
										<th>Manifest #</th>
										<th>Driver</th>
										<th>Trip To</th>
										<th>Plate #</th>
										<th>Date</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
								<?php if($result):?>
								<?php foreach($result as $row):?>
									<tr>
										<td><input class='row' type='checkbox' name='checkbox[]' id='checkbox' value='<?php echo $row->manifest_number;?>'></input></td>
										<td><a href='<?php echo base_url();?>manifest/details/<?php echo $row->manifest_number;?>'><?php echo $row->alpha;?></a></td>
										<td><?php echo $row->driver;?>			</td>
										<td><?php echo $row->trip_to;?>			</td>
										<td><?php echo $row->plate_number;?>	</td>
										<td width='200px'><?php echo date('F d, Y', strtotime( $row->date));?></td>
										<td class='text-right'>
											<div class="btn-group">
												<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
													<strong> Action </strong><span class="caret"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li><a href="<?php echo base_url();?>manifest/details/<?php echo $row->manifest_number;?>">View</a></li>
													<li><a href="<?php echo base_url();?>manifest/update/<?php echo $row->manifest_number;?>">Edit</a></li>
												</ul>
											</div>
										</td>
									</tr>
								<?php endforeach;?>
								<?php else:?>
									<tr>
										<td colspan='7'>No record/s found.</td>
									</tr>
								<?php endif;?>
								</tbody>
							</table>
						</div>
					</div>
					<div class='pull-left'><?php echo $links;?></div>
					<div class='pull-right'><label class='control-label'><strong>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $total;?> results</strong></label></div>
				</div>
			</div>
			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
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
		$('.sidebar-menu > li:nth-child(4)').addClass('active');
		/* DATEPICKER */
		$('.datepicker').datepicker({
			autoclose: true,
			todayHighlight: true,
			format: "yyyy-mm-dd",
		}).on('changeDate', function(e){
		    	$('#search_key').val(e.format("yyyy-mm-dd"));
		    	$('#search_form').submit();
		    });

		$('#myTable').tablesorter({
			headers : { 0 : { sorter: false }, 6: { sorter: false}, 7: { sorter: false}, 8: { sorter: false} },
		});

		$('#checkAll').click(function(){
			if(this.checked){
				$('.row').each(function(){
					this.checked = true;
				});
			}else{
				$('.row').each(function(){
					this.checked = false;
				});
			}
		});

		$('#delete').click(function(e){
			e.preventDefault();
			var data = $('input#checkbox').serialize();

			if(data == ""){
				$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h4>');
				$('.modal-body').html('<p>Please select record/s to be deleted.</p>');
				$('.modal-footer').hide();
			}else{
				$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Confirm</h4>');
				$('.modal-body').html('<p>Are you sure you want to delete?</p>');
				$('.modal-footer').show();
			}
			$('#myModal').modal();

			$('#yes').click(function(){
				showloader = true;
				$.ajax({
					type: 'post',
					url: '<?php echo base_url();?>manifest/delete',
					data: data,
					success: function(response){
						if(response){
							window.location  = '<?php echo base_url();?>manifest';
						}else{
							alert('Delete Error');
						}
					}
				});
			});
		}); 
	});
	// $('#delete').click(function(e){
	// 	e.preventDefault();
	// 	var data = $('input#checkbox').serialize();
	// 	$.ajax({
	// 		type: 'post',
	// 		url: '<?php echo base_url();?>manifest/delete',
	// 		data: data,
	// 		success: function(response){
	// 			if(response){
	// 				window.location  = '<?php echo base_url();?>manifest';
	// 			}else{
	// 				alert('Error Deleting...');
	// 			}
	// 		}
	// 	});
	// });
</script>
</body>
</html>