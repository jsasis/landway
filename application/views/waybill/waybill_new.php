<?php include "/../admin_lte_header.php"; ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class='pull-left'> Create Waybill</h1>
			<h1 class='pull-right'><a href='<?php echo base_url();?>customer/add' class='btn btn-success btn-lg'><i class='fa fa-plus-circle'></i> Customer</a></h1>
			<div class='clearfix'></div>
		</section>
		<!-- Main content -->
		<section class="content">
			<?php echo form_open('',array('id'=>'myForm', 'class'=>'form-horizontal'));?>
			<input type="hidden" name="status" value="Received">	
			<!-- Customer Details -->
			<div class='row'>
				<div class='col-md-12'>
					<div class="box box-default">
						<div class="box-body">
							<div id='error' class="text-center"></div>
							<div class="clearfix"></div>
							<div class="callout callout-info">
							<h4>Please fill out required fields.</h4>
							<p>Fields with <span class='text-danger'>*</span> are required.</p></div>
							<!-- Customer Info -->
							<div class='row'>
								<!-- Consignee -->
								<div class='col-md-6'>
									<div class='form-group' id='consignee'>
										<?php $isConsignee = true;?>
										<input type='hidden' id='ce_id' name='ce_id'>
										<?php echo form_label('Consignee','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
										<div class='col-md-9'>
											<?php echo form_input(array('id'=>'consignee','name'=>'consignee','class'=>'form-control customer consignee','placeholder'=>'Consignee', 'autocomplete'=>'off', 'onchange'=>"setCustomerData('<?php echo $isConsignee;?>')"));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='address_1'>
										<?php echo form_label('Address','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
										<div class='col-md-9'>
											<?php echo form_input(array('id'=>'address_1','name'=>'address_1','class'=>'form-control','placeholder'=>'Consignee Address'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='notes'>
										<?php echo form_label('Notes','',array('class'=>'control-label col-md-2'));?>
										<div class='col-md-9'>
											<textarea id='notes' name='notes' class='form-control' placeholder='(Optional)' rows='5'></textarea>
										</div>
									</div>
								</div>
								<!-- Consignor -->
								<div class='col-md-6'>
									<div class='form-group' id='consignor'>
										<input type='hidden' id='cr_id' name='cr_id'>
										<?php echo form_label('Consignor','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
										<div class='col-md-9'>
											<?php echo form_input(array('id'=>'consignor','name'=>'consignor','class'=>'form-control customer consignor','placeholder'=>'Consignor', 'autocomplete'=>'off', 'onchange'=>'setCustomerData()'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='address_2'>
										<?php echo form_label('Address','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
										<div class='col-md-9'>
											<?php echo form_input(array('id'=>'address_2','name'=>'address_2','class'=>'form-control','placeholder'=>'Consignor Address'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='dr_number'>
										<?php echo form_label('CI/DR #','',array('class'=>'control-label col-md-2'));?>
										<div class='col-md-5'>
											<?php echo form_input(array('id'=>'dr_number','name'=>'dr_number','class'=>'form-control','placeholder'=>'CI/DR #'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='is_backload'>
										<?php echo form_label('Backload','',array('class'=>'control-label col-md-2'));?>
										<div class='col-md-4'>
											<?php $data = array(0=>'No',1=>'Yes');?>
											<?php $attrib = "class='form-control'";?>
											<?php echo form_dropdown('is_backload', $data, 0, $attrib);?>
											<span class='error-message control-label'> </span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>	<!-- /.customer details -->			
			<!-- Items -->
			<div class='row'>
				<div class='col-md-8'>
					<div class="box box-default">
						<div class="box-body">
							<table class='table table-hover'>
								<thead>
									<tr>
										<th></th>
										<th>Qty <span class='text-danger'>*</span></th>
										<th>Unit <span class='text-danger'>*</span></th>
										<th>Cost <span class='text-danger'>*</span></th>
										<th>Item Description <span class='text-danger'>*</span></th>
										<th class='text-center'>Sub-Total </th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan='4'><button id='addMore' class='btn btn-sm btn-info'><span class='glyphicon glyphicon-plus'></span></button></td>
										<td class='text-center'><strong>GRAND TOTAL</strong></td>
										<td id='total' class='text-center'><strong> 0.00</strong></td>
									</tr>
								</tfoot>
								<tbody id='wrapper'>
								<input type='hidden' id='total' name='total'>
									<tr id="1">
										<input type='hidden' id='hidden' name='id[]'>
										<td style='width:30px'> </td>
										<td style='width:100px' class='quantity'>
											<?php echo form_input(array('id'=>'qty','name'=>'quantity[]','class'=>'form-control','placeholder'=>'Qty', 'autocomplete'=>'off', 'onkeyup'=>"setVal(null, true)",'data-toggle'=>'popover','data-placement'=>'top','data-content'=>'Please enter quantity.'));?>
										</td>
										<td style='width:200px' class='unit'>
											<?php echo form_input(array('id'=>'unit','name'=>'unit[]','class'=>'form-control typeahead','placeholder'=>'Unit','onchange'=>'setVal()'));?>
										</td>
										<td style='width:100px' class='unit_price'>
											<?php echo form_input(array('id'=>'unit_price','name'=>'unit_price[]','class'=>'form-control', 'placeholder'=>'Cost', 'onkeyup'=>"setVal(null, false)"));?>
										</td>
										<td class='item_description'>
											<?php echo form_input(array('id'=>'item_description','name'=>'item_description[]','class'=>'form-control', 'placeholder'=>'Item Description'));?>
										</td>
										<td class='price text-center' id='price' style='vertical-align:middle'><strong> 0.00</strong></td>
										<input type='hidden' id='sub_total' name='sub_total[]'>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div> <!-- /.items -->
				<div class="col-md-4">
					<div class="box box-default">
						<div class="box-body">
							<div class="row">
								<div class="col-md-10 col-md-offset-1">
									<div class='form-group' id='payment_terms'>
										<?php echo form_label('Terms','',array('class'=>'control-label'));?>
											<?php $data = array('prepaid'=>'Prepaid','collect'=>'Collect');?>
											<?php $attrib = "class='form-control'";?>
											<?php echo form_dropdown('payment_terms', $data, 'collect', $attrib);?>
											<span class='error-message control-label'> </span>
									</div>
									<div id='payment'>
										<div class='form-group' id='amount'>
											<?php echo form_label('Amount','' , array('class'=>'control-label'));?>
											<?php echo form_input(array('id'=>'amount','name'=>'amount','class'=>'form-control','placeholder'=>'Enter Amount'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> 
			</div> 
			<div class="row">
				<div class="col-md-12">
					<div class="box-footer text-right">
						<a class='btn btn-default' href="<?php echo base_url();?>waybill">Cancel</a>
						<button id='save' class='btn btn-success'>Save</button>
					</div>
				</div>
			</div>
			<?php echo form_close();?>
		</section><!-- /.content -->
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-info-sign"></span> Unknown Customer</h4>
					</div>
					<div class="modal-body">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"> No</button>
					</div>
				</div>
			</div>
		</div> <!-- /.modal -->
	</div><!-- /.content-wrapper -->

<?php include "/../admin_lte_footer.php";?>

<!-- add dynamic element-->
<script type='text/javascript'>
	$(document).ready(function(){
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(3)').addClass('active');
		$('.sidebar-menu li.active .treeview-menu li:nth-child(1)').addClass('active');
		
		$('.consignee').on('keyup',function(){
			customer_type = 'customer';
		});
		$('.consignor').on('keyup',function(){
			customer_type = 'supplier';
		});

		$('#payment').hide();
		$("#payment_terms").change(function(){
		   $( "#payment_terms option:selected").each(function(){
			   if($(this).attr("value") == "prepaid"){
					$('#payment').show();
			   }else{
					$('#payment').hide();
			   }
		   });
		}).change();
		
		var maxFields = 8;
		var wrapper = $('tbody#wrapper');
		var addButton = $('#addMore');
		var x = 1; //set row count to 1
		
		//initialize typeahead
		var settings = {
			source: function(typeahead, process){
				$.ajax({
					type: 'post',
					url: '<?php echo base_url();?>unit_category/getItems/',
					dataType: 'json',
					success: function(result){
						process(result);
					}
				});
			},
			matcher: function(item){
				if (item.toLowerCase().indexOf(this.query.trim().toLowerCase()) != -1) {
					return true;
				}
			},
			highlighter: function(item){
				var regex = new RegExp( '(' + this.query + ')', 'gi' );
				return item.replace( regex, "<strong>$1</strong>" );
			}
		};
		$('.typeahead').typeahead(settings);
		
		//append dynamic form
		$(addButton).click(function(e){
			e.preventDefault();
			if(x < maxFields){
				x++;
				var child = '<tr id='+x+'><input type="hidden" id="hidden'+x+'" name="id[]"><td width="30px" style="vertical-align:middle">'+
				'<a href="javascript:void(0)" id="'+x+'" class="remove text-danger"><span class="glyphicon glyphicon-minus"></span></a></td>'+
				'<td style="width:100px"><?php echo form_input(array("id"=>"qty'+x+'","name"=>"quantity[]","class"=>"form-control","placeholder"=>"Qty","onkeyup"=>"setVal('+x+', true)","data-toggle"=>"popover","data-placement"=>"top","data-content"=>"QTY is required"));?></td>'+
				'<td style="width:100px"><?php echo form_input(array("id"=>"unit'+x+'","name"=>"unit[]","class"=>"form-control typeahead","placeholder"=>"Unit", "onchange"=>"setVal('+x+')"));?></td>'+
				'<td style="width:100px"><?php echo form_input(array("id"=>"unit_price'+x+'","name"=>"unit_price[]","class"=>"form-control","placeholder"=>"Cost","onkeyup"=>"setVal('+x+', false)"));?></td>'+
				'<td class="item_description"><?php echo form_input(array("id"=>"item_description'+x+'","name"=>"item_description[]","class"=>"form-control","placeholder"=>"Item Description"));?></td>'+
				'<td class="price text-center" id="price'+x+'" style="vertical-align:middle"><strong> 0.00</strong></td>'+
				'<input type="hidden" id="sub_total'+x+'" name="sub_total[]">'+
				'</tr>';
				$(wrapper).append(child);
				$('.typeahead').trigger('added');
			}
		}); 
		//bind typeahead
		$('.typeahead').on('added',function(){
			$('.typeahead').typeahead(settings);
		});
		//remove item from DOM
		$(wrapper).on('click','.remove',function(e){
			e.preventDefault();
			x--;
			$(this).parent().parent().remove();
			var item_id = $(this).attr('id');
			$.each(prices, function(i){
				if(prices[i].id == parseInt(item_id)){
					prices.splice(i, 1); //remove item from prices[] array
				
					return false;
				}
			});
			calculate();
		}); 
	});

	var showloader = false;
	$(document).ajaxStart(function(){
		if (showloader){
			$('body').loader('show');
		}
	});    
	$(document).ajaxComplete(function(){
		setTimeout("$('body').loader('hide')", 800);
		showloader = false;
	});
</script>
<!-- setVal items -->
<script type='text/javascript'>
	var prices = [];
	
	function setVal(rowIndex, y){
		var data, qty, unit_cost, price;
		// on cost change. first row
		if(rowIndex == null && y == false) {
			id = 1;
			qty 		= parseInt($('#qty').val());
			unit_cost	= parseFloat($('#unit_price').val());
			
			if(!unit_cost) unit_cost = 0;

			price = qty * unit_cost;

			$('#sub_total').val(price);
			$('#price').html('<strong> '+ numeral(price).format('0.00') +'</strong>');

			remove(1);

			prices.push({ //add item total to prices[]
				id: 	1,
				qty: 	qty,  
				price: 	price
			});

			calculate();
		// on cost change. dynamic row
		} else if(rowIndex != null && y == false) {
			
			qty = parseInt($('#qty'+ rowIndex +'').val());
			unit_cost = parseFloat($('#unit_price'+ rowIndex +'').val());
			
			if(!unit_cost) unit_cost = 0;

			price = qty * unit_cost;

			$('#sub_total'+ rowIndex +'').val(price);
			$('#price'+ rowIndex +'').html('<strong> ' + numeral(price).format('0.00') +'</strong>');

			remove(rowIndex);

			prices.push({ //add item total to prices[]
				id: 	rowIndex,
				qty: 	qty,  
				price: 	price
			});

			calculate();
		// on qty change
		} else if(rowIndex == null && y == true) {
			id = 1;
			qty 		= parseInt($('#qty').val());
			unit_cost	= parseFloat($('#unit_price').val());
			if(!qty) qty = 0;
			if(!unit_cost) unit_cost = 0;

			price = qty * unit_cost;

			$('#sub_total').val(price);
			$('#price').html('<strong> '+ numeral(price).format('0.00') +'</strong>');

			remove(1);

			prices.push({ //add item total to prices[]
				id: 	1,
				qty: 	qty,  
				price: 	price
			});

			calculate();
		// on qty change
		} else if(rowIndex && y == true) {
			id = rowIndex;
			qty 		= parseInt($('#qty'+rowIndex).val());
			unit_cost	= parseFloat($('#unit_price'+rowIndex).val());
			if(!qty) qty = 0;
			if(!unit_cost) unit_cost = 0;

			price = qty * unit_cost;

			$('#sub_total'+ rowIndex).val(price);
			$('#price'+ rowIndex).html('<strong> '+ numeral(price).format('0.00') +'</strong>');

			remove(rowIndex);

			prices.push({ //add item total to prices[]
				id: 	rowIndex,
				qty: 	qty,  
				price: 	price
			});

			calculate();
		// on unit change
		}else { 
			if(rowIndex == null) {
				if($('#qty').val() == ""){
					$('#qty').popover('show');

					return false;
				} else {
					data = $('#unit').serialize();
					qty = $('#qty').val();
				}
			} else {
				if($('#qty'+ rowIndex +'').val() == ""){
					$('#qty'+ rowIndex +'').popover('show');

					return false;
				} else {
					data = $('#unit'+ rowIndex +'').serialize();
					qty = $('#qty'+ rowIndex +'').val();
				}
			}	

			$.ajax({
				type: 'post',
				data: data.slice(0, -8),
				url: '<?php echo base_url();?>unit_category/getItemPrice',
				dataType:'json',
				success: function(result){
					if(result.success){
						if(result.result.cost_id == null){ // if cost_id is null then the user is the trying to input an uncategorized unit
							if(rowIndex == null) {
								$('tr#1 #hidden').val('');
								$('#unit_price').val('');
								$('#price').html('<strong> ' + numeral(price).format('0.00') +'</strong>');
								remove(1);
							}else{
								$('#hidden'+ rowIndex).val('');
								$('#unit_price'+ rowIndex +'').val('');
								$('#price'+ rowIndex +'').html('<strong> ' + numeral(price).format('0.00') +'</strong>');
								remove(rowIndex);
							}
							calculate();
							
							return;
						}
						if(rowIndex == null) {
							unit_cost = result.result.unit_cost;
							$('#hidden')	.val(result.result.cost_id);
							$('#unit')		.val(result.result.unit);
							$('#unit_price').val(unit_cost);

							price = qty * unit_cost; //compute line total

							$('#sub_total').val(price);
							$('#price')   .html('<strong> '+ numeral(price).format('0.00') +'</strong>');
							remove(1); //check if id exists in prices[]
							prices.push({ //add item total to prices[]
								id: 	1,
								qty: 	qty,  
								price: 	price
							});
							$(".item_description input").focus();
						}else{
							unit_cost = result.result.unit_cost;
							$('#hidden'+ rowIndex +'')	 .val(result.result.cost_id);
							$('#unit'+ rowIndex +'')		 .val(result.result.unit);
							$('#unit_price'+ rowIndex +'').val(unit_cost);

							price = qty * unit_cost; //compute line total

							$('#sub_total'+ rowIndex +'').val(price);
							$('#price'+ rowIndex +'')   .html('<strong> ' + numeral(price).format('0.00') +'</strong>');
							remove(rowIndex); //check if id exists in prices[]
							prices.push({ //add item total to prices[]
								id: 	rowIndex,
								qty: 	qty,
								price: 	price
							});
							$("#item_description"+ rowIndex).focus();
							}
						}
						
						calculate();
					}
			});
		}	
	}

	//if presented id exists in array,remove associated values
	function remove(rowIndex){
		for(var i in prices){
			if(prices[i]['id'] == rowIndex){
				 prices.splice(i, 1); //remove from array where id == rowIndex
			}
		}
	}

	//calculate GRAND TOTAL
	function calculate(){
		var total = 0;

		for(var i in prices){
			total += prices[i]["price"];
			console.log(prices[i]);
		}
		
		$('input#total').val(total);
		$('input#amount').val(numeral(total).format('0.00'));
		$('#total').html('<strong> ' + numeral(total).format('0.00') +'</strong>');
	}
</script>

<!-- submit -->
<script type='text/javascript'>
	$('#save').click(function(e){
		e.preventDefault();
		$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Confirm </h4>');
		$('.modal-body').html('Are you sure you want to save ?');
		$('.modal-footer').html('<button type="button" class="btn btn-default" data-dismiss="modal"> No</button><button id="yes" data-dismiss="modal" class="btn btn-primary">Yes</button>');
		$('#myModal').modal();

		$('#yes').click(function(){
			$('#consignee,#consignor,#address_1,#address_2,#payment_terms,#amount,#dr_number').removeClass('has-error');
			$('#consignee .error-message,#consignor .error-message,#address_1 .error-message,#address_2 .error-message,#payment_terms .error-message,#amount .error-message,#dr_number .error-message').empty();
			$('#error').hide();
			showloader = true;
			var data = $('#myForm').serialize();
			
			$.ajax({
				type:'post',
				url: '<?php echo base_url();?>waybill/save',
				data: data,
				dataType: 'json',
				success: function(result){
					if(!result.success){
						window.scrollTo(0, 0);
						if(!result.error.consignee && result.error.ce_id){
							$('.modal-body').html("Input value for <strong> CONSIGNEE </strong> is unknown. Are you trying to add a <strong> NEW CUSTOMER</strong> ?");
							$('.modal-footer').html("<button type='button' class='btn btn-default' data-dismiss='modal'> No</button><a href='<?php echo base_url();?>customer/add' class='btn btn-primary'> Yes</a>");
							$('#myModal').modal();

							return;
						}
						if(!result.error.consignor && result.error.cr_id){
							$('.modal-body').html("Input value for <strong> CONSIGNOR </strong> is unknown. Are you trying to add a <strong> NEW CUSTOMER</strong> ?");
							$('.modal-footer').html("<button type='button' class='btn btn-default' data-dismiss='modal'> No</button><a href='<?php echo base_url();?>customer/add' class='btn btn-primary'> Yes</a>");
							$('#myModal').modal();

							return;
						}
						if(result.error.consignee){
							$('#consignee').addClass('has-error');
							$('#consignee .error-message').html(result.error.consignee);
						}
						if(result.error.consignor){
							$('#consignor').addClass('has-error');
							$('#consignor .error-message').html(result.error.consignor);
						}
						if(result.error.address_1){
							$('#address_1').addClass('has-error');
							$('#address_1 .error-message').html(result.error.address_1);
						}
						if(result.error.address_2){
							$('#address_2').addClass('has-error');
							$('#address_2 .error-message').html(result.error.address_2);
						}
						if(result.error.dr_number){
							$('#dr_number').addClass('has-error');
							$('#dr_number .error-message').html(result.error.dr_number);
						}
						if(result.error.payment_terms){
							$('#payment_terms').addClass('has-error');
							$('#payment_terms .error-message').html(result.error.payment_terms);
						}
						if(result.error.amount){
							$('#amount').addClass('has-error');
							$('#amount .error-message').html(result.error.amount);
						}
						if(result.error.quantity){
							//$('thead tr th:nth-child(2)').addClass('label-danger');
							itemError();
						}
						if(result.error.unit){
							itemError();
						}
						if(result.error.description){
							itemError();
						}
						if(result.error.item_description){
							itemError();
						}
						if(result.error.unit_price){
							itemError();
						}
						showLoader = false;
					}else{
						$('.notification').slideDown('slow').fadeOut('slow');
						$('#myForm')[0].reset();
						$('#payment').hide();
						$('td#total, .price').html('<strong> 0.00</strong>');
					}
				},
			});
		});
	}); 

	function close(){
		$('.notification').fadeOut('slow');

	}
	function itemError(){
		$('#error').html('<div class="alert alert-warning"><span><i class="fa fa-warning fa-4x"></i></span>' +
			'<h5>There were problems with your inputs. Please fill-out required fields and with the correct format.</h5></div>');
		$('#error').show();
	}
</script>
<!-- customer typeahead -->
<script type='text/javascript'> 
	var customer_type = '';
	$('.customer').typeahead({
		source: function (query, process) {
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>customer/typeAhead/'+ customer_type,
				dataType: 'json',
				success: function(result){
					process(result);
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

	function setCustomerData(isConsignee){
			var data;
			var input;
			var hidden;
			if(isConsignee){
				data = $('input#consignee').val();
				input = $('input#address_1');
				hidden = $('input#ce_id');
			}else{
				data = $('input#consignor').val();
				input = $('input#address_2');
				hidden = $('input#cr_id');
			}
			$.ajax({
				type: 'post',
				data: {customer: data},
				url: '<?php echo base_url();?>customer/search',
				dataType:'json',
				success: function(result){
					if(result.success){
						if(result.result == null){
							input.val('');
							hidden.val('');
							return;
						}else{
							hidden.attr('value',result.result.customer_id);
							input.val(result.result.complete_address);
						}
					}
				}
			});
	}
</script>

</body>
</html>