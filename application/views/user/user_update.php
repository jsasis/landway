<?php include "/../admin_lte_header.php"; ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1 class="pull-left">Edit User Information</h1>
					<div class="clearfix"></div>
				</section>

				<!-- Main content -->
				<section class="content">
					<div class='row'>
						<div class='col-md-12'>
							<div class="box box-primary">
								<div class="box-body">
									<div class='row'>
										<div class='col-md-6'>
											<?php echo form_open('', array('id'=>'myForm'));?>
											<input type='hidden' name='user_id' value= '<?php echo $result['user_id'];?>'></input>
											<div class='form-group' id='first_name'>
												<?php echo form_label('First Name','', array('class'=>'control-label'));?>
												<?php echo form_input(array('id'=>'first_name', 'name'=>'first_name', 'class'=>'form-control', 'value'=> $result['first_name'], 'autocomplete'=>'off'));?>
												<span class='error-message control-label'></span>
											</div>
											<div class='form-group' id='last_name'>
												<?php echo form_label('Last Name','', array('class'=>'control-label'));?>
												<?php echo form_input(array('id'=>'last_name', 'name'=>'last_name', 'class'=>'form-control', 'value'=> $result['last_name'], 'autocomplete'=>'off'));?>
												<span class=' error-message control-label'></span>
											</div>
											<div class='form-group' id='user_type'>
												<?php echo form_label('User Type','', array('class'=>'control-label'));?>
												<?php $options = array(''=>'Choose Permission', 'admin'=>'Admin', 'user'=>'User');?>
												<?php echo form_dropdown('user_type', $options, $result['user_type'], 'class = form-control');?>
												<span class='error-message control-label'></span>
											</div>
											<div class='form-group pull-right'>
												<a href='<?php echo base_url();?>user' class='btn btn-default'>Cancel</a>
												<button type='submit' class='btn btn-success'>Save</button>
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

		$('#first_name, #last_name, #user_type').removeClass('has-error');
		$('#first_name .error-message, #last_name .error-message, #user_type .error-message').empty();
		
		$.ajax({
			type: 	'post',
			data: 	data,
			url: 	'<?php echo base_url();?>user/saveUpdate',
			dataType: 'json',
			success: function(response){
				if(response.success){
					$('.notification').html(" <strong><h4><i class='fa fa-check-circle'></i><span id='message'>  User has been updated! </span></h4></strong>");
					$('.notification').slideDown();
					$('.notification').fadeOut(800);
					window.setTimeout(function(){
						window.location = "<?php echo base_url();?>user/show";
					}, 800);
				}else{
					if(response.error.first_name){
						$('#first_name').addClass('has-error');
						$('#first_name .error-message').html(response.error.first_name);
					}
					if(response.error.last_name){
						$('#last_name').addClass('has-error');
						$('#last_name .error-message').html(response.error.last_name);
					}
					if(response.error.user_type){
						$('#user_type').addClass('has-error');
						$('#user_type .error-message').html(response.error.user_type);
					}
				}
			}
		});
	});
</script>

</body>
</html>