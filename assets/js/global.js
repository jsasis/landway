$(document).ready(function(e){
	// AJAX Loading Animation
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
	// Data Table Selection
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
	// Data Table Delete Selection
	$('#delete').click(function(e){
		e.preventDefault();
		var data = $('#myForm').serialize();

		if(data == "") { 
			$('.modal-header').html('<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Alert</h4>');
			$('.modal-body').html('<p>Please select records to be deleted.</p>');
			$('.modal-footer').hide();
		} else {
			$('.modal-header').html('<h4><span class="glyphicon glyphicon-info-sign"></span> Confirm</h4>');
			$('.modal-body').html('<p>Are you sure you want to delete?</p>');
			$('.modal-footer').show();
		}

		$('#myModal').modal();

		$('#yes').click(function(){
			showloader = true;
			$.ajax({
				type: 'post',
				url: '<?php echo base_url();?>waybill/delete',
				data: data,
				success: function(result){
					window.location  = '<?php echo base_url();?>waybill';
				}
			});
		});
	}); 
	// Datepicker
	/*$('.datepicker').datepicker({
		autoclose: true,
		todayHighlight: true,
		format: "yyyy-mm-dd",
	}).on('changeDate', function(e){
		$('#search_key').val(e.format("yyyy-mm-dd"));
		$('#search_form').submit();
	});*/
});