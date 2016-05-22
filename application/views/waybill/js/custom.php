<!-- Load Source Scripts -->
<script src='<?php echo base_url();?>assets/js/tablesorter.min.js'></script>
<script src='<?php echo base_url();?>assets/js/typeahead.js'></script>
<script src='<?php echo base_url();?>assets/js/numeral.min.js'></script>
<script src='<?php echo base_url();?>assets/js/jquery.loader.min.js'></script>

<!-- Custom Scripts -->
<script type='text/javascript'>
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();
		$('.sidebar-menu > li').removeClass('active');
		$('.sidebar-menu > li:nth-child(3)').addClass('active');
		$('.sidebar-menu > li.active .treeview-menu li:nth-child(2)').addClass('active');
		
		$('#myTable').tablesorter({
			headers : { 0 : { sorter: false }, 7: { sorter: false}, 8: { sorter: false}, 9: { sorter: false} },
			theme: 'default'
		});

		$('#updateDs').on('show.bs.modal',function(e){
			var waybill_number = $(e.relatedTarget).data('waybill-number');
			$(e.currentTarget).find('input[name="waybill_number"]').val(waybill_number);
		});
	});

	function printByBatch(){
		var data = $('#myForm').serialize();
		
		if(!data){
			alert('Please select items to be printed.');

			return;
		}
		
		$.ajax({
			type: 'post',
			data: data,
			url: '<?php echo base_url();?>waybill/printByBatch',
			success: function(result){
				$('#i_frame').attr('srcdoc', result);
			}
		});
	}
	// Waybill Data table
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
				'<td style="width:100px"><?php echo form_input(array("id"=>"unit'+x+'","name"=>"unit[]","class"=>"form-control typeahead","placeholder"=>"Unit", "onchange"=>"getUnit('+x+')"));?></td>'+
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

	// Waybill New
	var prices = [];

	function setVal(rowIndex, y){
		var fqty = $('#qty'),
		 	funitcost = $('#unit_price'),
		 	fsubtotal = $('#sub_total'),
		 	fprice = $('#price'),
		 	funit = $('#unit'),
		 	id = 1, data, qty, unit_cost, price
		 	;

		if(rowIndex != null) {
			id = rowIndex;
			fqty = $('#qty' + rowIndex);
			funitcost = $('#unit_price' + rowIndex);
			funit = $('#unit' + rowIndex);
		}

		qty = parseInt(fqty.val());
		unit_cost = parseFloat(funitcost.val());

		if(y) {
			if(!qty) qty = 0;
		}

		if(!unit_cost) {
			unit_cost = 0;
		}

		price = qty * unit_cost;

		fsubtotal.val(price);
		fprice.html('<strong> '+ numeral(price).format('0.00') +'</strong>');
		
		remove(id);

		prices.push({
			id: id,
			qty: qty,
			price: price
		});

		calculate();

		/*// on cost change. first row
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
			var quantity, unit, qty, data;

			if(rowIndex == null) {
				unit = $('#unit');
				quantity  = $('#qty');
			} else {
				unit = $('#unit' + rowIndex);
				quantity  = $('#qty' + rowIndex);
			}

			if(quantity.val() == null || quantity.val() == '') {
				
				quantity.popover('show');

				return;
			}

			data = unit.serialize();
			qty  = quantity.val();
		
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
		}	*/
	}

	function getUnit(rowIndex) {
		var fquantity = $('#qty'), funit = $('#unit'), qty, data, 
			fhidden			 = $('#hidden'), 
			funitprice		 = $('#unit_price'), 
			fprice 			 = $('#price'),
			fsubtotal		 = $('#sub_total'),
			fitemdescription = $('#item_description')
			;

		if(rowIndex != null) {
			funit = $('#unit' + rowIndex);
			fquantity  = $('#qty' + rowIndex);
			fhidden			 = $('#hidden' + rowIndex), 
			funitprice		 = $('#unit_price' + rowIndex), 
			fprice 			 = $('#price' + rowIndex),
			fsubtotal		 = $('#sub_total' + rowIndex),
			fitemdescription = $('#item_description' + rowIndex)
			;
		}

		if(fquantity.val() == null) {
			
			fquantity.popover('show');

			return;
		}

		data = funit.serialize();
		qty  = fquantity.val();
		
		$.ajax({
			type: 'post',
			data: data.slice(0, -8),
			url: '<?php echo base_url();?>unit_category/getItemPrice',
			dataType:'json',
			success: function(result){
				if(result.success) {
					if(result.result.cost_id == null){ // if cost_id is null then the user is the trying to input an uncategorized unit

						if(rowIndex != null) {
							fhidden = $('#hidden' + rowIndex);
							funitprice = $('#unit_price' + rowIndex); 
							fprice = $('#price' + rowIndex);
							fsubtotal = $('#sub_total' + rowIndex);
							fitemdescription = $('#item_description' + rowIndex);
						}

						remove(rowIndex);

						calculate();
						
						return;
					}

					unit_cost = result.result.unit_cost;

					fhidden.val(result.result.cost_id);
					funit.val(result.result.unit);
					funitprice.val(unit_cost);

					price = qty * unit_cost; //compute line total

					fsubtotal.val(price);
					fprice.html('<strong> '+ numeral(price).format('0.00') +'</strong>');

					remove(rowIndex);

					prices.push({ //add item total to prices[]
						id: 	1,
						qty: 	qty,  
						price: 	price
					});
 
					fitemdescription.focus();

					calculate();
				}
			}
		});
	}

	//if presented id exists in array,remove associated values
	function remove(rowIndex){
		for(var i in prices){
			if(prices[i]['id'] == rowIndex){
				prices.splice(i, 1); //remove from array where id = rowIndex
			}
		}
	}

	//calculate GRAND TOTAL
	function calculate(){
		var total = 0;

		for(var i in prices){
			total += prices[i]["price"];
		}
		
		$('input#total').val(total);
		$('input#amount').val(numeral(total).format('0.00'));
		$('#total').html('<strong> ' + numeral(total).format('0.00') +'</strong>');
	}

	function close(){
		$('.notification').fadeOut('slow');
	}

	function itemError(){
		$('#error').html('<div class="alert alert-warning"><span><i class="fa fa-warning fa-4x"></i></span>' +
			'<h5>There were problems with your inputs. Please fill-out required fields and with the correct format.</h5></div>');
		$('#error').show();
	}

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
