<html lang='en'>
<?php include_once('/../header.php');?>
<?php include_once('/../nav.php');?>
	<body>
		<div id='page-wrapper'>
			<div class='row'>
				<div class='col-md-12 main'>
					<div class='row'>
						<div class='col-md-6'>
							<h1 class='manage text-info'><i class='fa fa-edit'></i> Edit <small>Item</small></h1>
							<div class='wrapper'>
								<?php echo form_open('',array('id'=>'myForm'));?>
									<div id='unit' class='form-group'>
										<?php echo form_label('Unit','',array('class'=>'control-label'));?>
							            <select class='form-control' name='unit'>
							                <option value='<?php echo $result['unit_id'];?>'><?php echo $result['unit'];?></option>
							                	<?php foreach ($unit as $row): ?>
							                  		<option value='<?php echo $row->unit_id; ?>'> <?php echo $row->description;?></option>;
							                	<?php endforeach; ?>
							            </select>
							            <span class='error-message control-label'></span>
									</div>
									<div id='unit_category_description' class='form-group'>
										<?php echo form_label('Description','',array('class'=>'control-label'));?>
										<?php echo form_input(array('id'=>'unit_category_description','name'=>'unit_category_description','class'=>'form-control','value'=>$result['description']));?>
										<span class='error-message control-label'></span>
									</div>
									<p class='text-right'><a href='#' data-toggle="modal" data-target="#myModal"> Update Price</a></p>
									<input type='hidden' name='unit_category_id' value='<?php echo $result['unit_category_id'];?>'></input>
									<button type='submit' class='btn btn-success'><i class='fa fa-edit'></i> Save</button>
									<a href='<?php echo base_url();?>unit_category' class='btn btn-default'>Cancel</a>
								</form>
							</div>
						</div>
					</div>
					<!-- Modal -->
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">Price Update</h4>
					      </div>
					      <div class="modal-body">
					      	<?php echo form_open('', array('id'=>'change_price_form','class'=>'form-horizontal'));?>
					      		<div class='row'>
					      			<div class='col-md-12'>
		      				      		<div id='cost' class='form-group'>
		      				      			<input type='hidden' name='unit_category_id' value='<?php echo $result['unit_category_id'];?>'></input>
		      						        <?php echo form_label('New Price','',array('class' => 'control-label col-md-4'));?>
		      						        <div class='col-md-6'>
		      							        <?php echo form_input(array('id'=>'cost', 'name'=>'cost', 'placeholder'=>'Enter New Price', 'class'=>'form-control'));?>
		      								    <span class='error-message control-label'></span>
		      							    </div>
		      							</div>
					      			</div>
					      		</div>
					      </div>
					      <div class="modal-footer">
					      	<div class='form-group'>
						        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						        <button type='submit' class="btn btn-primary">Save changes</button>
						    </div>
					        <?php echo form_close();?>
					      </div>
					    </div>
					  </div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<script type='text/javascript'>
	$(document).ready(function(e){
		$('.navbar ul li:nth-child(4)').addClass('active');
	});//end of js

	$('#myForm').submit(function(e){
		e.preventDefault();
		var data = $('#myForm').serialize();
		$.ajax({
			type: 'post',
			url: '<?php echo base_url();?>unit_category/save',
			data: data,
			dataType: 'json',
			success: function(result){
				if(!result.success){
					if(result.error.unit){
						$('#unit').addClass('has-error');
						$('#unit .error-message').html(result.error.unit);
					}
					if(result.error.description){
						$('#description').addClass('has-error');
						$('#description .error-message').html(result.error.description);
					}
					if(result.error.unit_cost){
						$('#unit_cost').addClass('has-error');
						$('#unit_cost .error-message').html(result.error.unit_cost);
					}
				}else{
					$('.notification').html(" <strong><h4><i class='fa fa-check-circle'></i>  Item has been updated!</span></h4></strong>");
					$('.notification').slideDown();
					$('.notification').fadeOut(800);
					window.setTimeout(function(){
						window.location = "<?php echo base_url();?>unit_category/";
					}, 800);
				}
			}
		});//end of ajax
	});

	$('#change_price_form').submit(function(e){
		e.preventDefault();

		var data = $(this).serialize();

		$('#cost').removeClass('has-error');
		$('#cost .error-message').empty();

		$.ajax({
			type: 'post',
			data: data,
			url:  '<?php echo base_url();?>costing/save',
			dataType: 'json',
			success: function(response){
				if(response.success){
					$('#change_price_form')[0].reset();
					$('#myModal').modal('hide');
					$('.notification').html(" <strong><h4><i class='fa fa-check-circle'></i>  Price has been updated!</span></h4></strong>");
					$('.notification').slideDown();
					$('.notification').fadeOut(800);
					window.setTimeout(function(){
						window.location = "<?php echo base_url();?>unit_category/";
					}, 800);
				}else{
					if(response.error.cost){
						$('#cost').addClass('has-error');
						$('#cost .error-message').html(response.error.cost);
					}
				}
			}
		});
	});
</script>