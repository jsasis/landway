<?php include "/../admin_lte_header.php"; ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1 class="pull-left">New Item</h1>
					<div class="clearfix"></div>
				</section>

				<!-- Main content -->
				<section class="content">
					<div class="box box-primary">
						<div class="box-body">
							<div class='row'>
								<div class='col-md-12'>
									<div class='row'>
										<div class='col-md-6'>
											<?php echo form_open('',array('id'=>'myForm'));?>
											<div id='unit' class='form-group'>
												<?php echo form_label('Unit','',array('class'=>'control-label'));?>
									            <select class="form-control" name="unit">
									                <option value="">Select Unit</option>
									                	<?php foreach ($unit as $row): ?>
									                  		<option value="<?php echo $row->unit_id; ?>"> <?php echo $row->description;?></option>;
									                	<?php endforeach; ?>
									            </select>
									            <span class='error-message control-label'></span>
											</div>
											<div id='unit_category_description' class='form-group'>
												<?php echo form_label('Description','',array('class'=>'control-label'));?>
												<?php echo form_input(array('id'=>'unit_category_description','name'=>'unit_category_description','class'=>'form-control','placeholder'=>'Category Description'));?>
												<span class='error-message control-label'></span>
											</div>
											<div id='unit_cost' class='form-group'>
												<?php echo form_label('Unit Cost','',array('class'=>'control-label'));?>
												<?php echo form_input(array('id'=>'unit_cost','name'=>'unit_cost','class'=>'form-control','placeholder'=>'Unit Cost'));?>
												<span class='error-message control-label'></span>
											</div>
											<div class='pull-right'>
												<a href='<?php echo base_url();?>unit_category' class='btn btn-default'>Cancel</a>
												<button type="submit" id='submit' class="btn btn-success">Save</button>
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
		$('.navbar ul li:nth-child(4)').addClass('active');
		
		$('#submit').click(function(e){
			e.preventDefault();
			var data = $('#myForm').serialize();
			$('#unit').removeClass('has-error');
			$('#unit_category_description').removeClass('has-error');
			$('#unit_cost').removeClass('has-error');
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>unit_category/save',
				data: data,
				dataType: 'json',
				success: function(result){
					if(!result.success){
						if(result.error.unit_id){
							$('#unit').addClass('has-error');
							$('#unit .error-message').html(result.error.unit);
						}
						if(result.error.unit_category_description){
							$('#unit_category_description').addClass('has-error');
							$('#unit_category_description .error-message').html(result.error.unit_category_description);
						}
						if(result.error.unit_cost){
							$('#unit_cost').addClass('has-error');
							$('#unit_cost .error-message').html(result.error.unit_cost);

						}
					}else{
						//window.location.href = "unit_category";
						$('#myForm')[0].reset();
						$('.notification').html(" <strong><h4><i class='fa fa-check-circle'></i><span id='message'>  New item has been created!</span></h4></strong>");
						$('.notification').slideDown('slow');
						window.setTimeout(close, 800);
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