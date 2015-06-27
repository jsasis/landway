<?php include "/../admin_lte_header.php"; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Manage Posts</h1>
            <h1 class='pull-right'><a href='<?php echo base_url();?>truck/add'class="btn btn-success btn-lg"><i class='fa fa-plus-circle'></i> Create New</a></h1>
            <div class="clearfix"></div>
        </section>
        <!-- Main content -->
        <section class="content">
        	<div class="row">
        		<div class='col-md-12'>
        			<div class="box box-primary">
        				<div class="box-header with-border">
        					<button id='delete' class="btn btn-sm btn-danger"><i class='fa fa-minus-circle'></i> Delete</button>
        				</div>
        				<div class="box-body">
        					<?php echo form_open('',array('id'=>'myForm'));?>
        					<table id='myTable' class='table table-striped'>
        						<thead>
        							<tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Body</th>
        							</tr>
        						</thead>
        						<tbody>
        							<?php if(!empty($result)):?>
        								<?php foreach($result as $row):?>
        									<tr>
        										<td width='30px' style="padding-top: 35px"><input class='row' type='checkbox' name='checkbox[]' id='checkbox[]' value='<?php echo $row->truck_id;?>'></input></td>
        										<td><?php echo $row->post_title;?></td>
                                                <td><?php echo $row->post_body;?></td>
        									</tr>
        								<?php endforeach;?>
        								<?php echo form_close();?>
        							<?php else:?>
        								<tr><td colspan='5'><?php echo 'No Records Found.';?></td></tr>
        							<?php endif;?>
        						</tbody>
        					</table>
        					<?php echo form_close();?>
        				</div>
        			</div>			
        			<div class='pull-left'><?php echo $links;?></div>
        			<div class='pull-right'><label class='control-label'><strong>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $total;?> results</strong></label></div>
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

		$('#myTable').tablesorter({
			headers : { 0 : { sorter: false }, 4: { sorter: false}},
		});

		$('#checkAll').click(function(event) {  //on click 
		    if(this.checked) { // check select status
		        $('.row').each(function() { //loop through each checkbox
		            this.checked = true;  //select all checkboxes with class 'row'               
		        });
		    }else{
		        $('.row').each(function() { //loop through each checkbox
		            this.checked = false; //deselect all checkboxes with class 'row'                       
		        });         
		    }
		});

		$('#delete').click(function(e){
			e.preventDefault();
			var data = $('#myForm').serialize();
			if(data == ""){
				$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h4>');
				$('.modal-body').html('<p>Please select record/s to be deleted.</p>');
				$('.modal-footer').hide();
			}else{
				$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Confirm</h4>');
				$('.modal-body').html('<p>Are you sure you want to delete?</p>');
				$('.modal-footer').show();
			}
			$('#myModal').modal();

			$('#yes').click(function(){
				$.ajax({
					type: 'post',
					url: '<?php echo base_url();?>truck/delete',
					data: data,
					success: function(result){
						window.location  = '<?php echo base_url();?>truck';
					}
				});
			});
		});

	});
</script>

</body>
</html>
