<?php include "/../admin_lte_header.php"; ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Main content -->
				<section class="content">
					<div class='row'>
						<div class='col-md-12'>
							<div class="box box-primary">
								<div class="col-md-8">
									<div class="box-header with-border">
										<?php $unit_id = $item['unit_id'];?>
										<div class="pull-left"><h2><strong><?php echo $item["unit_code"];?></strong> <small><em><?php echo $item["description"];?></em></small></h2></div>
										<h3 class='text-right'><button class="btn btn-success" data-toggle="modal" data-target="#addSubItem"><i class='fa fa-plus-circle'></i> <strong>Sub-item</strong></button></h3>
										<div class="clearfix"></div>
									</div>
								</div>
								<div class="box-body">
									<div class='row'>
										<div class='col-md-8 '>

											<?php echo form_open('', array('id'=>'myForm'));?>
											<h3 class="text-left"><button id='delete' class="btn btn-danger btn-xs"><i class='fa fa-trash'></i> Delete</button></h3>

											<table class="table table-striped">
											<thead>
												<tr>
													<th><input type='checkbox' id='checkAll'></th>
													<th>Sub-item</th>
													<th>Cost</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
											<?php if($sub_items) :?>
											<?php foreach($sub_items as $row) :?>
												<tr>
													<td width='30px'><input class='checkbox1' type='checkbox' name='checkbox[]' id='checkbox[]' value='<?php echo $row->unit_category_id;?>'></td>	
													<td><h4><?php echo $row->description;?></h4>
														<em><a style="cursor: pointer;" data-item="<?php echo $row->description;?>" data-id="<?php echo $row->unit_category_id;?>" data-toggle="modal" data-target="#editItem">edit</a></em></td>
													<td><?php echo $row->unit_cost;?></td>
													<td class="text-right">
														<a  class="btn btn-sm btn-warning" href="#" data-id="<?php echo $row->unit_category_id;?>" data-toggle="modal" data-target="#myModal">Update Price</a>
													</td>	
												</tr>
											<?php endforeach;?>
											<?php else : ?>
												<tr>
													<td colspan="3">No records found.</td>
												</tr>
											<?php endif; ?>
											</tbody>
											</table>
											<?php echo form_close();?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Modal -->
					<div class="modal fade" id="myModal">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">Update Price</h4>
					      </div>
					      <div class="modal-body">
					      	<?php echo form_open('', array('id'=>'change_price_form'));?>
					      		<div class='row'>
					      			<div class='col-md-6 col-md-offset-3'>
		      				      		<div id='cost' class='form-group'>
		      				      			<input type='hidden' name='unit_category_id'></input>
	      							        <?php echo form_input(array('id'=>'cost', 'name'=>'cost', 'placeholder'=>'Enter New Price', 'class'=>'form-control'));?>
	      								    <span class='error-message control-label'></span>
		      							</div>
					      			</div>
					      		</div>
					      </div>
					      <div class="modal-footer">
					      	<div class='form-group'>
						       <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						       <button type="submit" class="btn btn-success">Save</button>
						    </div>
					        <?php echo form_close();?>
					      </div>
					    </div>
					  </div>
					</div>
					<div class="modal fade" id="editItem">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel">Edit Sub-item</h4>
					      </div>
					      <div class="modal-body">
					      	<?php echo form_open('', array('id'=>'updateItem'));?>
					      		<div class='row'>
					      			<div class='col-md-12'>
		      				      		<div class='form-group' id="itr">
		      				      			<input type='hidden' name='unit_category_id'/>
	      							        <?php echo form_input(array('id'=>'item_description', 'name'=>'unit_category_description', 'placeholder'=>'New Item Description', 'class'=>'form-control'));?>
	      								    <span class='error-message control-label'></span>
		      							</div>
					      			</div>
					      		</div>
					      </div>
					      <div class="modal-footer">
						       <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						       <button type="submit" class="btn btn-success">Save</button>
					        <?php echo form_close();?>
					      </div>
					    </div>
					  </div>
					</div>
					<div class="modal fade" id="addSubItem">
					  <div class="modal-dialog">
					    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel">Add <?php echo $item['description'];?> Sub-item</h4>
						      </div>
						      <div class="modal-body">
					      		<?php echo form_open('', array('id'=>'addItem'));?>
					      		<div class='row'>
					      			<div class='col-md-12'>
		      				      		<div id='unit' class='form-group'>
		      				      			<input type='hidden' name='unit' value="<?php echo $item['unit_id'];?>">
		      							</div>
		      							<div id='unit_category_description' class='form-group'>
		      								<?php echo form_label('Item Description','',array('class'=>'control-label'));?>
		      								<?php echo form_input(array('id'=>'unit_category_description','name'=>'unit_category_description','class'=>'form-control','placeholder'=>'Item Description'));?>
		      								<span class='error-message control-label'></span>
		      							</div>
		      							<div id='unit_cost' class='form-group'>
		      								<?php echo form_label('Cost','',array('class'=>'control-label'));?>
		      								<?php echo form_input(array('id'=>'unit_cost','name'=>'unit_cost','class'=>'form-control','placeholder'=>'Cost'));?>
		      								<span class='error-message control-label'></span>
		      							</div>
					      			</div>
					      		</div>
					      </div>
					      <div class="modal-footer">
					      	<div class='form-group'>
						        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						        <button type='submit' class="btn btn-success">Save</button>
						    </div>
					        <?php echo form_close();?>
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
						window.location.href = '<?php echo base_url();?>unit';
						console.log(result);
					}
				}
			});//end of ajax
		});//end of submit

		$('#checkAll').click(function(){
			if(this.checked){
				$('.checkbox1').each(function(){
					this.checked = true;
				});
			}else{
				$('.checkbox1').each(function(){
					this.checked = false;
				});
			}
		});

		$('#delete').click(function(e){
			e.preventDefault();
			var data = $('#myForm').serialize();

			if(data == ""){
				$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h4>');
				$('.modal-body').html('<p>Please select records to be deleted.</p>');
				$('.modal-footer').hide();
			}else{
				$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Confirm</h4>');
				$('.modal-body').html('<p>Are you sure you want to delete?</p>');
				$('.modal-footer .form-group').html('<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button><button type="submit" id="yes" class="btn btn-success">Yes</button>');
			}
			$('#myModal').modal();

			$('#yes').click(function(){
				$.ajax({
					type: 'post',
					url: '<?php echo base_url();?>unit_category/delete',
					data: data,
					dataType: 'json',
					success: function(response) {
						console.log(response);
						if(response.success) {
							window.location  = '<?php echo base_url();?>unit/getSubitems/<?php echo $item["unit_id"];?>';
						} else {
							alert("Unable to delete record.");
						}
					}
				});
			});
		});
	});//end of js

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

	$("#addItem").submit(function(e) {
		e.preventDefault();
		var data = $(this).serialize();

		$("#unit_category_description, #unit_cost").removeClass("has-error");

		$.ajax({
			type: "post",
			data: data,
			url: "<?php echo base_url();?>unit_category/save",
			dataType: "json",
			success: function(response) {
				if(response.success) {
					window.location = "<?php echo base_url();?>unit/getSubitems/<?php echo $item['unit_id'];?>";
				} else {
					if(response.error.unit_category_description) {
						$("#unit_category_description").addClass("has-error");
					}
					if(response.error.unit_cost) {
						$("#unit_cost").addClass("has-error");
					}
				}
			}
		});
	});

	$('#updateItem').submit(function(e){
		e.preventDefault();

		var data = $(this).serialize();

		$.ajax({
			type: 'post',
			url: '<?php echo base_url();?>unit_category/save',
			data: data,
			dataType: 'json',
			success: function(result){
				if(!result.success){
					if(result.error.description){
						$('#itr').addClass('has-error');
						$('#itr .error-message').html(result.error.description);
					}

				}else{
					$('.notification').html(" <strong><h4><i class='fa fa-check-circle'></i>  Item has been updated!</span></h4></strong>");
					$('.notification').slideDown();
					$('.notification').fadeOut(800);
					window.setTimeout(function(){
						window.location = "<?php echo base_url();?>unit/getSubitems/<?php echo $item['unit_id'];?>";
					}, 800);
				}
			}
		});//end of ajax
	});


	$('#editItem').on('show.bs.modal', function(e) {

	    //get data-id attribute of the clicked element
	    var unit_category_id = $(e.relatedTarget).data('id');
	    var item_description = $(e.relatedTarget).data('item');

	    //populate the textbox
	    $(e.currentTarget).find('#updateItem input[name="unit_category_id"]').val(unit_category_id);
	    $(e.currentTarget).find('#updateItem input[name="unit_category_description"]').val(item_description);
	});

	//triggered when modal is about to be shown
	$('#myModal').on('show.bs.modal', function(e) {

	    //get data-id attribute of the clicked element
	    var unit_category_id = $(e.relatedTarget).data('id');

	    //populate the textbox
	    $(e.currentTarget).find('input[name="unit_category_id"]').val(unit_category_id);
	});
</script>

</body>
</html>