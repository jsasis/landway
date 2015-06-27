<html lang='en'>
<?php include_once('/../header.php');?>
<?php include_once('/../nav.php');?>
<body>
	<div id='page-wrapper'>
		<div class='row'>
			<div class='col-md-12 main'>
				<legend>
					<h1 class='manage text-info pull-left'><span class='glyphicon glyphicon-th-list'></span> Manage <small>Items</small></h1>
					<h1 class='pull-right'><a href='<?php echo base_url();?>unit_category/add'class="btn btn-success btn-lg"><strong><i class='fa fa-plus-circle'></i> Create New</a></strong></h1>	
					<div class='clearfix'></div>
				</legend>
				<div class='wrapper'>
					<nav class="navbar navbar-default control-table" role="navigation">
					   <div class="container-fluid">
					      <ul class='nav navbar-nav navbar-left'>
					         <form class="navbar-form navbar-left" role="search">
					            <button id='delete' class="btn btn-danger btn-sm"><i class='fa fa-minus-circle'></i> Delete</button>
					         </form>
					      </ul>
					      <ul class='nav navbar-nav navbar-right'>
					         <form action='<?php echo base_url();?>unit_category/search' method='POST' class="navbar-form navbar-left" role="search">
					            <div class="input-group">
					            	<?php echo form_input(array('id'=>'item','name'=>'item','class'=>'form-control','placeholder'=>'Search item ...'));?>
					            	<span class="input-group-btn"><button type='submit' class='btn btn-default' id='search'>Go</button></span>
					            </div>
					         </form>
					      </ul>
					   </div>
					</nav>
					<?php echo form_open('',array('id'=>'myForm'));?>
					<table id='myTable' class='table table-hover'>
						<thead>
							<tr>
								<th><input type='checkbox' id='checkAll'></input></th>
								<th>Unit</th>
								<th>Item</th>
								<th>Unit Cost</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($result)):?>
								<?php foreach($result as $row):?>
								<tr>
									<td width='30px'><input class='checkbox1' type='checkbox' name='checkbox[]' id='checkbox[]' value='<?php echo $row->unit_category_id;?>'></input></td>
									<td><?php echo $row->unit;?></td>
									<td><?php echo $row->description;?></td>
									<td><?php echo $row->unit_cost;?></td>
									<td class='text-center'><a href='<?php echo base_url();?>unit_category/update/<?php echo $row->unit_category_id;?>'><i class='fa fa-edit'></i> Edit</a></td>
								</tr>
								<?php endforeach;?>
							<?php else:?>
								<tr><td colspan='5'><?php echo 'No Records Found.';?></td></tr>
							<?php endif ?>
						</tbody>
					</table>
					<?php echo form_close();?>
					<?php echo $links;?>
					<div class='pull-right'>
						<label class='control-label'><strong>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $total;?> results<strong></label>
					</div>
				</div>
				<!-- Modal -->
				<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				        <h4 class="modal-title" id="myModalLabel">Confirm</h4>
				      </div>
				      <div class="modal-body">
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
				        <button id='yes' type="button" class="btn btn-primary">Yes</button>
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
	$(document).ready(function(){
		$('.sidebar-nav .nav-second-level ul li:nth-child(2)').addClass('active');

		$('#myTable').tablesorter({
			headers : { 0 : { sorter: false }, 4: { sorter: false} },
		});

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
				$('.modal-header').html('<h3 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h3>');
				$('.modal-body').html('<h4>Please select record/s to be deleted.</h4>');
				$('.modal-footer').hide();
			}else{
				$('.modal-header').html('<h3 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Confirm</h3>');
				$('.modal-body').html('<h4>Are you sure you want to delete selected record/s ?</h4>');
				$('.modal-footer').show();
			}
			$('#myModal').modal();

			$('#yes').click(function(){
				$.ajax({
					type: 'post',
					url: '<?php echo base_url();?>unit_category/delete',
					data: data,
					success: function(result){
						window.location  = '<?php echo base_url();?>unit_category';
					}
				});
			});
		});	
	});
</script>