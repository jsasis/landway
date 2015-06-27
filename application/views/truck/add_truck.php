<?php include "/../admin_lte_header.php"; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Create Truck</h1>
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
        							<div id='make' class='form-group'>
        								<?php echo form_label('Make','',array('class'=>'control-label'));?>
        								<?php echo form_input(array('id'=>'make','name'=>'make','class'=>'form-control','placeholder'=>'Make'));?>
        								<span class='error-message control-label'></span>
        							</div>
        							<div id='type' class='form-group'>
        								<?php echo form_label('Description','',array('class'=>'control-label'));?>
        								<?php echo form_input(array('id'=>'type','name'=>'type','class'=>'form-control','placeholder'=>'Type'));?>
        								<span class='error-message control-label'></span>
        							</div>
        							<div id='plate_number' class='form-group'>
        								<?php echo form_label('Plate Number','',array('class'=>'control-label'));?>
        								<?php echo form_input(array('id'=>'plate_number','name'=>'plate_number','class'=>'form-control','placeholder'=>'Plate #'));?>
        								<span class='error-message control-label'></span>
        							</div>
        							<div class='pull-right'>
        								<button type="submit" class="btn btn-success"><i class='fa fa-save'></i> Save</button>
        								<a href='<?php echo base_url();?>truck' class='btn btn-default'>Cancel</a>
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
</div>
<?php include "/../admin_lte_footer.php"; ?>
		
<script type='text/javascript'>
	$(document).ready(function(e){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(6)').addClass('active');

		$('#myForm').submit(function(e){
			e.preventDefault();

			var data = $('#myForm').serialize();

			$('#make').removeClass('has-error');
			$('#type').removeClass('has-error');
			$('#plate_number').removeClass('has-error');

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
						$('.notification').html(" <strong><h4><i class='fa fa-check-circle'></i><span id='message'>  New Truck has been created!</span></h4></strong>");
						$('.notification').slideDown('slow');
						$('.notification').fadeOut(800);
						window.setTimeout(function(){
							window.location = "<?php echo base_url();?>truck";
						}, 800);
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