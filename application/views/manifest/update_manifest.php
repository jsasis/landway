<?php include "/../admin_lte_header.php"; ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class='pull-left'>Manage <?php echo $manifest_details['alpha'];?></h1>
			<h1 class="pull-right"><a href="
						<?php echo base_url();?>manifest/getDetails/<?php echo $manifest_details['manifest_number'];?>" class='btn btn-default man-update-back-btn'>
						<i class='fa fa-mail-reply'></i> Back to list</a></h1>
			<div class='clearfix'></div>
		</section>

		<!-- Main content -->
		<section class="content">
			<div class='row'>
				<div class='col-md-12'>
					<div class="box box-default">
						<div class="box-body">
							<!-- Nav tabs -->
							<ul class="nav nav-tabs">
							  <li class="active"><a href="#truckload" data-toggle="tab">Truckload</a></li>
							  <li><a href="#details" data-toggle="tab">Details</a></li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane fade in active" id="truckload">
							  		<div class='row'>
				  					  	<!-- Loaded -->	  					  	 
				  			        	<div class='col-md-6'>
				  			        		<h3>Truckload</h3>			            
				  			        		<div class='box'>
				  				        		<div class='row'>
				  				        			<div class='col-md-12'>
				  				        				<div class="search-box">
															<?php echo form_input(array('id'=>'search','name'=>'checkbox[]','class'=>'form-control waybill','placeholder'=>'Search waybill ...','autocomplete'=>'off',
															'onchange'=>"load($('#search').serialize())"));?>
														</div>
														<br>
				  				        				<table class='table table-striped table-bordered table-hover'>
				  				        					<thead>
				  				        						<tr>
				  				        							<th></th>
				  				        							<th>Waybill #</th>
				  				        							<th>Consignee</th>
				  				        							<th>Consignor</th>
				  				        						</tr>
				  				        					</thead>
				  				        					<tbody id='manifest'>
				  				        						<?php if($manifest_waybills):?>
				  				        						<?php foreach($manifest_waybills as $row):?>
				  				        						<tr>
				  				        							<td><a href='javascript:void(0)' onclick='unload("<?php echo $row->waybill_number;?>")'><i class='fa fa-minus-square text-danger'></i> </a></td>
				  				        							<td><?php echo $row->waybill_number;?></td>
				  				        							<td><?php echo $row->consignee;?></td>
				  				        							<td><?php echo $row->consignor;?></td>
				  				        						</tr>
				  				        						<?php endforeach;?>
				  				        						<?php else:?>
				  				        						<tr>
				  				        							<td colspan='4' class='text-center'><p>No waybills loaded.</p></td>
				  				        						</tr>
				  				        						<?php endif;?>
				  				        					</tbody>
				  				        				</table>
				  				        			</div>
				  				        		</div>
				  			        		</div>
				  			        	</div>
				  			        	<!-- Unloaded -->
				  			        	<div class='col-md-6'>
				  			        		<h3 class='text-left pull'>Received Waybills <small>select an item</small></h3>
				  			        		<div class='well'>
				  			        			<div class="box-body">
				  			        				<button id='load' class='btn btn-success'><i class="fa fa-download"></i> Load to Manifest</button>
				  			        			</div>
				  			        			<?php echo form_open('',array('id'=>'form'));?>
				  			        			<input type='hidden' id='manifest_number' value='<?php echo $manifest_details["manifest_number"];?>'></input>
				  			        			<table class='table table-condensed table-bordered table-hover' style="background:#fff" id='myTable'>
				  			        				<thead>
				  			        					<tr>
				  			        						<th><input type='checkbox' id='checkAll'></input></th>
				  			        						<th>Waybill #</th>
				  			        						<th>Consignee</th>
				  			        						<th>Consignor</th>
				  			        						<th class='text-center'>Transaction Date</th>
				  			        					</tr>
				  			        				</thead>
				  			        				<tbody>
				  			        					<?php if(!empty($result)):?>
				  			        					<?php foreach($result as $row):?>
				  			        					<tr>
				  			        						<td><input class='row' type='checkbox' name='checkbox[]' id='checkbox' value="<?php echo $row->waybill_number;?>"></input></td>
				  			        						<td><strong><?php echo $row->waybill_number;?></strong></td>
				  			        						<td>						  <?php echo substr($row->consignee, 0, 15);?></td>
				  			        						<td><?php echo substr($row->consignor, 0, 10);?></td>
				  			        						<td class='text-right'><?php echo date('Y-M-d', strtotime($row->transaction_date));?></td>
				  			        					</tr>
				  			        					<?php endforeach;?>
				  			        					<?php else:?>
				  			        					<tr>
				  			        						<td colspan='9'><?php echo 'No records to show.';?></td>
				  			        					</tr>
				  			        				<?php endif;?>
				  			        				</tbody>
				  			        			</table>
				  			        			<?php echo form_close();?>
				  			        			<div class='row'>
				  			        				<div class='col-md-12'>
				  			        					<div class='pull-left'><?php echo $links;?></div>
				  			        					<div class='pull-right'><label class='control-label'><strong>Showing <?php echo $start;?> to <?php echo $end;?> of <?php echo $total;?> results</strong></label></div>
				  			        				</div>
				  			        			</div>
							  				</div>
				  			        		</div>
				  			        	</div>
								</div>
						  		<div class="tab-pane fade" id="details">
						  			<div class='row'>
								  		<div class='col-md-6'>
								  			<div class="box-body">
								  				<?php echo form_open('',array('id'=>'myForm'));?>
							  				<input type='hidden' name='manifest_number' value='<?php echo $manifest_details['manifest_number'];?>'></input>
							  				<div class='form-group' id='truck'>
							  					<?php echo form_label('Truck','',array('class'=>'control-label'));?><span class='text-danger'> *</span>
							  					<?php $attrib = "class='form-control'";?>
							  					<?php foreach($trucks as $truck):?>
							  					<?php $options[$truck->truck_id] = $truck->plate_number;?>
							  					<?php endforeach;?>
							  					<?php echo form_dropdown('truck', $options, $manifest_details['truck_id'], $attrib);?>
							  					<span class='error-message control-label'></span>
							  				</div>
							  				<div class='form-group' id='driver'>
							  					<?php echo form_label('Driver','',array('class'=>'control-label'));?>
							  					<?php echo form_input(array('id'=>'driver','name'=>'driver','class'=>'form-control','value'=>$manifest_details['driver'],'autocomplete'=>'off'));?>
							  					<span class='error-message control-label'></span>
							  				</div>
							  				<div class='form-group' id='trip_to'>
							  					<?php echo form_label('Trip To','',array('class'=>'control-label'));?>
							  					<?php echo form_input(array('id'=>'trip_to','name'=>'trip_to','class'=>'form-control','value'=> $manifest_details['trip_to'],'autocomplete'=>'off'));?>
							  					<span class='error-message control-label'></span>
							  				</div>
							  				<div class='form-group'>
							  					<button type='submit' class='btn btn-success pull-right'><i class='fa fa-save'></i> Save</button>
							  				</div>
							  				<?php echo form_close();?>
								  			</div>
								  		</div>
						  			</div>
						  		</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section><!-- /.content -->
	</div><!-- /.content-wrapper -->
	
