<?php include "/../admin_lte_header.php"; ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1 class="pull-left">Create Customer</h1>
					<h1 class="pull-right"><a href='<?php echo base_url();?>customer/add'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
					<div class="clearfix"></div>
				</section>

				<!-- Main content -->
				<section class="content">
					<div class='row'>
						<div class='col-md-12'>
							<div class="box box-primary">
								<div class="box-body">
									<?php echo form_open('',array('id'=>'myForm'));?>
									<div class='row'>
										<div class='col-md-6'>
											<div class='form-group' id='customer_name'>
											  <?php echo form_label('Customer Name','',array('class'=>'control-label'));?>
											  <?php echo form_input(array('id'=>'customer_name','name'=>'customer_name','class'=>'form-control','placeholder'=>'Customer Name'));?>
											  <span class='error-message control-label'></span>
											</div>
											<div class='form-group' id='complete_address'>
											  <?php echo form_label('Complete Address','',array('class'=>'control-label'));?>
											  <?php echo form_input(array('id'=>'complete_address','name'=>'complete_address','class'=>'form-control','placeholder'=>'Complete Address'));?>
											  <span class='error-message control-label'></span>
											</div>
											<div class='form-group' id='contact_number'>
											  <?php echo form_label('Contact No.','',array('class'=>'control-label'));?>
											  <?php echo form_input(array('id'=>'contact_number','name'=>'contact_number','class'=>'form-control','placeholder'=>'Contact No.'));?>
											  <span class='error-message control-label'></span>
											</div>
											<div class="pull-right">
												<a href="<?php echo $base_url;?>customer" class="btn btn-default">Cancel</a>
												<button type='submit' class='btn btn-success'>Save</button>
												<?php echo form_close();?>
											</div>
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
$(document).ready(function(e){
	$('.sidebar-menu > li').removeClass('active');
	$('.sidebar-menu > li:nth-child(5)').addClass('active');
	
	$('#myForm').submit(function(){
		$('#customer_name, #complete_address, #contact_number').removeClass('has-error');
		$('#customer_name .error-message, #complete_address .error-message, #contact_number .error-message').empty();

		var data = $('form').serialize();
		$.ajax({
			type: 'post',
			data: data,
			url: '<?php echo base_url();?>customer/save',
			dataType: 'json',
			success: function(result) {
				if(!result.success) {
					if(result.errors.customer_name) {
						$('#customer_name').addClass('has-error');
						$('#customer_name .error-message').html(result.errors.customer_name);
					}
					if(result.errors.complete_address) {
						$('#complete_address').addClass('has-error');
						$('#complete_address .error-message').html(result.errors.complete_address);
					}
					if(result.errors.contact_number) {
						$('#contact_number').addClass('has-error');
						$('#contact_number .error-message').html(result.errors.contact_number);
					}
				}else{
					$('#myForm')[0].reset();
					$('.notification').slideDown();
					window.setTimeout(close,5000);
				}
			}
		});
		function close() {
			$('.notification').slideUp('fast');
		}
		return false;
	});
});
</script>

</body>
</html>