<?php include "/../admin_lte_header.php"; ?>
	
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class='pull-left'> Manage Waybill</h1>
			<h1 class='pull-right'><a href='<?php echo base_url();?>waybill/add'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
			<div class='clearfix'></div>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class='row'>
				<div class='col-md-12'>
					<?php if($this->session->flashdata('notification')):?>
						<div class='alert alert-success'><?php echo $this->session->flashdata('notification');?></div>
					<?php endif?>
					<div class="box box-default">
						<div class="box-header with-border">
							<div class="pull-left">
								<button id='delete' class='btn btn-danger btn-sm'><i class='fa fa-minus-circle'></i> Delete</button>
								<a class='btn btn-default btn-sm' onclick='printByBatch()'><span class='glyphicon glyphicon-print no-print'></span> Print</a>
							</div>
							<form  id='search_form' action='<?php echo base_url();?>waybill/search' method='POST' class="navbar-form pull-right">
								<div class="input-group">
									<?php echo form_input(array('id'=>'search_key','name'=>'search_key','class'=>'form-control','placeholder'=>'Search ...','autocomplete'=>'off'));?>
									<span class="input-group-btn">
										<button type="submit" class="btn btn-default">Search</button>
										<button type="button" id="datepicker" class="btn btn-info btn-flat datepicker" style="height: 34px" onclick="showDatepicker()"><i class='fa fa-calendar'></i></button>
									</span>
								</div>
							</form>
						</div>
						<div class="box-body">
							<!-- <nav class="navbar navbar-default control-table" role="navigation">
							   <div class="container-fluid">
								  <ul class='nav navbar-nav navbar-left'>
									 <form class="navbar-form navbar-left" role="search">
										<button id='delete' class='btn btn-danger btn-sm'><i class='fa fa-minus-circle'></i> Delete</button>
										<a class='btn btn-default btn-sm' onclick='printByBatch()'><span class='glyphicon glyphicon-print no-print'></span> Print</a>
									 </form>
								  </ul>
								  <ul class='nav navbar-nav navbar-right'>
									 <form  id='search_form' action='<?php echo base_url();?>waybill/search' method='POST' class="navbar-form navbar-left">
										<div class="input-group">
											<?php echo form_input(array('id'=>'search_key','name'=>'search_key','class'=>'form-control','placeholder'=>'Search ...','autocomplete'=>'off'));?>
											<span class="input-group-btn">
												<button type="submit" class="btn btn-default">Search</button>
												<button type="button" id="datepicker" class="btn btn-info btn-flat datepicker" style="height: 34px" onclick="showDatepicker()"><i class='fa fa-calendar'></i></button>
											</span>
										</div>
									 </form>
								  </ul>
							   </div>
							</nav> -->
							<?php echo form_open('',array('id'=>'myForm'));?>
							<table class='table table-hover table-condensed table-striped' id='myTable'>
								<thead>
									<tr>
										<th><input type='checkbox' id='checkAll'></th>
										<th>                    Waybill #</th>
										<th>                    Consignee</th>
										<th class='text-right'> Consignor</th>
										<th class='text-center'>Status</th>
										<th class='text-center'>Date</th>
										<th>Total Due</th>
										<th>Balance</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($result)):?>
										<?php foreach($result as $row):?>
										<tr>
											<td><input class='row' type='checkbox' name='checkbox[]' id='checkbox' value="<?php echo $row->waybill_number;?>"></td>
											<td><a href='<?php echo base_url();?>waybill/details/<?php echo $row->waybill_number;?>'><?php echo $row->waybill_number;?></a></td>
											<td><?php echo $row->consignee;?></td>
											<td class='text-right'><?php echo $row->consignor;?></td>
											<td style='vertical-align:middle' class='text-center'>
													<?php if($row->plate_number):?>
														<a href='<?php echo base_url();?>manifest/details/<?php echo $row->manifest_number;?>'>
															<?php echo '<label class="label label-success">'.$row->plate_number.'</label>';?>
														</a>
													<?php elseif ($row->status == 'Loaded') : ?>
														<label class="label label-warning"><?php echo $row->status;?></label>
													<?php else:?>
															<?php echo '<label class="label label-danger">'. $row->status .'</label>';?>
													<?php endif;?></td>
											<td class='text-center'><?php echo date('F d, Y', strtotime($row->transaction_date));?></td>
											<td> <?php echo number_format($row->total, 2, '.', ',');?></td>
											<td><strong><?php $balance = $row->total - $row->payment; echo number_format($balance, 2, '.', ',');?></strong></td>
											<td class='text-center'>
												<div class="btn-group">
													<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
														<strong> Action </strong><span class="caret"></span>
													</button>
													<ul class="dropdown-menu" role="menu">
														<li><a href="<?php echo base_url();?>waybill/details/<?php echo $row->waybill_number;?>">View</a></li>
														<li><a href="<?php echo base_url();?>waybill/update/<?php echo $row->waybill_number;?>">Edit</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<?php endforeach?>
									<?php else:?>
										<tr>
											<td colspan='8'><?php echo 'No record/s found.';?></td>
										</tr>
									<?php endif ?>
								</tbody>
							</table>
							<?php echo form_close();?>
						</div>
					</div>				
					<div class='pull-left'><?php echo $links;?></div>
					<div class='pull-right'>
						<label class='control-label'><strong>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $total;?> results</strong></label>
					</div>
				</div>
			</div>
			<!-- Iframe -->
			<iframe name='print' id='i_frame' width='100%' height='100%' style='display:none'></iframe>
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
	
<?php include "/../admin_lte_footer.php";?>

<script type='text/javascript'>
	var showloader = false;
	$(document).ajaxStart(function(){
		if (showloader){
			$('body').loader('show');
		}
	});    
	$(document).ajaxComplete(function(){
		setTimeout("$('body').loader('hide')", 800);
		showloader = false;
	});
	
	function printByBatch(){
		var data = $('#myForm').serialize();
		if(!data){
			alert('Please select items to be printed.');
			return;
		}
		$.ajax({
			type: 'post',
			data: data,
			url: '<?php echo base_url();?>waybill/printByBatch',
			success: function(result){
				$('#i_frame').attr('srcdoc', result);
			}
		});
	}
</script>
<script type='text/javascript'>
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(3)').addClass('active');
		$('.sidebar-menu > li.active .treeview-menu li:nth-child(2)').addClass('active');
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
			headers : { 0 : { sorter: false }, 7: { sorter: false}, 8: { sorter: false}, 9: { sorter: false} },
			theme: 'default'
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
			var data = $('#myForm').serialize();
		
			if(data == "") { 
				$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h4>');
				$('.modal-body').html('<p>Please select records to be deleted.</p>');
				$('.modal-footer').hide();
			} else {
				$('.modal-header').html('<h4><span class="glyphicon glyphicon-info-sign"></span> Confirm</h4>');
				$('.modal-body').html('<p>Are you sure you want to delete?</p>');
				$('.modal-footer').show();
			}

			$('#myModal').modal();

			$('#yes').click(function(){
				showloader = true;
				$.ajax({
					type: 'post',
					url: '<?php echo base_url();?>waybill/delete',
					data: data,
					success: function(result){
						window.location  = '<?php echo base_url();?>waybill';
					}
				});
			});
		}); 
	});
</script>

</body>
</html>

