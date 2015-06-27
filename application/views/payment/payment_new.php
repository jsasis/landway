<html lang='en'>
<?php include_once('/../header.php');?>
<?php include_once('/../nav.php');?>
<body>
	<div id='page-wrapper'>
		<div class='row'>
			<div class='col-md-12 main'>
				<div class='row'>
					<div class='col-md-6'>
						<h1 class='text-info manage'><span class='glyphicon glyphicon-th-list'></span> Manage <small>Payments</small></h1>
						<div class='wrapper'>
							<?php echo form_open('',array('id'=>'myForm'));?>
							<div class='form-group' id='waybill_number'>
								<?php echo form_label('Waybill #','',array('class'=>'control-label'));?>
								<?php $attrib = "class = 'form-control'";?>
								<?php $options[''] = "Select Waybill";?>
								<?php foreach($waybill_numbers as $waybill_number):?>
								<?php $options[$waybill_number->waybill_number] = $waybill_number->waybill_number;?>
								<?php endforeach;?>
								<?php echo form_dropdown('waybill_number', $options, '', $attrib);?>
								<span class='error-message control-label'> </span>
							</div>
					      	<div class='form-group' id='payment_terms'>
					      		<?php echo form_label('Payment Terms','',array('class'=>'control-label'));?>
					      		<?php $data = array(''=>'Choose Terms of Payment','prepaid'=>'Prepaid','collect'=>'Collect');?>
					      		<?php $attrib = "class='form-control'";?>
					      		<?php echo form_dropdown('payment_terms', $data, '', $attrib);?>
					      		<span class='error-message control-label'> </span>
					      	</div>
					      	<div class='form-group' id='discount'>
						      	<?php echo form_label('Discount','',array('class'=>'control-label'));?>
						      	<?php echo form_input(array('id'=>'discount','name'=>'discount','class'=>'form-control','placeholder'=>'Discount'));?>
					      	</div>
					      	<div class='form-group' id='amount'>
						      	<?php echo form_label('Amount Paid','',array('class'=>'control-label'));?>
						      	<?php echo form_input(array('id'=>'amount','name'=>'amount','class'=>'form-control','placeholder'=>'Amount Paid'));?>
					      	</div>
					      	<div class='form-group'>
					      		
					      		<button type='submit' class='btn btn-success'>Save</button>
					      	</div>
					      	<?php echo form_close();?>
						</wrapper>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</body>
</html>
<script type='text/javascript'>
	$('#myForm').submit(function(e){
		e.preventDefault();
		var data = $(this).serialize();

		$('#waybill_number,#payment_terms, #amount').removeClass('has-error');
		$('#waybill_number .error-message, #payment_terms .error-message, #amount .error-message').empty();

		$.ajax({
			type: 'post',
			data: data,
			url:  '<?php echo base_url();?>payment/save',
			dataType: 'json',
			success: function(response){
				if(!response.success){
					if(response.error.waybill_number){
						$('#waybill_number').addClass('has-error');
						$('#waybill_number .error-message').html(response.error.waybill_number);
					}
					if(response.error.payment_terms){
						$('#payment_terms').addClass('has-error');
						$('#payment_terms .error-message').html(response.error.payment_terms);
					}
					if(response.error.amount){
						$('#amount').addClass('has-error');
						$('#amount .error-message').html(response.error.amount);
					}
				}else{
					$('#myForm')[0].reset();
					$('.notification').html("<strong><h4><span class='glyphicon glyphicon-ok'></span><span id='message'> Payment has been saved!</span></h4></strong>");
					$('.notification').slideDown('slow');
					$('.notification').fadeOut(4000);
				}
			}
		});
	});
</script>