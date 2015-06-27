<?php include_once('/../header.php');?>
<?php include_once('/../nav.php');?>
<div class="container">
  <div class="row">
    <input type="text" id="field" placeholder="Start typing.." class="typeahead" data-items="">  
  </div>
  <div class="row" id="addRow">
    <button id="btnAddRow" class="btn">Add Row</button> 
  </div>
</div>
<script>
	var typeaheadSettings = {
		source: function (typeahead, process) {
		$.ajax({
            type: 'post',
            url: '<?php echo base_url();?>waybill/typeAhead',
            dataType: 'json',
            success: function(result){
             process(result);
          }
       });
		}
	};

	$('.typeahead').typeahead(typeaheadSettings); /* init first input */

	$('#btnAddRow').click(function(){
	  	var newRow = $('<div class="row"><input type="text" placeholder="Start typing.." class="typeahead" data-items="3"></div>');
		$('#addRow').append(newRow);
	  	$('.typeahead').trigger('added');
	  
	});

	$('.typeahead').on('added',function(){
		$('.typeahead').typeahead(typeaheadSettings);
	});
</script>