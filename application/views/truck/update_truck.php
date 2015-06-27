<?php include "/../admin_lte_header.php"; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left"></h1>
            <div class="clearfix"></div>
        </section>
        <!-- Main content -->
        <section class="content">
			<div class="row">
				<div class="col-md-12">
					<h2><?php echo $result['plate_number'];?></h2>
					<p>Truck Details</p>
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
					        <li class="active"><a data-toggle="tab" href="#manifest">Manifest</a></li>
					        <li><a data-toggle="tab" href="#edit_info">Edit Information</a></li>
					    </ul>
					    <div class="tab-content">
					    	<!-- TRUCK MANIFESTS -->
					    	<div id="manifest" class="tab-pane fade in active">
					    		<div class="box-header">
					    			<button id='delete' class='btn btn-sm btn-danger pull-right'><i class='fa fa-minus-circle'></i> Delete</button>
					    		</div>
	   							<table class='table table-hover' id='myTable'>
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
	   								<?php if($manifests):?>
	   								<?php foreach($manifests as $row):?>
	   									<tr>
	   										<td><input class='row' type='checkbox' name='checkbox[]' id='checkbox' value='<?php echo $row->manifest_number;?>'></input></td>
	   										<td><a href='<?php echo base_url();?>manifest/getDetails/<?php echo $row->manifest_number;?>'><?php echo $row->alpha;?></a>
	   										</td>
	   										<td><?php echo $row->driver;?>			</td>
	   										<td><?php echo $row->trip_to;?>			</td>
	   										<td><?php echo $row->plate_number;?>	</td>
	   										<td width='200px'><?php echo date('F d, Y', strtotime($row->date));?></td>
	   										<td class='text-right'>
	   											<div class="btn-group">
	   												<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
	   													<strong> Action </strong><span class="caret"></span>
	   												</button>
	   												<ul class="dropdown-menu" role="menu">
	   													<li><a href="<?php echo base_url();?>manifest/getDetails/<?php echo $row->manifest_number;?>">View</a></li>
	   													<li><a href="<?php echo base_url();?>manifest/update/<?php echo $row->manifest_number;?>">Edit</a></li>
	   													<li class="divider"></li>
	   													<li><a href="<?php echo base_url();?>manifest/export/<?php echo $row->manifest_number;?>">Export</a></li>
	   												</ul>
	   											</div>
	   										</td>
	   									</tr>
	   								<?php endforeach;?>
	   								<?php else:?>
	   									<tr>
	   										<td colspan='7'>No records found.</td>
	   									</tr>
	   								<?php endif;?>
	   								</tbody>
	   							</table>
	   							<div class="row">
	   								<div class="col-md-12">
	   									<div class='pull-left'><?php echo $links;?></div>
	   									<div class='pull-right'>
	   										<label class='control-label'><strong>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $total;?> results</strong></label>
	   									</div>
	   								</div>
	   							</div>
	   							
					        </div>
					        <!-- EDIT TRUCK INFO -->
					        <div id="edit_info" class="tab-pane fade">
					        	<div class="box-body">
					        		<div class='row'>
	           						<div class='col-md-6'>
	       								<?php echo form_open('',array('id'=>'myForm'));?>
	       								<div id='make' class='form-group'>
	       									<?php echo form_label('Make','',array('class'=>'control-label'));?>
	       									<?php echo form_input(array('id'=>'make','name'=>'make','class'=>'form-control','value'=> $result['make']));?>
	       									<span class='error-message control-label'></span>
	       								</div>
	       								<div id='type' class='form-group'>
	       									<?php echo form_label('Type/Configuration','',array('class'=>'control-label'));?>
	       									<?php echo form_input(array('id'=>'type','name'=>'type','class'=>'form-control','value'=> $result['type']));?>
	       									<span class='error-message control-label'></span>
	       								</div>
	       								<div id='plate_number' class='form-group'>
	       									<?php echo form_label('Plate Number','',array('class'=>'control-label'));?>
	       									<?php echo form_input(array('id'=>'plate_number','name'=>'plate_number','class'=>'form-control','value'=> $result['plate_number']));?>
	       									<span class='error-message control-label'></span>
	       								</div>
	       								<input type='hidden' id='truck_id' name='truck_id' value='<?php echo $result['truck_id'];?>';?>
	       								<div class='pull-right'>
	       									<a href='<?php echo base_url();?>truck' class='btn btn-default'>Cancel</a>
	       									<button type="submit" class="btn btn-success"> Save</button>
	       								</div>	
	       								<?php echo form_close();?>
	           						</div>
	           					</div>
					        	</div>
					        	
					        </div>
					    </div>	
					</div>			
				</div>
			</div>
         </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
</div>
<?php include "/../admin_lte_footer.php"; ?>
<script type='text/javascript'>
	$(document).ready(function(e){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(6)').addClass('active');

		$('.input-daterange').datepicker({
			autoclose: true,
			format: "yyyy-mm-dd"
		});

		$('#myForm').submit(function(e){
			e.preventDefault();

			var data = $('#myForm').serialize();
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>truck/save',
				data: data,
				dataType: 'json',
				success: function(result){
					if(!result.success){
						if(result.error.make){
							$('#make').addClass('has-error');
							$('#make .error-message').html(result.error.make);
						}
						if(result.error.type){
							$('#type').addClass('has-error');
							$('#type .error-message').html(result.error.type);
						}
						if(result.error.plate_number){
							$('#plate_number').addClass('has-error');
							$('#plate_number .error-message').html(result.error.plate_number);
						}
					}else{
						$('.notification').html(" <strong><h4><i class='fa fa-check-circle'></i><span id='message'>  Truck has been updated!</span></h4></strong>");
						$('.notification').slideDown('slow');
						$('.notification').fadeOut(800);
						window.setTimeout(function(){
							window.location = "<?php echo base_url();?>truck/";
						}, 800);
					}
				}
			});//end of ajax
		});//end of submit
	});//end of js
</script>
</body>
</html>