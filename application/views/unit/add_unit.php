<?php include "/../admin_lte_header.php"; ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1 class="pull-left">Create Unit
					</h1>
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
											<?php echo form_open('',array('id'=>'myForm'));?>
											<div id='unit_code' class='form-group'>
												<?php echo form_label('Unit Code','',array('class'=>'control-label'));?>
												<?php echo form_input(array('id'=>'unit_code','name'=>'unit_code','class'=>'form-control','placeholder'=>'Unit Code'));?>
												<span class='error-message control-label'></span>
											</div>
											<div id='description' class='form-group'>
												<?php echo form_label('Description','',array('class'=>'control-label'));?>
												<?php echo form_input(array('id'=>'description','name'=>'description','class'=>'form-control','placeholder'=>'Description'));?>
												<span class='error-message control-label'></span>
											</div>
											<div class='pull-right'>
												<a href='<?php echo base_url();?>unit/' class="btn btn-default">Cancel</a>
												<button type="submit" class="btn btn-success">Save</button>
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
	$(document).ready(function(e){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(7)').addClass('active');
		
		$('#myForm').submit(function(e){
			e.preventDefault();
			var data = $('#myForm').serialize();
			$('#unit_code').removeClass('has-error');
			$('#description').removeClass('has-error');
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>unit/save',
				data: data,
				dataType: 'json',
				success: function(result){
					if(!result.success){
						if(result.error.unit_code){
							$('#unit_code').addClass('has-error');
							$('#unit_code .error-message').html(result.error.unit_code);
						}
						if(result.error.description){
							$('#description').addClass('has-error');
							$('#description .error-message').html(result.error.description);

						}
					}else{
						window.location.href = "showUnits";
						$('#myForm')[0].reset();
						$('.notification').slideDown('slow');
						window.setTimeout(close,5000);
					}
				}
			}); //end of ajax
		}); //end of submit
		function close(){
			$('.notification').slideUp('slow');
		}
	});//end of js
</script>

</body>
</html>