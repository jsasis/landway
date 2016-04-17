<?php include "/../admin_lte_header.php"; ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1 class="pull-left">Change Password</h1>
					<div class="clearfix"></div>
				</section>

				<!-- Main content -->
				<section class="content">
					<div class='row'>
						<div class='col-md-12 '>
							<div class="box box-primary">
								<div class="box-body">
									<div class='row'>
										<div class='col-md-6'>
											<?php echo form_open('', array('id'=>'myForm'));?>
											<div class='form-group' id='old_password'>
												<?php echo form_label('Old Password','', array('class'=>'control-label'));?>
												<?php echo form_input(array('type'=>'password','id'=>'old_password', 'name'=>'old_password', 'class'=>'form-control', 'placeholder'=>'Old Password', 'autocomplete'=>'off'));?>
												<span class='error-message control-label'></span>
											</div>
											<div class='form-group' id='new_password'>
												<?php echo form_label('New Password','', array('class'=>'control-label'));?>
												<?php echo form_input(array('type'=>'password','id'=>'new_password', 'name'=>'new_password', 'class'=>'form-control', 'placeholder'=>'New Password'));?>
												<span class='error-message control-label'></span>
											</div>
											<div class='form-group' id='confirm_password'>
												<?php echo form_label('Confirm Password','', array('class'=>'control-label'));?>
												<?php echo form_input(array('type'=>'password','id'=>'confirm_password', 'name'=>'confirm_password', 'class'=>'form-control', 'placeholder'=>'Confirm Password'));?>
												<span class='error-message control-label'></span>
											</div>
											<div class='form-group pull-right'>
												<a href='<?php echo base_url();?>user/show' class='btn btn-default'>Cancel</a>
												<button type='submit' class='btn btn-success'><i class='fa fa-edit'></i> Change Password</button>
											</div>
											<?php echo form_close();?>
										</div>
									</div>
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
	});

	$('#myForm').submit(function(e){
		e.preventDefault();
		var data = $(this).serialize();

		$('#old_password, #new_password, #confirm_password').removeClass('has-error');
		$('#old_password .error-message, #new_password .error-message, #confirm_password .error-message').empty();

		$.ajax({
			type: 	'post',
			data: 	data,
			url: 	'<?php echo base_url();?>user/update',
			dataType: 'json',
			success: function(response){
				console.log(response);
				if(response.success){
					$('#myForm')[0].reset();
					$('.notification').html(" <strong><h4><i class='fa fa-check-circle'></i><span id='message'> Password has been changed </span></h4></strong>");
					$('.notification').slideDown('slow');
					$('.notification').fadeOut(800);
				}else{
					if(response.error.old_password){
						$('#old_password').addClass('has-error');
						$('#old_password .error-message').html(response.error.old_password);
					}
					if(response.error.new_password){
						$('#new_password').addClass('has-error');
						$('#new_password .error-message').html(response.error.new_password);
					}
					if(response.error.confirm_password){
						$('#confirm_password').addClass('has-error');
						$('#confirm_password .error-message').html(response.error.confirm_password);
					}
				}
			}
		});
	});
</script>

</body>
</html>