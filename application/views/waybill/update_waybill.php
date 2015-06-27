<?php include "/../admin_lte_header.php";
	$payment_terms = $row["payment_terms"];
	$waybill_number = $row["waybill_number"]; ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		
		<!-- Main content -->
		<section class="content">
			<h2>Waybill # <strong><?php echo $row['waybill_number'];?></strong> <small><i>edit</i></small></h2>

			<?php echo form_open('', array('id'=>'myForm', 'class'=>'form-horizontal'));?>
			<input type="hidden" name="payment_terms" value="<?php echo $payment_terms;?>">
			<!-- Customer Details -->
			<div class='row'>
				<div class='col-md-12'>
					<div class="box box-primary">
						<div class="box-body">
							<div id='error' class="text-center"></div>
							<div class="clearfix"></div>	
							<p>Fields with <strong class='text-danger'>*</strong> are required.</p>
							<!-- Customer Info -->
							<div class='row'>
								<!-- Consignee -->
								<div class='col-md-6'>
									<div class='form-group' id='consignee'>
										<?php echo form_input(array('name'=>'status','type'=>'hidden','value'=>set_value('status', $row['status'])));?>
										<?php $isConsignee = true;?>
										<input type='hidden' id='ce_id' name='ce_id' value="<?php echo $row['consignee_id'];?>">
										<input type='hidden' id='waybill_number' name='waybill_number' value="<?php echo $row['waybill_number'];?>">
										<?php echo form_label('Consignee','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
										<div class='col-md-9'>
											<?php echo form_input(array('id'=>'consignee','name'=>'consignee','class'=>'form-control customer consignee','placeholder'=>'Consignee', 
											'autocomplete'=>'off', 'value'=>$row['consignee'],'onchange'=>"setCustomerData('<?php echo $isConsignee;?>')"));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='address_1'>
										<?php echo form_label('Address','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
										<div class='col-md-9'>
											<?php echo form_input(array('id'=>'address_1','name'=>'address_1','class'=>'form-control','value'=>$row['address1'],'placeholder'=>'Consignee Address'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='notes'>
										<?php echo form_label('Notes','',array('class'=>'control-label col-md-2'));?>
										<div class='col-md-9'>
											<textarea id='notes' name='notes' class='form-control' placeholder='(Optional)' rows='5'><?php echo $row["notes"];?></textarea>
										</div>
									</div>
								</div>
								<!-- Consignor -->
								<div class='col-md-6'>
									<div class='form-group' id='consignor'>
										<input type='hidden' id='cr_id' name='cr_id' value="<?php echo $row['consignor_id'];?>">
										<?php echo form_label('Consignor','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
										<div class='col-md-9'>
											<?php echo form_input(array('id'=>'consignor','name'=>'consignor','class'=>'form-control customer consignor',
											'value'=>$row['consignor'], 'placeholder'=>'Consignor', 'autocomplete'=>'off', 'onchange'=>'setCustomerData()'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='address_2'>
										<?php echo form_label('Address','',array('class'=>'control-label col-md-2'));?><span class='text-danger'> *</span>
										<div class='col-md-9'>
											<?php echo form_input(array('id'=>'address_2','name'=>'address_2','class'=>'form-control', 'value'=>$row['address2'],'placeholder'=>'Consignor Address'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='dr_number'>
										<?php echo form_label('CI/DR #','',array('class'=>'control-label col-md-2'));?>
										<div class='col-md-5'>
											<?php echo form_input(array('id'=>'dr_number','name'=>'dr_number','class'=>'form-control', 'value'=>$row['dr_number'], 'placeholder'=>'CI/DR #'));?>
											<span class='error-message control-label'></span>
										</div>
									</div>
									<div class='form-group' id='is_backload'>
										<?php echo form_label('Backload','',array('class'=>'control-label col-md-2'));?>
										<div class='col-md-4'>
											<?php $data = array(0=>'No',1=>'Yes');?>
											<?php $attrib = "class='form-control'";?>
											<?php echo form_dropdown('is_backload', $data, $row["is_backload"], $attrib);?>
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
				<div class='col-md-12'>
					<div class="box box-default">
						<div class="box-body">
							<table class='table table-hover'>
								<thead>
									<tr>
										<th></th>
										<th>Qty <span class='text-danger'>*</span></th>
										<th>Unit <span class='text-danger'>*</span></th>
										<th>Cost <span class='text-danger'>*</span></th>
										<th>Item Description</th>
										<th class='text-center'>Sub-Total</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan='4'><button id='addMore' class='btn btn-sm btn-info'><span class='glyphicon glyphicon-plus'></span></button></td>
										<td class='text-center'><strong>GRAND TOTAL</strong></td>
										<td id='total' class='text-center'><strong><?php echo number_format($row['total'],'2','.',',');?></strong></td>
									</tr>
								</tfoot>
								<tbody id='wrapper'>
									<input type='hidden' id='total' name='total' value="<?php echo $row['total'];?>">
									<?php $i = 0;?>
									<?php foreach($resultItems as $row): ?>
									<?php $i++;?>
										<tr id = <?php echo $i;?> >
											<input type='hidden' id='hidden' name='id[]' value=<?php echo $row->cost_id;?> >
											<td style='width:30px; vertical-align:middle'><a href="javascript:void(0)" id="<?php echo $i;?>" class="remove text-danger"><span class="glyphicon glyphicon-minus"></span></a></td>
											<td style='width:100px' class='quantity'>
												<?php echo form_input(array('id'=>"qty$i",'name'=>'quantity[]','class'=>'form-control','placeholder'=>'Qty', 'value'=>$row->quantity, 'autocomplete'=>'off', 'onkeyup'=>"setVal($i, true)",'data-toggle'=>'popover','data-placement'=>'top','data-content'=>'Please input value for QTY'));?>
											</td>
											<td style='width:200px' class='unit'>
												<?php echo form_input(array('id'=>"unit$i",'name'=>'unit[]','class'=>'form-control typeahead','placeholder'=>'Unit', 'value'=>$row->unit_code, 'onchange'=>"setVal($i)"));?>
											</td>
											<td style='width:100px' class='unit_price'>
												<?php echo form_input(array('id'=>"unit_price$i",'name'=>'unit_price[]','class'=>'form-control', 'placeholder'=>'Cost', 'value'=>$row->unit_cost, 'onkeyup'=>"setVal(null, false)"));?>
											</td>
											<td class='item_description'>
												<?php echo form_input(array('id'=>"item_description$i",'name'=>'item_description[]','class'=>'form-control', 'value'=>$row->item_description, 'placeholder'=>'Item Description'));?>
											</td>
											<td class='price text-center' id="price<?php echo $i;?>" style='vertical-align:middle'><strong><?php echo number_format($row->sub_total,'2','.',',');?></strong></td>
											<input type='hidden' id='sub_total1' name='sub_total[]' value='<?php echo $row->sub_total;?>'>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div> <!-- /.items -->
				<!-- <div class="col-md-4">
					<div class="box box-danger">
						<div class="box-body">
							<div class="row">
								<div class="col-md-10 col-md-offset-1">
									<div class='form-group' id='payment_terms'>
										<?php echo form_label('Payment Terms','',array('class'=>'control-label'));?>
										<?php $data = array(''=>'Choose Terms of Payment','prepaid'=>'Prepaid','collect'=>'Collect');?>
										<?php $attrib = "class='form-control'";?>
										<?php echo form_dropdown('payment_terms', $data, $payment_terms, $attrib);?>
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
				</div> -->
			</div> 
			<div class="row">
				<div class="col-md-12">
					<div class="box-footer text-right">
						<a class='btn btn-default' href="<?php echo base_url();?>waybill/details/<?php echo $waybill_number;?>">Cancel</a>
						<button id='save' class='btn btn-success'>Save</button>
					</div>
				</div>
			</div>
			<?php echo form_close(); ?>
		</section><!-- /.content -->
	</div><!-- /.content-wrapper -->

<?php include "/../admin_lte_footer.php"; ?>	

<script type='text/javascript'>
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
<!-- add row -->
<script type='text/javascript'>
	var prices = [];

	$(document).ready(function(){
		// add class 'active' to navbar
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(3)').addClass('active');
		
		// load datepicker
		$('.datepicker').datepicker({
				format: 'yyyy-mm-dd',
				startDate: "-",
				todayHighlight: true,
				autoclose: true
			});
		
		// set customer type on consignee/consignor field keyup event
		$('.consignee').on('keyup',function(){

			customer_type = 'customer';
		});
		$('.consignor').on('keyup',function(){

			customer_type = 'supplier';
		});
		
		var maxFields = 8;
		var wrapper = $('tbody#wrapper');
		var addButton = $('#addMore');
		var x = $('tbody#wrapper tr').length;

		init(x);
		
		//initialize typeahead
		var settings = {
			source: function(typeahead, process){
				$.ajax({
					type: 'post',
					url: '<?php echo base_url();?>unit_category/getItems',
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

		//add more item
		$(addButton).click(function(e){
			e.preventDefault();
			if(x < maxFields){
				x++;
				var child = '<tr id='+x+'><input type="hidden" id="hidden'+x+'" name="id[]"><td width="30px" style="vertical-align:middle">'+
				'<a href="javascript:void(0)" id="'+x+'" class="remove text-danger"><span class="glyphicon glyphicon-minus"></span></a></td>'+
				'<td style="width:100px"><?php echo form_input(array("id"=>"qty'+x+'","name"=>"quantity[]","class"=>"form-control","placeholder"=>"Qty","onkeyup"=>"setVal('+x+', true)","data-toggle"=>"popover","data-placement"=>"top","data-content"=>"QTY is required"));?></td>'+
				'<td style="width:100px"><?php echo form_input(array("id"=>"unit'+x+'","name"=>"unit[]","class"=>"form-control typeahead","placeholder"=>"Unit", "onchange"=>"setVal('+x+')"));?></td>'+
				'<td style="width:100px"><?php echo form_input(array("id"=>"unit_price'+x+'","name"=>"unit_price[]","class"=>"form-control","placeholder"=>"Cost","onkeyup"=>"setVal('+x+', false)"));?></td>'+
				'<td><?php echo form_input(array("id"=>"item_description'+x+'","name"=>"item_description[]","class"=>"form-control","placeholder"=>"Item Description"));?></td>'+
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

		//remove item
		$(wrapper).on('click','.remove',function(e){
			e.preventDefault();

			if(x > 1){
				x--;
				$(this).parent().parent().remove(); //remove row from DOM

				var item_id = $(this).attr('id');
				
				$.each(prices, function(i){
					if(prices[i].id == parseInt(item_id)){
						prices.splice(i,1); //remove item from prices[]
						return false;
					}
				});
				calculate();
			}else{
				alert('You cannot remove this row!');
			}
		}); 
	});
	
	// add currently loaded items' prices to prices[] array.
	function init(numOfItems){
		for(var i = 1; i <= numOfItems; i++){
			prices.push({
				id: i,
				qty: $('#qty'+i+'').val(),
				price: $('#qty'+i+'').val() * $('#unit_price'+i+'').val()
			});
		}
		console.log(prices);
	}
	//get item price from db
	function setVal(rowIndex, y){
		var data, qty, unit_cost, price;
		// on cost change. first row
		if(rowIndex == null && y == false){
			id = 1;
			qty 		= parseInt($('#qty1').val());
			unit_cost	= parseFloat($('#unit_price1').val());

			if(!unit_cost) unit_cost = 0;

			price = qty * unit_cost;

			$('#sub_total1').val(price);
			$('#price1')    .html('<strong>'+ numeral(price).format('0.00') +'</strong>');

			remove(1);

			prices.push({ //add item total to prices[]
				id: 	1,
				qty: 	qty,  
				price: 	price
			});
			calculate();
		// on cost change. dynamic row
		} else if(rowIndex != null && y == false) {
			if($('#unit_price'+rowIndex+'').val() == ''){
				alert('Please input a number!');
				return;
			}

			qty = parseInt($('#qty'+rowIndex+'').val());
			unit_cost = parseFloat($('#unit_price'+rowIndex+'').val());

			if(!unit_cost) unit_cost = 0;

			price = qty * unit_cost;

			$('#sub_total'+rowIndex+'').val(price);
			$('#price'+rowIndex+'')   .html('<strong>'+ numeral(price).format('0.00') +'</strong>');

			remove(rowIndex);

			prices.push({ //add item total to prices[]
				id: 	rowIndex,
				qty: 	qty,  
				price: 	price
			});
			calculate();
		// on qty change
		} else if(rowIndex && y == true) {
			id = rowIndex;
			qty 		= parseInt($('#qty'+ rowIndex).val());
			unit_cost	= parseFloat($('#unit_price'+ rowIndex).val());
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
		} else {
			if(rowIndex == null ) {
				if($('#qty1').val() == ""){
					$('#qty1').popover('show');

					return false;
				} else {
					data = $('#unit1').serialize();
					qty = $('#qty1').val();
				}
			} else {
				if($('#qty'+rowIndex+'').val() == ""){
					$('#qty'+rowIndex+'').popover('show');

					return false;
				} else {
					data = $('#unit'+rowIndex+'').serialize();
					qty = $('#qty'+rowIndex+'').val();
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
							if(rowIndex == null){

								$('tr#1 #hidden').val('');
								$('#unit_price1').val('');
								$('#price1').html('<strong> '+ numeral(price).format('0.00') +'</strong>');
								remove(1);
							}else{
								$('#hidden'+ rowIndex).val('');
								$('#unit_price'+rowIndex+'').val('');
								$('#price'+rowIndex+'').html('<strong> '+ numeral(price).format('0.00') +'</strong>');
								remove(rowIndex);
							}
							calculate();
							return;
						}
						if(rowIndex == null){
							unit_cost = result.result.unit_cost;
							$('#hidden1')	.val(result.result.cost_id);
							$('#unit1')		.val(result.result.unit);
							$('#unit_price1').val(unit_cost);

							price = qty * unit_cost; //compute line total

							$('#sub_total1').val(price);
							$('#price1')   .html('<strong>'+ numeral(price).format('0.00') +'</strong>');
							remove(1); //check if id exists in prices[]
							prices.push({ //add item total to prices[]
								id: 	1,
								qty: 	qty,  
								price: 	price
							});
						}else{
							unit_cost = result.result.unit_cost;
							$('#hidden'+rowIndex+'')	 .val(result.result.cost_id);
							$('#unit'+rowIndex+'')		 .val(result.result.unit);
							$('#unit_price'+rowIndex+'').val(unit_cost);

							price = qty * unit_cost; //compute line total

							$('#sub_total'+rowIndex+'').val(price);
							$('#price'+rowIndex+'')   .html('<strong>'+ numeral(price).format('0.00') +'</strong>');
							remove(rowIndex); //check if id exists in prices[]
							prices.push({ //add item total to prices[]
								id: 	rowIndex,
								qty: 	qty,
								price: 	price
							});
							}
						}
						calculate();
					}
			});
		}	
	}
	//current row price has changed, replace the old price with the new one
	function remove(rowIndex){
		for(var i in prices){
			if(prices[i]['id'] == rowIndex){
				 prices.splice(i,1); //remove from array where id == x
			}
		}
	}
	//compute grand total price
	function calculate(){
		var total = 0;

		for(var i in prices){
			total += prices[i]["price"];
		}

		$('input#total').val(total);
		$('#total').html('<strong>'+ numeral(total).format('0.00') +'</strong>');
	}
</script>
<!-- submit -->
<script type='text/javascript'>	
	$('#myForm').submit(function(e){
		e.preventDefault();
		$('#consignee,#consignor,#address_1,#address_2,#payment_terms,#dr_number,#truck').removeClass('has-error');
		$('#consignee .error-message,#consignor .error-message,#address_1 .error-message,#address_2 .error-message,#payment_terms .error-message,#dr_number .error-message').empty();
		$('#error').hide();
		showloader = true;
		var data = $(this).serialize();
	
		$.ajax({
			type:'post',
			url: '<?php echo base_url();?>waybill/save',
			data: data,
			dataType: 'json',
			success: function(result){
				if(!result.success){
					window.scrollTo(0, 0);
					if(!result.error.consignee && result.error.ce_id){
						$('.modal-body').html("The name you entered for <strong class='text-danger'> CONSIGNEE </strong> is unknown.<br> Are you trying to add a <strong class='text-primary'>NEW CUSTOMER</strong>?");
						$('#myModal').modal();
					}
					if(!result.error.consignor && result.error.cr_id){
						$('.modal-body').html("The name you entered for <strong class='text-danger'> CONSIGNOR </strong> is unknown.<br> Are you trying to add a <strong class='text-primary'>NEW CUSTOMER</strong>?");
						$('#myModal').modal();
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
					if(result.error.shipment_date){
						$('#shipment_date').addClass('has-error');
						$('#shipment_date .error-message').html(result.error.shipment_date);
					}
					if(result.error.truck){
						$('#truck').addClass('has-error');
						$('#truck .error-message').html(result.error.truck);
					}
					if(result.error.quantity){
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
				}else{
					$('.notification #message').html(' Waybill has been updated.'); 
					$('.notification').slideDown('fast');
					window.setTimeout(close, 3000);
				}
			}
		});
	});

	function close(){
		$('.notification').slideUp('slow');
	}
	
	function itemError(){
		$('#error').html('<p class="alert alert-danger">Please fill-out required fields.</p>');
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
					console.log(result);
				}
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