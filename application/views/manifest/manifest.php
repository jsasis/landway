<?php include "/../admin_lte_header.php"; ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class='pull-left'> Create Manifest</h1>
			<div class='clearfix'></div>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class="box box-default">
				<div class="box-body">
					<div class='row'>
						<div class='col-md-6'>
							<?php echo form_open('',array('id'=>'myForm'));?>
								<div class='form-group' id='truck'>
									<?php echo form_label('Truck','',array('class'=>'control-label'));?><span class='text-danger'> *</span>
									<?php $attrib = "class='form-control'";?>
									<?php $options[''] = 'Choose Truck';?>
									<?php foreach($trucks as $truck):?>
									<?php $options[$truck->truck_id] = $truck->plate_number;?>
									<?php endforeach;?>
									<?php echo form_dropdown('truck', $options,'', $attrib);?>
									<span class='error-message control-label'></span>
									</div>
									<div class='form-group' id='driver'>
										<?php echo form_label('Driver','',array('class'=>'control-label'));?>
										<?php echo form_input(array('id'=>'driver','name'=>'driver','class'=>'form-control','placeholder'=>'Driver','autocomplete'=>'off'));?>
										<span class='error-message control-label'></span>
									</div>
									<div class='form-group' id='trip_to'>
										<?php echo form_label('Trip to','',array('class'=>'control-label'));?>
										<?php echo form_input(array('id'=>'trip_to','name'=>'trip_to','class'=>'form-control','placeholder'=>'Trip to','autocomplete'=>'off'));?>
										<span class='error-message control-label'></span>
								</div>
								<div class='form-group pull-right'>
									<button type='submit' class='btn btn-success'>Save</button>
									<a class="btn btn-default" href='<?php echo base_url();?>manifest'>Cancel</a>
								</div>
							<?php echo form_close();?>
						</div>
				</div>
			</div>
			
		</section><!-- /.content -->

	</div><!-- /.content-wrapper -->
	
<?php include "/../admin_lte_footer.php";?>

<script type='text/javascript'>
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(4)').addClass('active');
	});
	$('#myForm').submit(function(e){
		e.preventDefault();
		var data = $(this).serialize();
		
		$('#truck, #driver, #trip_to').removeClass('has-error');
		$('#truck .error-message, #driver .error-message, #trip_to .error-message').empty();
		
		$.ajax({
			type: 'post',
			url:  '<?php echo base_url();?>manifest/save',
			data: data,
			dataType: 'json',
			success: function(response){
				if(response.success){
					$('.notification').html(" <strong><h4><i class='fa fa-check-circle'></i><span id='message'>  New manifest has been created!</span></h4></strong>");
					$('.notification').slideDown('slow');
					$('.notification').fadeOut(4000);
					window.setTimeout(function(){
						window.location = "<?php echo base_url();?>manifest/getDetails/"+response.manifest_number;
					}, 2000);
				}else{
					if(response.error.truck){
						$('#truck').addClass('has-error');
						$('#truck .error-message').html(response.error.truck);
					}
					if(response.error.driver){
						$('#driver').addClass('has-error');
						$('#driver .error-message').html(response.error.driver);
					}
					if(response.error.trip_to){
						$('#trip_to').addClass('has-error');
						$('#trip_to .error-message').html(response.error.trip_to);
					}
				}
			}
		});
	});
</script>
</body>
</html>