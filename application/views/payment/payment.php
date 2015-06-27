<html lang='en'>
<?php include_once('/../header.php');?>
<?php include_once('/../nav.php');?>
<body>
	<div id='page-wrapper'>
		<div class='row'>
			<div class='col-md-12 main'>
				<legend>
					<h1 class='text-info manage pull-left'><span class='glyphicon glyphicon-th-list'></span> Manage <small>Payments</small></h1>
					<h1 class='pull-right'><a href='<?php echo base_url();?>payment/add'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
					<div class='clearfix'></div>
				</legend>
				<div class='wrapper'>
					<?php if($this->session->flashdata('notification')):?>
						<div class='alert alert-success'><?php echo $this->session->flashdata('notification');?></div>
					<?php endif?>
					<nav class="navbar navbar-default control-table" role="navigation">
					   <div class="container-fluid">
					      <ul class='nav navbar-nav navbar-left'>
					         <form class="navbar-form navbar-left" role="search">
					            <button id='delete' class='btn btn-danger'><fa class='fa fa-minus-circle'></i> Delete</button>
					         </form>
					      </ul>
					   </div>
					</nav>
					<?php echo form_open('',array('id'=>'myForm'));?>
					<table class='table table-hover' id='myTable'>
						<thead>
							<tr>
								<th><input type='checkbox' id='checkAll'></input></th>
								<th>Waybill #</th>
								<th class='text-center'>Payment Terms</th>
								<th class='text-center'>Amount Paid</th>
								<th class='text-center'>Payment Date</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($result)):?>
								<?php foreach($result as $row):?>
								<tr>
									<td><input class='row' type='checkbox' name='checkbox[]' id='checkbox[]' value="<?php echo $row->payment_id;?>"></input></td>
									<td><strong><?php echo $row->waybill_number;?></strong></td>
									<td class='text-center'><?php echo $row->payment_terms;?></td>
									<td class='text-center'>&#8369; <?php echo $row->amount;?></strong></td>
									<td class='text-center'><?php echo date('l, M d, Y g:i A',strtotime($row->date));?></td>
								</tr>
								<?php endforeach?>
							<?php else:?>
								<tr>
									<td colspan='9'><?php echo 'No record/s found.';?></td>
								</tr>
							<?php endif ?>
						</tbody>
					</table>
					<?php echo form_close();?>
					<div class='pull-left'>
						<?php echo $links;?>
					</div>
					<div class='pull-right'>
						<label class='control-label'><strong>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $total;?> results<strong></label>
					</div>
				</div>
			</div>
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
</body>
</html>
<script type='text/javascript'>
	$(document).ready(function(){
		if($('.alert').length != 0){
			$('.alert').fadeOut(4000);
		}
		$('ul.navbar-left li:nth-child(1)').addClass('active');

		$('#myTable').tablesorter({
			headers : { 0 : { sorter: false } },
		});

		$('#checkAll').click(function(){
			if(this.checked){
				$('.row').each(function(){
					this.checked = true;
				});
			}else{
				$('.row').each(function(){
					this.checked = false;
				});
			}
		});

		$('#delete').click(function(e){
			e.preventDefault();
			var data = $('#myForm').serialize();
			if(data == ""){
				$('.modal-header').html('<h3 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h3>');
				$('.modal-body').html('<h3>Nothing is selected.</h3><h4>Please select record/s to be deleted.</h4>');
				$('.modal-footer').hide();
			}else{
				$('.modal-header').html('<h3 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Confirm Delete</h3>');
				$('.modal-body').html('<h4>Are you sure you want to delete?</h4> <h4><small>This will be deleted permanently.</small></h4>');
				$('.modal-footer').show();
			}
			$('#myModal').modal();

			$('#yes').click(function(){
				$.ajax({
					type: 'post',
					url: '<?php echo base_url();?>payment/delete',
					data: data,
					success: function(result){
						window.location  = '<?php echo base_url();?>payment';
					}
				});
			});
		});	
	});
</script>