<?php include "/../admin_lte_footer.php";?>

<script type='text/javascript'>
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(4)').addClass('active');
	});

	var showloader = false;

	$(document).ready(function(){
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
	});
	$(document).ajaxStart(function(){
	    if (showloader){
	        $('body').loader('show');
	    }
	});    
	$(document).ajaxComplete(function(){
	    window.setTimeout("$('body').loader('hide')", 1000);
	    showloader = false;
	});
	$('#load').click(function(e){
		e.preventDefault();
		var data = $('#form').serialize();
		load(data);
	});
	/*	Load */
	function load(data){
		var manifest_number = <?php echo $manifest_details['manifest_number'];?>;
		
		if(!data){
			alert('Please choose an item.');
			return;
		}
		showloader = true;

		$.ajax({
			type: 'post',
			url: '<?php echo base_url();?>manifest/load/<?php echo $manifest_details["manifest_number"];?>',
			data: data,
			success: function(response){
				if(response){
					$('#loaded.notification').html(" <strong><h4><i class='fa fa-check-circle'></i><span id='message'>  Waybill has been loaded</span></h4></strong>");
					$('#loaded.notification').slideDown('slow');
					$('#loaded.notification').fadeOut(800);
					window.setTimeout(function(){
						window.location	= '<?php echo base_url();?>manifest/update/'+ manifest_number;
					}, 800);
				}
			}
		});
	}
	/*	Unload */
	function unload(x){
		var manifest_number = <?php echo $manifest_details['manifest_number'];?>;
		showloader = true;
		$.ajax({
			type: 'post',
			url: '<?php echo base_url();?>manifest/unload',
			data: {waybill_number: x, manifest_number: manifest_number},
			success: function(response){
				$('#loaded.notification').html(" <strong><h4><i class='fa fa-check-circle'></i><span id='message'>  Waybill has been removed</span></h4></strong>");
				$('#loaded.notification').slideDown();
				$('#loaded.notification').fadeOut(800);
				window.location = "<?php echo base_url();?>manifest/update/" + manifest_number;
			}
		});
	}
	/*	Edit  */
	$('#myForm').submit(function(e){
		e.preventDefault();
		var data = $(this).serialize();
		
		$('#truck, #driver, #trip_to').removeClass('has-error');
		$('#truck .error-message, #driver .error-message, #trip_to .error-message').empty();
		
		showloader = true;
		$.ajax({
			type: 'post',
			url:  '<?php echo base_url();?>manifest/save',
			data: data,
			dataType: 'json',
			success: function(response){
				if(response.success){
					$('.notification').html("<strong><h4><i class='fa fa-check-circle'></i><span id='message'>  Manifest has been updated!</span></h4></strong>");
					$('.notification').slideDown('slow');
					$('.notification').fadeOut(800);
					window.setTimeout(function(){
						window.location = '<?php echo base_url();?>manifest/';
					}, 800);
				}else{
					if(response.error.truck){
						$('#truck').addClass('has-error');
						$('#truck .error-message').html(response.error.truck);
					}
					if(response.error.driver){
						$('#driver').addClass('has-error');
						$('#driver .error-message').html(response.error.driver);
					}
					if(response.error.trip_to){
						$('#trip_to').addClass('has-error');
						$('#trip_to .error-message').html(response.error.trip_to);
					}
				}
			}
		});
	});
	/* Type Ahead*/
	$('.waybill').typeahead({
		source: function (query, process) {
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>waybill/typeAhead/',
				dataType: 'json',
				success: function(result){
					process(result);
					console.log(result);
				},
			});
		},
		matcher: function (item) {
			if (item.toLowerCase().indexOf(this.query.trim().toLowerCase()) != -1) {
				return true;
			}
		},
		highlighter: function (item) {
			var regex = new RegExp( '(' + this.query + ')', 'gi' );
			return item.replace( regex, "<strong>$1</strong>" );
		}
	});	
</script>

</body>
</html>